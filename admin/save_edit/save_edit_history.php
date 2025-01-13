<?php
session_start();
require '../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $id_history = isset($_POST['id_history']) ? intval($_POST['id_history']) : 0;
    $name_th_history = isset($_POST['name_th_history']) ? trim($_POST['name_th_history']) : '';
    $name_en_history = isset($_POST['name_en_history']) ? trim($_POST['name_en_history']) : '';
    $detail_th_history = isset($_POST['detail_th_history']) ? trim($_POST['detail_th_history']) : '';
    $detail_en_history = isset($_POST['detail_en_history']) ? trim($_POST['detail_en_history']) : '';
    $date_history = isset($_POST['date_history']) ? trim($_POST['date_history']) : '';

    // ตรวจสอบข้อมูล
    if (empty($id_history) || empty($name_th_history) || empty($name_en_history) || empty($detail_th_history) || empty($detail_en_history) || empty($date_history)) {
        $_SESSION['error'] = 'กรุณากรอกข้อมูลให้ครบถ้วน';
        header('Location: ../edit/edit_list_history.php?id=' . $id_history);
        exit();
    }

    // แปลงวันที่ให้เข้ากับรูปแบบฐานข้อมูล
    $date_history = date('Y-m-d', strtotime($date_history));

    try {
        // อัปเดตข้อมูลในฐานข้อมูล
        $sql = "UPDATE history 
                SET name_th_history = :name_th, name_en_history = :name_en, 
                    detail_th_history = :detail_th, detail_en_history = :detail_en, 
                    date_history = :date_history
                WHERE id_history = :id_history";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name_th', $name_th_history, PDO::PARAM_STR);
        $stmt->bindParam(':name_en', $name_en_history, PDO::PARAM_STR);
        $stmt->bindParam(':detail_th', $detail_th_history, PDO::PARAM_STR);
        $stmt->bindParam(':detail_en', $detail_en_history, PDO::PARAM_STR);
        $stmt->bindParam(':date_history', $date_history, PDO::PARAM_STR);
        $stmt->bindParam(':id_history', $id_history, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'อัปเดตข้อมูลเรียบร้อยแล้ว';
            header('Location: ../table_history.php');
            exit();
        } else {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล';
            header('Location: ../edit/edit_list_history.php?id=' . $id_history);
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'ข้อผิดพลาด: ' . $e->getMessage();
        header('Location: ../edit/edit_list_history.php?id=' . $id_history);
        exit();
    }
} else {
    $_SESSION['error'] = 'วิธีการร้องขอไม่ถูกต้อง';
    header('Location: ../table_history.php');
    exit();
}
