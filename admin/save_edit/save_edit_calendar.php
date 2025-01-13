<?php
// เริ่มการเชื่อมต่อฐานข้อมูล
include '../../db.php';
session_start();

// ตรวจสอบว่ามีการส่งข้อมูลแบบ POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // รับค่าจากฟอร์ม
        $id_calendar = $_POST['id_calendar'] ?? null;
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

        // ตรวจสอบ ID กิจกรรม
        if (!$id_calendar) {
            $_SESSION['error'] = 'ไม่พบ ID ที่ต้องการแก้ไข!';
            header('Location: ../table_calendar.php');
            exit();
        }

        // ตรวจสอบและแปลงรูปแบบวันที่
        if (!empty($date_calendar)) {
            $date_object = DateTime::createFromFormat('d/m/Y', $date_calendar);
            if ($date_object === false) {
                $_SESSION['error'] = 'รูปแบบวันที่ไม่ถูกต้อง! กรุณากรอกวันที่ในรูปแบบ วว/ดด/ปปปป';
                header("Location: ../edit/edit_list_calendar.php?id=$id_calendar");
                exit();
            }
            $date_calendar = $date_object->format('Y-m-d');
        }

        // สร้างคำสั่ง SQL สำหรับอัปเดตข้อมูล
        $sql = "UPDATE calendar SET
                    name_th_calendar = :name_th_calendar,
                    name_en_calendar = :name_en_calendar,
                    date_calendar = :date_calendar,
                    start_time_calendar = :start_time_calendar,
                    end_time_calendar = :end_time_calendar,
                    speaker_th_calendar = :speaker_th_calendar,
                    speaker_en_calendar = :speaker_en_calendar,
                    location_th_calendar = :location_th_calendar,
                    location_en_calendar = :location_en_calendar,
                    notes_th_calendar = :notes_th_calendar,
                    notes_en_calendar = :notes_en_calendar
                WHERE id_calendar = :id_calendar";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_calendar' => $id_calendar,
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

        // แสดงข้อความสำเร็จและเปลี่ยนหน้า
        $_SESSION['success'] = 'แก้ไขกิจกรรมสำเร็จ!';
        header('Location: ../table_calendar.php');
    } catch (Exception $e) {
        // จัดการข้อผิดพลาดและแสดงข้อความ
        $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
        header("Location: ../edit/edit_list_calendar.php?id=$id_calendar");
    }
} else {
    // กรณีเข้าถึงไฟล์นี้โดยไม่ใช่ POST
    $_SESSION['error'] = 'ไม่รองรับการเข้าถึงแบบนี้!';
    header('Location: ../table_calendar.php');
}
?>
