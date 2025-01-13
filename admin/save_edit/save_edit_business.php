<?php
// เริ่มการเชื่อมต่อฐานข้อมูล
include '../../db.php';
session_start();

// ตรวจสอบการส่งข้อมูลจากฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ดึงข้อมูลจากฟอร์ม
    $id_business = $_POST['id_business'];
    $name_th_business = $_POST['name_th_business'];
    $name_en_business = $_POST['name_en_business'];
    $detail_th_business = $_POST['detail_th_business'];
    $detail_en_business = $_POST['detail_en_business'];

    $img_business = $_FILES['img_business'];

    // ดึงข้อมูลเก่าจากฐานข้อมูล
    $stmt = $pdo->prepare("SELECT * FROM business WHERE id_business = :id_business");
    $stmt->bindParam(':id_business', $id_business, PDO::PARAM_INT);
    $stmt->execute();
    $business = $stmt->fetch(PDO::FETCH_ASSOC);

    // เช็คหากมีการอัปโหลดรูปใหม่
    if ($img_business['error'] === UPLOAD_ERR_OK) {
        // ตรวจสอบประเภทของไฟล์ที่อนุญาตให้อัปโหลด
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = pathinfo($img_business['name'], PATHINFO_EXTENSION);
        
        if (!in_array($file_ext, $allowed)) {
            $_SESSION['error'] = "ประเภทไฟล์ไม่ถูกต้อง อนุญาตเฉพาะ: " . implode(', ', $allowed);
            header("Location: ../page_business.php?id_business=$id_business");
            exit();
        }

        // ลบรูปเก่าหากมีการอัปโหลดใหม่
        if (!empty($business['img_business']) && file_exists("../../img/business/" . $business['img_business'])) {
            unlink("../../img/business/" . $business['img_business']);
        }

        // ตั้งชื่อไฟล์ใหม่ที่ไม่ซ้ำกัน
        $img_name = uniqid() . "." . $file_ext;
        $img_path = "../../img/business/" . $img_name;

        // ตรวจสอบและสร้างโฟลเดอร์หากไม่มี
        if (!is_dir("../../img/business/")) {
            mkdir("../../img/business/", 0777, true);
        }

        // ย้ายไฟล์ไปยังโฟลเดอร์ที่กำหนด
        if (move_uploaded_file($img_business['tmp_name'], $img_path)) {
            $img_business_db = $img_name;
        } else {
            $_SESSION['error'] = "ไม่สามารถอัปโหลดรูปภาพได้";
            header("Location: ../page_business.php?id_business=$id_business");
            exit();
        }
    } else {
        // หากไม่มีการอัปโหลดรูปใหม่ ใช้รูปเดิม
        $img_business_db = $business['img_business'];
    }

    // เตรียมคำสั่ง SQL เพื่ออัปเดตข้อมูลในฐานข้อมูล
    $stmt = $pdo->prepare("UPDATE business SET
        name_th_business = :name_th_business,
        name_en_business = :name_en_business,
        detail_th_business = :detail_th_business,
        detail_en_business = :detail_en_business,
        img_business = :img_business
        WHERE id_business = :id_business");

    // ผูกค่าตัวแปรกับคำสั่ง SQL
    $stmt->bindParam(':name_th_business', $name_th_business);
    $stmt->bindParam(':name_en_business', $name_en_business);
    $stmt->bindParam(':detail_th_business', $detail_th_business);
    $stmt->bindParam(':detail_en_business', $detail_en_business);
    $stmt->bindParam(':img_business', $img_business_db);
    $stmt->bindParam(':id_business', $id_business, PDO::PARAM_INT);

    // อัปเดตข้อมูลในฐานข้อมูล
    if ($stmt->execute()) {
        $_SESSION['success'] = "ข้อมูลการติดต่อได้ถูกอัปเดตสำเร็จ";
    } else {
        $_SESSION['error'] = "เกิดข้อผิดพลาดในการอัปเดตข้อมูล";
    }

    // เปลี่ยนเส้นทางไปยังหน้าที่เกี่ยวข้อง
    header("Location: ../page_business.php");
    exit();
}
?>
