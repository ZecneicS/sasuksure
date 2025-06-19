<?php
// การตั้งค่าเริ่มต้นและ Session
ini_set('memory_limit', '-1');
session_start();

// 1. ตรวจสอบ Session และตอบกลับเป็น JSON หากไม่พบ
if (empty(trim($_SESSION["user_sasuksure"]))) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 0, 'message' => 'Session หมดอายุ กรุณาเข้าสู่ระบบใหม่']);
    exit();
}

// 2. ตรวจสอบว่ามีการส่งข้อมูลแบบ POST มาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 3. เรียกใช้ไฟล์เชื่อมต่อ PDO ที่ปลอดภัย
    require_once __DIR__ . '/pdo-connect.php';

    // 4. เริ่มต้น Transaction เพื่อให้แน่ใจว่าทุกคำสั่งจะสำเร็จทั้งหมด หรือไม่สำเร็จเลยสักอย่าง
    try {
        $pdo->beginTransaction();

        // --- 5. เตรียมข้อมูล Input อย่างปลอดภัย ---
        $id = trim($_POST["assign_id"]);
        $assign_by_id = trim($_SESSION["user_sasuksure"]);
        $assign_by_fullname = trim($_SESSION["user_fullname"]);

        // แยกข้อมูลผู้รับมอบหมาย (เพิ่มการตรวจสอบ)
        $assign = trim($_POST["district_id"]);
        $find_stop = strpos($assign, ":");

        if ($find_stop === false) {
            throw new Exception("รูปแบบข้อมูลผู้รับมอบหมายไม่ถูกต้อง");
        }
        
        $assign_to_id = substr($assign, 0, $find_stop);
        $assign_to_fullname = substr($assign, $find_stop + 1);

        // --- 6. คำสั่ง UPDATE ข่าว (ใช้ Prepared Statement) ---
        $sql_update = "UPDATE news SET 
                           assign_by_id = :assign_by_id, 
                           assign_by_fullname = :assign_by_fullname, 
                           assign_to_id = :assign_to_id, 
                           assign_to_fullname = :assign_to_fullname, 
                           assign_timestamp = NOW() 
                       WHERE id = :id";
        
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            ':assign_by_id'       => $assign_by_id,
            ':assign_by_fullname' => $assign_by_fullname,
            ':assign_to_id'       => $assign_to_id,
            ':assign_to_fullname' => $assign_to_fullname,
            ':id'                 => $id
        ]);

        // --- 7. คำสั่ง INSERT Log (ใช้ Prepared Statement) ---
        $sql_log = "INSERT INTO news_log (sid, assign_by_id, assign_by_fullname, assign_to_id, assign_to_fullname, assign_timestamp, time_stamp) 
                    VALUES (:sid, :assign_by_id, :assign_by_fullname, :assign_to_id, :assign_to_fullname, NOW(), NOW())";
        
        $stmt_log = $pdo->prepare($sql_log);
        $stmt_log->execute([
            ':sid'                => $id,
            ':assign_by_id'       => $assign_by_id,
            ':assign_by_fullname' => $assign_by_fullname,
            ':assign_to_id'       => $assign_to_id,
            ':assign_to_fullname' => $assign_to_fullname
        ]);

        // --- 8. ดึงข้อมูลอีเมลและหัวข้อข่าว ---
        $stmt_email = $pdo->prepare("SELECT email FROM users WHERE id = :assign_to_id");
        $stmt_email->execute([':assign_to_id' => $assign_to_id]);
        $email = $stmt_email->fetchColumn(); 

        $stmt_title = $pdo->prepare("SELECT news_title FROM news WHERE id = :id");
        $stmt_title->execute([':id' => $id]);
        $news_title = $stmt_title->fetchColumn();

        // --- 9. ยืนยันการทำรายการทั้งหมด (Commit Transaction) ---
        $pdo->commit();

        // --- 10. สร้าง JSON Response สำหรับการตอบกลับที่สำเร็จ ---
        $response = [
            'status'     => 1,
            'message'    => 'ทำรายการสำเร็จ',
            'id'         => $id,
            'news_title' => $news_title,
            'email'      => $email ?? null 
        ];

    } catch (Exception $e) {
        // --- 11. หากเกิดข้อผิดพลาดใดๆ ขึ้น ให้ย้อนกลับ (Rollback) ---
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        
        error_log("Assign news failed: " . $e->getMessage());

        $response = [
            'status'  => 0,
            'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล'
        ];
    }

    // --- 12. ส่งผลลัพธ์กลับเป็น JSON ---
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>