<!--?php
$connect = mysqli_connect("localhost","root","sasuksure@111165","sasuksure");
mysqli_set_charset($connect, "utf8");
?-->

<?php
// เรียกใช้ไฟล์ config อย่างปลอดภัย
require_once __DIR__ . '/../config/db_config.php';

// เชื่อมต่อฐานข้อมูล
$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// ตรวจสอบและจัดการข้อผิดพลาดในการเชื่อมต่อ
if (mysqli_connect_errno()) {
    error_log("Database connection failed: " . mysqli_connect_error());
    die("ขออภัย เกิดข้อผิดพลาดในการเชื่อมต่อกับระบบ");
}

// ตั้งค่า Charset
mysqli_set_charset($connect, "utf8");

// ไฟล์นี้พร้อมให้ไฟล์อื่น require ไปใช้งานต่อได้แล้ว
// ตัวแปร $connect คือ connection ที่ปลอดภัยและพร้อมใช้งาน
?>