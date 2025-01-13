<?php
session_start();
require '../../db.php';

if (isset($_GET['id'])) {
    $id_vision = intval($_GET['id']);

    if ($id_vision === 0) {
        $_SESSION['error'] = 'ID ไม่ถูกต้อง';
        header('Location: table_vision.php');
        exit();
    }

    try {
        // ดึงข้อมูลรูปภาพจากฐานข้อมูล
        $stmt = $pdo->prepare("SELECT img_vision FROM vision WHERE id_vision = :id_vision");
        $stmt->bindParam(':id_vision', $id_vision, PDO::PARAM_INT);
        $stmt->execute();
        $vision = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$vision) {
            $_SESSION['error'] = 'ไม่พบข้อมูลที่ต้องการลบ';
            header('Location: table_vision.php');
            exit();
        }

        // ลบโฟลเดอร์รูปภาพ
        $folderPath = '../../img/vision/' . $id_vision;
        if (is_dir($folderPath)) {
            $files = array_diff(scandir($folderPath), ['.', '..']);
            foreach ($files as $file) {
                unlink($folderPath . '/' . $file);
            }
            rmdir($folderPath);
        }

        // ลบข้อมูลจากฐานข้อมูล
        $sql = "DELETE FROM vision WHERE id_vision = :id_vision";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_vision', $id_vision, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'ลบข้อมูลและโฟลเดอร์รูปภาพเรียบร้อยแล้ว';
        } else {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการลบข้อมูล';
        }

        header('Location: ../table_vision.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'ข้อผิดพลาด: ' . $e->getMessage();
        header('Location: ../table_vision.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'วิธีการร้องขอไม่ถูกต้อง';
    header('Location: ../table_vision.php');
    exit();
}
