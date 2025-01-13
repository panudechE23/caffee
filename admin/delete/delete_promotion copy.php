<?php
require '../../db.php';
session_start();

// รับค่า ID จาก URL
$id_honordetail = $_GET['id'] ?? null;

if (!$id_honordetail) {
    $_SESSION['error'] = "ไม่พบ ID ของโปรโมชั่นที่ต้องการลบ!";
    header('Location: honordetail.php');
    exit;
}

try {
    // ดึงข้อมูลโปรโมชั่นเพื่อตรวจสอบและลบไฟล์
    $stmt = $pdo->prepare("SELECT * FROM honordetail WHERE id_honordetail = :id");
    $stmt->execute([':id' => $id_honordetail]);
    $honordetail = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$honordetail) {
        $_SESSION['error'] = "ไม่พบข้อมูลโปรโมชั่นที่ต้องการลบ!";
        header('Location: honordetail.php');
        exit;
    }

    // ลบไฟล์รูปภาพที่เกี่ยวข้อง
    $upload_dir = "../../assets/img/honordetail/$id_honordetail/";
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
    $stmt = $pdo->prepare("DELETE FROM honordetail WHERE id_honordetail = :id");
    $stmt->execute([':id' => $id_honordetail]);

    $_SESSION['success'] = "ลบข้อมูลโปรโมชั่นสำเร็จ!";
    header('Location: ../honordetail.php');
    exit;
} catch (Exception $e) {
    $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
    header('Location: ../honordetail.php');
    exit;
}
