<?php
session_start();
require '../../db.php'; // Adjusted the path to db.php

$id_history = 0;

// ตรวจสอบว่าเป็นคำร้องแบบ POST หรือ GET
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_history = isset($_POST['id_history']) ? intval($_POST['id_history']) : 0;
} elseif (isset($_GET['id'])) {
    $id_history = intval($_GET['id']);
}

if ($id_history === 0) {
    $_SESSION['error'] = 'ID ไม่ถูกต้อง';
    header('Location: ../table_history.php'); // Adjusted the path to table_history.php
    exit();
}

try {
    // ตรวจสอบว่ามีข้อมูลในฐานข้อมูลหรือไม่
    $checkSql = "SELECT COUNT(*) FROM history WHERE id_history = :id_history";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->bindParam(':id_history', $id_history, PDO::PARAM_INT);
    $checkStmt->execute();
    $recordExists = $checkStmt->fetchColumn();

    if ($recordExists) {
        // ลบข้อมูลจากฐานข้อมูล
        $sql = "DELETE FROM history WHERE id_history = :id_history";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_history', $id_history, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'ลบข้อมูลเรียบร้อยแล้ว';
        } else {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการลบข้อมูล';
        }
    } else {
        $_SESSION['error'] = 'ไม่พบข้อมูลที่ต้องการลบ';
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'ข้อผิดพลาด: ' . $e->getMessage();
}

// เปลี่ยนเส้นทางกลับไปยังหน้า table_history.php
header('Location: ../table_history.php'); // Adjusted the path to table_history.php
exit();
