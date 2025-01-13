<?php
session_start();
require '../../db.php';

if (isset($_GET['id'])) {
    $id_rules = intval($_GET['id']);

    if ($id_rules === 0) {
        $_SESSION['error'] = 'ID ไม่ถูกต้อง';
        header('Location: table_rules.php');
        exit();
    }

    try {
        // ดึงข้อมูลรูปภาพจากฐานข้อมูล
        $stmt = $pdo->prepare("SELECT img_rules FROM rules WHERE id_rules = :id_rules");
        $stmt->bindParam(':id_rules', $id_rules, PDO::PARAM_INT);
        $stmt->execute();
        $rules = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$rules) {
            $_SESSION['error'] = 'ไม่พบข้อมูลที่ต้องการลบ';
            header('Location: table_rules.php');
            exit();
        }

        // ลบโฟลเดอร์รูปภาพ
        $folderPath = '../../img/rules/' . $id_rules;
        if (is_dir($folderPath)) {
            $files = array_diff(scandir($folderPath), ['.', '..']);
            foreach ($files as $file) {
                unlink($folderPath . '/' . $file);
            }
            rmdir($folderPath);
        }

        // ลบข้อมูลจากฐานข้อมูล
        $sql = "DELETE FROM rules WHERE id_rules = :id_rules";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_rules', $id_rules, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'ลบข้อมูลและโฟลเดอร์รูปภาพเรียบร้อยแล้ว';
        } else {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการลบข้อมูล';
        }

        header('Location: ../table_rules.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'ข้อผิดพลาด: ' . $e->getMessage();
        header('Location: ../table_rules.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'วิธีการร้องขอไม่ถูกต้อง';
    header('Location: ../table_rules.php');
    exit();
}
