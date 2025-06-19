<?php
// --- Configuration ---
$target_dir = "uploads/"; // โฟลเดอร์สำหรับเก็บไฟล์ที่อัปโหลด
$allowed_extensions = ["jpg", "jpeg", "png"]; // นามสกุลไฟล์ที่อนุญาต
$max_file_size = 1 * 1024 * 1024; // ขนาดไฟล์สูงสุดที่อนุญาต (1MB)

// --- Initialization ---
$upload_ok = 1; // ตั้งค่าเริ่มต้นว่าอนุญาตให้อัปโหลด
$error_messages = []; // สำหรับเก็บข้อความข้อผิดพลาด

// ตรวจสอบว่ามีไฟล์ถูกส่งมาหรือไม่
if (isset($_FILES["file_to_upload"]) && $_FILES["file_to_upload"]["error"] == 0) {
    $file_tmp_name = $_FILES["file_to_upload"]["tmp_name"];
    $original_file_name = basename($_FILES["file_to_upload"]["name"]);
    $file_size = $_FILES["file_to_upload"]["size"];

    // ดึงนามสกุลไฟล์และแปลงเป็นตัวพิมพ์เล็ก
    $file_extension = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));

    // สร้างชื่อไฟล์ใหม่ที่ไม่ซ้ำกัน เพื่อป้องกันการเขียนทับและเพิ่มความปลอดภัย
    // ใช้ timestamp และส่วนหนึ่งของชื่อสุ่มเพื่อลดโอกาสซ้ำ (ถ้าต้องการความ unique สูงกว่านี้ อาจใช้ uniqid())
    $new_file_name = "sasuksure_" . time() . "_" . substr(md5(rand()), 0, 8) . "." . $file_extension;
    $destination_path = $target_dir . $new_file_name;

    // --- Start File Validation ---

    // 1. ตรวจสอบว่าเป็นไฟล์ภาพจริงหรือไม่ (ใช้ getimagesize)
    // getimagesize จะคืนค่า false หากไม่ใช่ไฟล์ภาพที่มันรู้จัก หรือไฟล์เสียหาย
    $check = @getimagesize($file_tmp_name); // ใช้ @ เพื่อ suppress warning ถ้าไฟล์ไม่ใช่ภาพ
    if ($check === false) {
        $error_messages[] = "ไฟล์ที่เลือกไม่ใช่ไฟล์ภาพที่ระบบรู้จัก หรือไฟล์อาจเสียหาย";
        $upload_ok = 0;
    } else {
        // 2. ตรวจสอบ MIME type จาก getimagesize ให้ตรงกับนามสกุลที่อนุญาต (เพิ่มความแม่นยำ)
        $image_mime = $check['mime'];
        $allowed_mimes = ['image/jpeg', 'image/png'];
        if (!in_array($image_mime, $allowed_mimes)) {
            $error_messages[] = "ประเภทของไฟล์ภาพ (MIME type: " . htmlspecialchars($image_mime) . ") ไม่ได้รับอนุญาต";
            $upload_ok = 0;
        }
        // 3. ตรวจสอบนามสกุลไฟล์อีกครั้ง (เผื่อกรณี MIME type ผ่าน แต่นามสกุลไม่ตรง)
        if (!in_array($file_extension, $allowed_extensions)) {
            $error_messages[] = "อนุญาตให้อัปโหลดเฉพาะไฟล์ภาพนามสกุล: " . implode(", ", $allowed_extensions) . " เท่านั้น";
            $upload_ok = 0;
        }
    }

    // 4. ตรวจสอบขนาดไฟล์
    if ($file_size > $max_file_size) {
        $error_messages[] = "ไฟล์มีขนาดใหญ่เกินไป (สูงสุด " . ($max_file_size / 1024 / 1024) . "MB)";
        $upload_ok = 0;
    }

    // (Optional but Recommended) ตรวจสอบว่าโฟลเดอร์ปลายทางมีอยู่และเขียนได้หรือไม่
    if (!is_dir($target_dir)) {
        if (!mkdir($target_dir, 0755, true)) { // พยายามสร้างโฟลเดอร์ถ้ายังไม่มี
             $error_messages[] = "ไม่สามารถสร้างโฟลเดอร์สำหรับอัปโหลดได้";
             $upload_ok = 0;
        }
    } elseif (!is_writable($target_dir)) {
        $error_messages[] = "โฟลเดอร์สำหรับอัปโหลดไม่สามารถเขียนได้";
        $upload_ok = 0;
    }

    // --- End File Validation ---

} else {
    // จัดการกับข้อผิดพลาดในการอัปโหลดเบื้องต้น (เช่น ไม่มีไฟล์, ไฟล์ใหญ่เกินกว่าที่ php.ini กำหนด)
    $upload_ok = 0;
    switch ($_FILES["file_to_upload"]["error"]) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            $error_messages[] = "ไฟล์มีขนาดใหญ่เกินกว่าที่กำหนด";
            break;
        case UPLOAD_ERR_PARTIAL:
            $error_messages[] = "ไฟล์ถูกอัปโหลดเพียงบางส่วน";
            break;
        case UPLOAD_ERR_NO_FILE:
            $error_messages[] = "ไม่ได้เลือกไฟล์ใดๆ สำหรับอัปโหลด";
            break;
        default:
            $error_messages[] = "เกิดข้อผิดพลาดที่ไม่ทราบสาเหตุระหว่างการอัปโหลด";
            break;
    }
}


// --- Process Upload ---
if ($upload_ok == 1) {
    // ถ้าทุกอย่างถูกต้อง, พยายามย้ายไฟล์ไปยังโฟลเดอร์ปลายทาง
    if (move_uploaded_file($file_tmp_name, $destination_path)) {
        echo "<p style='color:green;'>ไฟล์ <strong>" . htmlspecialchars($original_file_name) . "</strong> ถูกอัปโหลดเรียบร้อยแล้ว</p>";
        echo "<p>บันทึกเป็น: <strong>" . htmlspecialchars($new_file_name) . "</strong></p>";
        // คุณอาจต้องการบันทึก $new_file_name หรือ $destination_path ลงในฐานข้อมูลที่นี่
    } else {
        $error_messages[] = "เกิดข้อผิดพลาดในการย้ายไฟล์ไปยังโฟลเดอร์ปลายทาง โปรดลองอีกครั้ง";
        echo "<p style='color:red;'><strong>ไม่สามารถอัปโหลดไฟล์ได้:</strong></p>";
        echo "<ul style='color:red;'>";
        foreach ($error_messages as $msg) {
            echo "<li>" . htmlspecialchars($msg) . "</li>";
        }
        echo "</ul>";
    }
} else {
    // ถ้ามีข้อผิดพลาดใดๆ เกิดขึ้น
    echo "<p style='color:red;'><strong>ไม่สามารถอัปโหลดไฟล์ได้ เนื่องจาก:</strong></p>";
    if (empty($error_messages) && isset($_FILES["file_to_upload"]) && $_FILES["file_to_upload"]["error"] != UPLOAD_ERR_NO_FILE) {
         // กรณีที่ $upload_ok เป็น 0 แต่ไม่มี error message ที่กำหนดเอง (อาจเป็นปัญหาที่ไม่คาดคิด)
        $error_messages[] = "เกิดข้อผิดพลาดที่ไม่สามารถระบุได้ชัดเจน โปรดตรวจสอบไฟล์และลองอีกครั้ง";
    }
    echo "<ul style='color:red;'>";
    foreach ($error_messages as $msg) {
        echo "<li>" . htmlspecialchars($msg) . "</li>";
    }
    echo "</ul>";
}
?>
