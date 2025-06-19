<?php

require 'vendor/autoload.php'; 

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// ดึงค่าจาก Environment Variables ที่โหลดมา
$db_host = $_ENV['DB_HOST'];
$db_name = $_ENV['DB_NAME'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASS'];
$db_charset = $_ENV['DB_CHARSET'];

// ตั้งค่า Options สำหรับ PDO
$options = [
    // 1. บังคับให้ใช้ Real Prepared Statements (ป้องกัน SQL Injection ได้สมบูรณ์ขึ้น)
    PDO::ATTR_EMULATE_PREPARES   => false,
    // 2. กำหนดรูปแบบการดึงข้อมูลเริ่มต้นเป็น Array ที่มี Key เป็นชื่อคอลัมน์
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // 3. คงการตั้งค่า Error Mode ที่ดีไว้อยู่แล้ว
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
];

$dsn = "mysql:host=$db_host;dbname=$db_name;charset=$db_charset";
try {
    // ส่ง $options เข้าไปเป็นพารามิเตอร์ที่ 4
    $conn = new PDO($dsn, $db_user, $db_pass, $options);
    //echo "Connected successfully";

} catch(PDOException $e) {
    // ใน Server จริง (Production) ไม่ควรแสดง $e->getMessage() ให้ผู้ใช้เห็น
    // ควรเก็บ Error ลง Log File และแสดงข้อความทั่วไปแทน
    error_log("Connection failed: " . $e->getMessage()); // บันทึกลง log
    die("เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล กรุณาลองใหม่อีกครั้ง"); // แสดงให้ผู้ใช้เห็น
}
?>