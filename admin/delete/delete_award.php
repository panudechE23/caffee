<?php
session_start();
require '../../db.php';

if (isset($_GET['id'])) {
    $id_award = intval($_GET['id']);

    if ($id_award === 0) {
        $_SESSION['error'] = 'ID ไม่ถูกต้อง';
        header('Location: ../table_award.php');
        exit();
    }

    try {
        // ดึงข้อมูลรูปภาพจากฐานข้อมูล
        $stmt = $pdo->prepare("SELECT img_award FROM award WHERE id_award = :id_award");
        $stmt->bindParam(':id_award', $id_award, PDO::PARAM_INT);
        $stmt->execute();
        $award = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$award) {
            $_SESSION['error'] = 'ไม่พบข้อมูลที่ต้องการลบ';
            header('Location: table_award.php');
            exit();
        }

        // ลบโฟลเดอร์รูปภาพ
        $folderPath = '../../img/award/' . $id_award;
        if (is_dir($folderPath)) {
            $files = array_diff(scandir($folderPath), ['.', '..']);
            foreach ($files as $file) {
                unlink($folderPath . '/' . $file);
            }
            rmdir($folderPath);
        }

        // ลบข้อมูลจากฐานข้อมูล
        $sql = "DELETE FROM award WHERE id_award = :id_award";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_award', $id_award, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'ลบข้อมูลและโฟลเดอร์รูปภาพเรียบร้อยแล้ว';
        } else {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการลบข้อมูล';
        }

        header('Location: ../table_award.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'ข้อผิดพลาด: ' . $e->getMessage();
        header('Location: ../table_award.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'วิธีการร้องขอไม่ถูกต้อง';
    header('Location: ../table_award.php');
    exit();
}
