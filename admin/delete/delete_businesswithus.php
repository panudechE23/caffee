<?php
session_start();
require '../../db.php';

if (isset($_GET['id'])) {
    $id_businesswithus = intval($_GET['id']);

    if ($id_businesswithus === 0) {
        $_SESSION['error'] = 'ID ไม่ถูกต้อง';
        header('Location: ../table_businesswithus.php');
        exit();
    }

    try {
        // ดึงข้อมูลรูปภาพจากฐานข้อมูล
        $stmt = $pdo->prepare("SELECT img_businesswithus FROM businesswithus WHERE id_businesswithus = :id_businesswithus");
        $stmt->bindParam(':id_businesswithus', $id_businesswithus, PDO::PARAM_INT);
        $stmt->execute();
        $businesswithus = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$businesswithus) {
            $_SESSION['error'] = 'ไม่พบข้อมูลที่ต้องการลบ';
            header('Location: ../table_businesswithus.php');
            exit();
        }
    
        // ลบโฟลเดอร์รูปภาพ
        $folderPath = '../../img/businesswithus/' . $id_businesswithus;
        if (is_dir($folderPath)) {
            $files = array_diff(scandir($folderPath), ['.', '..']);
            foreach ($files as $file) {
                unlink($folderPath . '/' . $file);
            }
            rmdir($folderPath);
        }

        // ลบข้อมูลจากฐานข้อมูล
        $sql = "DELETE FROM businesswithus WHERE id_businesswithus = :id_businesswithus";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_businesswithus', $id_businesswithus, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'ลบข้อมูลและโฟลเดอร์รูปภาพเรียบร้อยแล้ว';
        } else {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการลบข้อมูล';
        }

        header('Location: ../table_businesswithus.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'ข้อผิดพลาด: ' . $e->getMessage();
        header('Location: ../table_businesswithus.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'วิธีการร้องขอไม่ถูกต้อง';
    header('Location: ../table_businesswithus.php');
    exit();
}
