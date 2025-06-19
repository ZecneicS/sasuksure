<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ตรวจสอบว่าผู้ใช้ล็อกอินมาอย่างถูกต้องและต้องเปลี่ยนรหัสผ่านจริงๆ
if (!isset($_SESSION["user_sasuksure"]) || !isset($_SESSION["force_password_change"])) {
    // ถ้าไม่ ให้ส่งกลับไปหน้า login
    header("Location: login.php");
    exit();
}

// ส่วนของการประมวลผลฟอร์ม
if (!empty($_POST["submit_new_password"])) {
    include("pdo-connect.php");
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION['user_sasuksure'];
    $error_messages = [];

    // 1. ตรวจสอบว่ารหัสผ่านใหม่ตรงกันหรือไม่
    if (empty($new_password) || $new_password !== $confirm_password) {
        $error_message = "รหัสผ่านใหม่และการยืนยันรหัสผ่านไม่ตรงกัน หรือเป็นค่าว่าง";
    } else {
        // 2. ตรวจสอบเงื่อนไขความปลอดภัยของรหัสผ่าน
        if (strlen($new_password) < 8) {
            $error_messages[] = "ต้องมีความยาวอย่างน้อย 8 ตัวอักษร";
        }
        if (!preg_match('/[A-Za-z]/', $new_password)) {
            $error_messages[] = "ต้องมีตัวอักษรภาษาอังกฤษอย่างน้อย 1 ตัว";
        }
        if (!preg_match('/[0-9]/', $new_password)) {
            $error_messages[] = "ต้องมีตัวเลขอย่างน้อย 1 ตัว";
        }
        if (!preg_match('/[^A-Za-z0-9]/', $new_password)) {
            $error_messages[] = "ต้องมีอักขระพิเศษอย่างน้อย 1 ตัว (เช่น !@#$%)";
        }

        // 3. ถ้าไม่มี error ให้ดำเนินการต่อ
        if (empty($error_messages)) {
            // เข้ารหัสรหัสผ่านใหม่
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            // อัปเดตลงฐานข้อมูล
            $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
            if ($stmt->execute([':password' => $hashed_password, ':id' => $user_id])) {
                // อัปเดตสำเร็จ ลบ Flag ออกจาก Session
                unset($_SESSION["force_password_change"]);
                
                // ทำลาย sessionเก่าเพื่อให้ login ใหม่
                session_destroy();

                // แจ้งเตือนและส่งไปยังหน้า login
                echo "<script>
                        alert('เปลี่ยนรหัสผ่านสำเร็จ! กรุณาเข้าสู่ระบบอีกครั้ง');
                        window.location.href = 'login.php';
                      </script>";
                exit();

            } else {
                $error_message = "เกิดข้อผิดพลาดในการบันทึกรหัสผ่านใหม่";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บังคับเปลี่ยนรหัสผ่าน</title>
    <!-- สามารถเพิ่ม CSS Framework เช่น Bootstrap หรือ Tailwind ได้ที่นี่ -->
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f4f4f4; margin: 0; padding: 20px; box-sizing: border-box; }
        .container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        input { width: 100%; padding: 0.5rem; margin-bottom: 1rem; box-sizing: border-box; }
        button { width: 100%; padding: 0.7rem; background-color: #007bff; color: white; border: none; cursor: pointer; border-radius: 4px; }
        .error { color: red; background-color: #ffebee; border: 1px solid #ef9a9a; padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .error ul { padding-left: 20px; margin: 0; }
        .requirements { font-size: 0.9em; color: #555; margin-bottom: 1rem; }
        .requirements ul { list-style-type: '✓ '; padding-left: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>กรุณาตั้งรหัสผ่านใหม่</h2>
        <p>เพื่อความปลอดภัยของบัญชี, คุณ <strong><?php echo htmlspecialchars($_SESSION["user_fullname"]); ?></strong> จะต้องตั้งรหัสผ่านใหม่ก่อนเข้าใช้งาน</p>
        
        <div class="requirements">
            <p><strong>เงื่อนไขรหัสผ่าน:</strong></p>
            <ul>
                <li>ความยาวไม่น้อยกว่า 8 ตัวอักษร</li>
                <li>มีตัวอักษรภาษาอังกฤษ (a-z, A-Z)</li>
                <li>มีตัวเลข (0-9)</li>
                <li>มีอักขระพิเศษ (!@#$%^&*)</li>
            </ul>
        </div>

        <?php if (!empty($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php elseif (!empty($error_messages)): ?>
            <div class="error">
                <strong>รหัสผ่านไม่ถูกต้อง:</strong>
                <ul>
                    <?php foreach ($error_messages as $msg): ?>
                        <li><?php echo $msg; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" novalidate>
            <label for="new_password">รหัสผ่านใหม่:</label>
            <input type="password" id="new_password" name="new_password" required>
            
            <label for="confirm_password">ยืนยันรหัสผ่านใหม่:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            
            <button type="submit" name="submit_new_password" value="1">บันทึกรหัสผ่านใหม่</button>
        </form>
    </div>
</body>
</html>
