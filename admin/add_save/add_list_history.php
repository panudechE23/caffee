<?php
// เริ่มต้น session
session_start();

// เชื่อมต่อฐานข้อมูล
require '../../db.php'; // db.php ใช้ PDO เชื่อมต่อ

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม
    $name_th_history = isset($_POST['name_th_history']) ? trim($_POST['name_th_history']) : '';
    $name_en_history = isset($_POST['name_en_history']) ? trim($_POST['name_en_history']) : '';
    $detail_th_history = isset($_POST['detail_th_history']) ? trim($_POST['detail_th_history']) : '';
    $detail_en_history = isset($_POST['detail_en_history']) ? trim($_POST['detail_en_history']) : '';
    $start_date_history = isset($_POST['start_date_history']) ? trim($_POST['start_date_history']) : '';

    // ตรวจสอบข้อมูล
    if (empty($name_th_history) || empty($name_en_history) || empty($detail_th_history) || empty($detail_en_history) || empty($start_date_history)) {
        $_SESSION['error'] = 'กรุณากรอกข้อมูลให้ครบถ้วน';
        header('Location: ../add_from/add_history.php');
        exit();
    }

    // แปลงวันที่ให้เข้ากับรูปแบบฐานข้อมูล
    $start_date_history = date('Y-m-d', strtotime($start_date_history));

    try {
        // เตรียมคำสั่ง SQL
        $sql = "INSERT INTO history (name_th_history, name_en_history, detail_th_history, detail_en_history, date_history)
                VALUES (:name_th, :name_en, :detail_th, :detail_en, :date_history)";
        $stmt = $pdo->prepare($sql);

        // ผูกค่าพารามิเตอร์
        $stmt->bindParam(':name_th', $name_th_history, PDO::PARAM_STR);
        $stmt->bindParam(':name_en', $name_en_history, PDO::PARAM_STR);
        $stmt->bindParam(':detail_th', $detail_th_history, PDO::PARAM_STR);
        $stmt->bindParam(':detail_en', $detail_en_history, PDO::PARAM_STR);
        $stmt->bindParam(':date_history', $start_date_history, PDO::PARAM_STR);

        // รันคำสั่ง SQL
        if ($stmt->execute()) {
            $_SESSION['success'] = 'เพิ่มข้อมูลเรียบร้อยแล้ว';
        } else {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล';
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'ข้อผิดพลาด: ' . $e->getMessage();
    }

    // เปลี่ยนเส้นทางกลับไปยังหน้า table_history.php
    header('Location: ../table_history.php');
    exit();
} else {
    $_SESSION['error'] = 'วิธีการร้องขอไม่ถูกต้อง';
    header('Location: ../table_history.php');
    exit();
}
