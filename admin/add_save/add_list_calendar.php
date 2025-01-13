<?php
// add_list_calendar.php

// เริ่มการเชื่อมต่อฐานข้อมูล
include '../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $name_th_calendar = $_POST['name_th_calendar'] ?? '';
    $name_en_calendar = $_POST['name_en_calendar'] ?? '';
    $date_calendar = $_POST['date_calendar'] ?? '';
    $start_time_calendar = $_POST['start_time_calendar'] ?? '';
    $end_time_calendar = $_POST['end_time_calendar'] ?? '';
    $speaker_th_calendar = $_POST['speaker_th_calendar'] ?? '';
    $speaker_en_calendar = $_POST['speaker_en_calendar'] ?? '';
    $location_th_calendar = $_POST['location_th_calendar'] ?? '';
    $location_en_calendar = $_POST['location_en_calendar'] ?? '';
    $notes_th_calendar = $_POST['notes_th_calendar'] ?? '';
    $notes_en_calendar = $_POST['notes_en_calendar'] ?? '';

    // ตรวจสอบข้อมูลที่จำเป็น
    if (empty($name_th_calendar) || empty($date_calendar) || empty($start_time_calendar) || empty($end_time_calendar) || empty($location_th_calendar)) {
        $_SESSION['error'] = 'กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน!';
        header('Location: ../form_calendar.php');
        exit;
    }

    // แปลงวันที่ให้อยู่ในรูปแบบฐานข้อมูล (YYYY-MM-DD)
    $date_calendar = DateTime::createFromFormat('d/m/Y', $date_calendar)->format('Y-m-d');

    try {
        // สร้างคำสั่ง SQL
        $sql = "INSERT INTO calendar (
                    name_th_calendar, 
                    name_en_calendar, 
                    date_calendar, 
                    start_time_calendar, 
                    end_time_calendar, 
                    speaker_th_calendar, 
                    speaker_en_calendar, 
                    location_th_calendar, 
                    location_en_calendar, 
                    notes_th_calendar, 
                    notes_en_calendar
                ) VALUES (
                    :name_th_calendar, 
                    :name_en_calendar, 
                    :date_calendar, 
                    :start_time_calendar, 
                    :end_time_calendar, 
                    :speaker_th_calendar, 
                    :speaker_en_calendar, 
                    :location_th_calendar, 
                    :location_en_calendar, 
                    :notes_th_calendar, 
                    :notes_en_calendar
                )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name_th_calendar' => $name_th_calendar,
            ':name_en_calendar' => $name_en_calendar,
            ':date_calendar' => $date_calendar,
            ':start_time_calendar' => $start_time_calendar,
            ':end_time_calendar' => $end_time_calendar,
            ':speaker_th_calendar' => $speaker_th_calendar,
            ':speaker_en_calendar' => $speaker_en_calendar,
            ':location_th_calendar' => $location_th_calendar,
            ':location_en_calendar' => $location_en_calendar,
            ':notes_th_calendar' => $notes_th_calendar,
            ':notes_en_calendar' => $notes_en_calendar
        ]);

        $_SESSION['success'] = 'เพิ่มกิจกรรมสำเร็จ!';
        header('Location: ../table_calendar.php');
    } catch (Exception $e) {
        $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
        header('Location: ../form_calendar.php');
    }
} else {
    $_SESSION['error'] = 'ไม่รองรับการเข้าถึงแบบนี้!';
    header('Location: ../form_calendar.php');
}
?>
