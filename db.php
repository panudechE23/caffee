<?php
$host = 'localhost';  // หรือ IP ของเซิร์ฟเวอร์ฐานข้อมูล
$dbname = 'caffee';  // ชื่อฐานข้อมูล
$username = 'root';  // ชื่อผู้ใช้ฐานข้อมูล
$password = '';  // รหัสผ่านฐานข้อมูล

try {
    // สร้างการเชื่อมต่อ PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
