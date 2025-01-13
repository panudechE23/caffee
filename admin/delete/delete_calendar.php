<?php
// เริ่มการเชื่อมต่อฐานข้อมูล
include '../../db.php';
session_start();

// ตรวจสอบว่ามีการส่ง ID มาหรือไม่
if (isset($_GET['id'])) {
    $id_calendar = $_GET['id'];

    try {
        // สร้างคำสั่ง SQL สำหรับลบข้อมูล
        $sql = "DELETE FROM calendar WHERE id_calendar = :id_calendar";
        $stmt = $pdo->prepare($sql);

        // ดำเนินการลบ
        $stmt->execute([':id_calendar' => $id_calendar]);

        // ตรวจสอบว่าลบสำเร็จหรือไม่
        if ($stmt->rowCount() > 0) {
            $_SESSION['success'] = 'ลบกิจกรรมสำเร็จ!';
        } else {
            $_SESSION['error'] = 'ไม่พบข้อมูลที่ต้องการลบ!';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
    }
} else {
    $_SESSION['error'] = 'ไม่มี ID ที่ระบุ!';
}

// กลับไปยังหน้าแสดงรายการ
header('Location: ../table_calendar.php');
exit();
?>
