<?php
// เริ่ม Session อย่างปลอดภัย
session_start();

// ====== การตั้งค่า Error Handling ======
// (สำหรับ Production ควรตั้งใน php.ini)
ini_set('display_errors', 0); 
ini_set('log_errors', 1);

// ====== 1. วิเคราะห์ URL ======
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($request_uri, '/');
$segments = explode('/', $path);

$main_route = $segments[0] === '' ? 'home' : $segments[0];


// ====== 2. Whitelist Router ======
try {
    switch ($main_route) {
        case 'home':
            require 'htdocs/index.php';
            break;

        case 'products':
            // สมมติว่า URL คือ /products/123
            // 3. กรอง Input
            $product_id = filter_var($segments[1] ?? null, FILTER_VALIDATE_INT);
            if ($product_id === false) {
                 throw new Exception("Invalid Product ID");
            }
            
            // 4. ป้องกัน SQL Injection (โค้ดส่วนนี้จะอยู่ในไฟล์ products.php)
            // $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            // $stmt->execute([$product_id]);
            // $product = $stmt->fetch();
            
            // 5. ป้องกัน XSS (โค้ดแสดงผลจะอยู่ในไฟล์ products.php)
            // echo '<h1>' . htmlspecialchars($product['name']) . '</h1>';
            
            require 'pages/products.php';
            break;

        case 'contact':
            require 'pages/contact.php';
            break;

        default:
            // ถ้าไม่ตรงกับ route ไหนเลย
            http_response_code(404);
            require 'pages/404.php';
            break;
    }
} catch (Exception $e) {
    // จัดการข้อผิดพลาดอื่นๆ ที่อาจเกิดขึ้น
    error_log($e->getMessage()); // บันทึก error ลง log
    http_response_code(500);
    require 'pages/500.php'; // แสดงหน้า Internal Server Error
}
?>