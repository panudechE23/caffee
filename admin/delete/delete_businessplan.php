<?php
session_start();
require '../../db.php';

if (isset($_GET['id'])) {
    $id_businessplan = intval($_GET['id']);

    if ($id_businessplan === 0) {
        $_SESSION['error'] = 'ID ไม่ถูกต้อง';
        header('Location: ../table_businessplan.php');
        exit();
    }

    try {
        // ดึงข้อมูลรูปภาพจากฐานข้อมูล
        $stmt = $pdo->prepare("SELECT img_businessplan FROM businessplan WHERE id_businessplan = :id_businessplan");
        $stmt->bindParam(':id_businessplan', $id_businessplan, PDO::PARAM_INT);
        $stmt->execute();
        $businessplan = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$businessplan) {
            $_SESSION['error'] = 'ไม่พบข้อมูลที่ต้องการลบ';
            header('Location: ../table_businessplan.php');
            exit();
        }

        // ลบโฟลเดอร์รูปภาพ
        $folderPath = '../../img/businessplan/' . $id_businessplan;
        if (is_dir($folderPath)) {
            $files = array_diff(scandir($folderPath), ['.', '..']);
            foreach ($files as $file) {
                unlink($folderPath . '/' . $file);
            }
            rmdir($folderPath);
        }

        // ลบข้อมูลจากฐานข้อมูล
        $sql = "DELETE FROM businessplan WHERE id_businessplan = :id_businessplan";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_businessplan', $id_businessplan, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'ลบข้อมูลและโฟลเดอร์รูปภาพเรียบร้อยแล้ว';
        } else {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการลบข้อมูล';
        }

        header('Location: ../table_businessplan.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'ข้อผิดพลาด: ' . $e->getMessage();
        header('Location: ../table_businessplan.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'วิธีการร้องขอไม่ถูกต้อง';
    header('Location: ../table_businessplan.php');
    exit();
}
