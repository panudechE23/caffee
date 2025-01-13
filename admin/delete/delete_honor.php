<?php
require '../../db.php';
session_start();

if (isset($_GET['id'])) {
    try {
        $id_honor = $_GET['id'];

        // ตรวจสอบว่ามีข้อมูลที่ต้องการลบหรือไม่
        $stmt = $pdo->prepare("SELECT * FROM honor WHERE id_honor = :id_honor");
        $stmt->execute([':id_honor' => $id_honor]);
        $honor = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$honor) {
            throw new Exception("ไม่พบข้อมูลที่ต้องการลบ");
        }

        // ลบโฟลเดอร์รูปภาพ
        $upload_dir = "../../img/honor/$id_honor/";
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
        $stmt = $pdo->prepare("DELETE FROM honor WHERE id_honor = :id_honor");
        $stmt->execute([':id_honor' => $id_honor]);

        $_SESSION['success'] = "ลบข้อมูลสำเร็จ!";
        header("Location: ../table_honor.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
        header("Location: ../table_honor.php");
        exit;
    }
} else {
    $_SESSION['error'] = "คำขอไม่ถูกต้อง!";
    header("Location: ../table_honor.php");
    exit;
}
