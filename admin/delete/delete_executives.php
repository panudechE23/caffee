<?php
session_start();
require '../../db.php';

if (isset($_GET['id'])) {
    $id_executive = intval($_GET['id']);

    if ($id_executive === 0) {
        $_SESSION['error'] = 'ID ไม่ถูกต้อง';
        header('Location: ../table_executives.php');
        exit();
    }

    try {
        // ดึงข้อมูลรูปภาพจากฐานข้อมูล
        $stmt = $pdo->prepare("SELECT img_executive FROM executives WHERE id_executive = :id_executive");
        $stmt->bindParam(':id_executive', $id_executive, PDO::PARAM_INT);
        $stmt->execute();
        $executive = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$executive) {
            $_SESSION['error'] = 'ไม่พบข้อมูลที่ต้องการลบ';
            header('Location: ../table_executives.php');
            exit();
        }

        // ลบโฟลเดอร์รูปภาพ
        $folderPath = '../../img/executives/' . $id_executive;
        if (is_dir($folderPath)) {
            $files = array_diff(scandir($folderPath), ['.', '..']);
            foreach ($files as $file) {
                unlink($folderPath . '/' . $file);
            }
            rmdir($folderPath);
        }

        // ลบข้อมูลจากฐานข้อมูล
        $sql = "DELETE FROM executives WHERE id_executive = :id_executive";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_executive', $id_executive, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'ลบข้อมูลและโฟลเดอร์รูปภาพเรียบร้อยแล้ว';
        } else {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการลบข้อมูล';
        }

        header('Location: ../table_executives.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'ข้อผิดพลาด: ' . $e->getMessage();
        header('Location: ../table_executives.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'วิธีการร้องขอไม่ถูกต้อง';
    header('Location: ../table_executives.php');
    exit();
}
