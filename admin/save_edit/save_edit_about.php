<?php
// เริ่มการเชื่อมต่อฐานข้อมูล
include '../../db.php';
session_start();

// ตรวจสอบการส่งข้อมูลจากฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ดึงข้อมูลจากฟอร์ม
    $id_about = $_POST['id_about'];
    $name_th_about = $_POST['name_th_about'];
    $name_en_about = $_POST['name_en_about'];
    $detail_th_about = $_POST['detail_th_about'];
    $detail_en_about = $_POST['detail_en_about'];
    $vdo_about = $_POST['vdo_about'];
    $img_about = $_FILES['img_about'];

    // ดึงข้อมูลเก่าจากฐานข้อมูล
    $stmt = $pdo->prepare("SELECT * FROM about WHERE id_about = :id_about");
    $stmt->bindParam(':id_about', $id_about, PDO::PARAM_INT);
    $stmt->execute();
    $about = $stmt->fetch(PDO::FETCH_ASSOC);

    // เช็คหากมีการอัปโหลดรูปใหม่
    if ($img_about['error'] === UPLOAD_ERR_OK) {
        // ลบรูปเก่าหากมีการอัปโหลดใหม่
        if (!empty($about['img_about']) && file_exists("../../img/about/" . $about['img_about'])) {
            unlink("../../img/about/" . $about['img_about']);
        }

        // บันทึกรูปใหม่
        $img_name = basename($img_about['name']);
        $img_path = "../../img/about/" . $img_name;

        // ย้ายไฟล์ไปยังโฟลเดอร์ที่กำหนด
        if (move_uploaded_file($img_about['tmp_name'], $img_path)) {
            $img_about_db = $img_name;
        } else {
            $_SESSION['error'] = "ไม่สามารถอัปโหลดรูปภาพได้";
            header("Location: edit_about.php?id_about=$id_about");
            exit();
        }
    } else {
        // หากไม่มีการอัปโหลดรูปใหม่ ใช้รูปเดิม
        $img_about_db = $about['img_about'];
    }

    // เตรียมคำสั่ง SQL เพื่ออัปเดตข้อมูลในฐานข้อมูล
    $stmt = $pdo->prepare("UPDATE about SET
        name_th_about = :name_th_about,
        name_en_about = :name_en_about,
        detail_th_about = :detail_th_about,
        detail_en_about = :detail_en_about,
        vdo_about = :vdo_about,
        img_about = :img_about
        WHERE id_about = :id_about");

    // ผูกค่าตัวแปรกับคำสั่ง SQL
    $stmt->bindParam(':name_th_about', $name_th_about);
    $stmt->bindParam(':name_en_about', $name_en_about);
    $stmt->bindParam(':detail_th_about', $detail_th_about);
    $stmt->bindParam(':detail_en_about', $detail_en_about);
    $stmt->bindParam(':vdo_about', $vdo_about);
    $stmt->bindParam(':img_about', $img_about_db);
    $stmt->bindParam(':id_about', $id_about, PDO::PARAM_INT);

    // อัปเดตข้อมูลในฐานข้อมูล
    if ($stmt->execute()) {
        $_SESSION['success'] = "ข้อมูลการติดต่อได้ถูกอัปเดตสำเร็จ";
    } else {
        $_SESSION['error'] = "เกิดข้อผิดพลาดในการอัปเดตข้อมูล";
    }

    // เปลี่ยนเส้นทางไปยังหน้าที่เกี่ยวข้อง
    header("Location: ../page_about.php");
    exit();
}
?>
