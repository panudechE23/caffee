<?php
require '../../db.php';
session_start();

if (isset($_GET['id'])) {
    try {
        $id_news = $_GET['id'];

        // ตรวจสอบว่ามีข้อมูลที่ต้องการลบหรือไม่
        $stmt = $pdo->prepare("SELECT * FROM news WHERE id_news = :id_news");
        $stmt->execute([':id_news' => $id_news]);
        $news = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$news) {
            throw new Exception("ไม่พบข้อมูลที่ต้องการลบ");
        }

        // ลบโฟลเดอร์รูปภาพ
        $upload_dir = "../../img/news/$id_news/";
        if (is_dir($upload_dir)) {
            $files = glob($upload_dir . '*'); // ดึงรายการไฟล์ทั้งหมดในโฟลเดอร์
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); // ลบไฟล์
                }
            }
            rmdir($upload_dir); // ลบโฟลเดอร์
        }

        // ลบข้อมูลจากฐานข้อมูล
        $stmt = $pdo->prepare("DELETE FROM news WHERE id_news = :id_news");
        $stmt->execute([':id_news' => $id_news]);

        $_SESSION['success'] = "ลบข้อมูลสำเร็จ!";
        header("Location: ../table_news.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
        header("Location: ../table_news.php");
        exit;
    }
} else {
    $_SESSION['error'] = "คำขอไม่ถูกต้อง!";
    header("Location: ../table_news.php");
    exit;
}
