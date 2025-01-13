<?php
require '../../db.php';
session_start();

// รับค่า ID จาก URL
$id_promotion = $_GET['id'] ?? null;

if (!$id_promotion) {
    $_SESSION['error'] = "ไม่พบ ID ของโปรโมชั่นที่ต้องการลบ!";
    header('Location: promotion.php');
    exit;
}

try {
    // ดึงข้อมูลโปรโมชั่นเพื่อตรวจสอบและลบไฟล์
    $stmt = $pdo->prepare("SELECT * FROM promotion WHERE id_promotion = :id");
    $stmt->execute([':id' => $id_promotion]);
    $promotion = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$promotion) {
        $_SESSION['error'] = "ไม่พบข้อมูลโปรโมชั่นที่ต้องการลบ!";
        header('Location: promotion.php');
        exit;
    }

    // ลบไฟล์รูปภาพที่เกี่ยวข้อง
    $upload_dir = "../../img/promotion/$id_promotion/";
    if (is_dir($upload_dir)) {
        $files = glob($upload_dir . '*'); // ค้นหาไฟล์ทั้งหมดในโฟลเดอร์
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // ลบไฟล์
            }
        }
        rmdir($upload_dir); // ลบโฟลเดอร์
    }

    // ลบข้อมูลจากฐานข้อมูล
    $stmt = $pdo->prepare("DELETE FROM promotion WHERE id_promotion = :id");
    $stmt->execute([':id' => $id_promotion]);

    $_SESSION['success'] = "ลบข้อมูลโปรโมชั่นสำเร็จ!";
    header('Location: ../table_promotion.php');
    exit;
} catch (Exception $e) {
    $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
    header('Location: ../table_promotion.php');
    exit;
}
