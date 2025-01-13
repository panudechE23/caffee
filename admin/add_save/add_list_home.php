<?php
session_start();
include('../../db.php'); // เปลี่ยนเส้นทางของไฟล์ฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $text1_th = $_POST['text1_th'] ?? '';
    $text1_en = $_POST['text1_en'] ?? '';
    $text2_th = $_POST['text2_th'] ?? '';
    $text2_en = $_POST['text2_en'] ?? '';
    $vdo_home = $_POST['vdo_home'] ?? ''; // รับลิงก์วิดีโอ
    $image = $_FILES['image'] ?? null;

    // ตรวจสอบว่ามีข้อมูลครบถ้วน
    if (empty($text1_th) || empty($text1_en) || empty($text2_th) || empty($text2_en) || empty($vdo_home) || !$image) {
        $_SESSION['error_message'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
        header('Location: ../../adhome.php'); // เปลี่ยนเส้นทางของไฟล์ฟอร์ม
        exit();
    }

    try {
        // เพิ่มข้อมูลในฐานข้อมูลก่อน เพื่อให้ได้ id_home สำหรับสร้างโฟลเดอร์
        $stmt = $pdo->prepare("
            INSERT INTO home (text1_th_home, text1_en_home, text2_th_home, text2_en_home, img_home, vdo_home) 
            VALUES (:text1_th, :text1_en, :text2_th, :text2_en, '', :vdo_home)
        ");
        $stmt->bindParam(':text1_th', $text1_th);
        $stmt->bindParam(':text1_en', $text1_en);
        $stmt->bindParam(':text2_th', $text2_th);
        $stmt->bindParam(':text2_en', $text2_en);
        $stmt->bindParam(':vdo_home', $vdo_home); // บันทึกลิงก์วิดีโอ
        $stmt->execute();

        // ดึง ID ล่าสุด
        $id_home = $pdo->lastInsertId();

        // ตรวจสอบประเภทของไฟล์รูปภาพ
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $imageFileType = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

        if (!in_array($imageFileType, $allowedTypes)) {
            $_SESSION['error_message'] = "ประเภทไฟล์รูปภาพไม่ถูกต้อง (รองรับเฉพาะ jpg, jpeg, png, gif)";
            header('Location: ../../adhome.php');
            exit();
        }

        // ตั้งค่าชื่อและตำแหน่งการอัปโหลดไฟล์
        $uploadDir = "../../img/home/$id_home/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // สร้างโฟลเดอร์ถ้ายังไม่มี
        }

        $uniqueFileName = uniqid('home_', true) . '.' . $imageFileType;
        $uploadFile = $uploadDir . $uniqueFileName;

        // ย้ายไฟล์ไปยังโฟลเดอร์ที่กำหนด
        if (move_uploaded_file($image['tmp_name'], $uploadFile)) {
            // อัปเดตชื่อรูปในฐานข้อมูล
            $stmt = $pdo->prepare("UPDATE home SET img_home = :img WHERE id_home = :id_home");
            $stmt->bindParam(':img', $uniqueFileName);
            $stmt->bindParam(':id_home', $id_home, PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['success_message'] = "เพิ่มข้อมูลสำเร็จ!";
        } else {
            $_SESSION['error_message'] = "เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ";
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
    }

    header('Location: ../../adhome.php');
    exit();
}

?>
