<?php
session_start();
require '../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_home = $_POST['id_home'] ?? null;
    $text1_th = $_POST['text1_th'] ?? '';
    $text1_en = $_POST['text1_en'] ?? '';
    $text2_th = $_POST['text2_th'] ?? '';
    $text2_en = $_POST['text2_en'] ?? '';
    $vdo_home = $_POST['vdo_home'] ?? ''; // เพิ่มการรับข้อมูล vdo_home
    $image = $_FILES['image'] ?? null;

    if (!$id_home) {
        $_SESSION['error'] = "ID ไม่ถูกต้อง หรือไม่ได้ส่งข้อมูล ID มา";
        header("Location:../add_from/add_home.php");
        exit;
    }

    try {
        // ดึงข้อมูลเดิมจากฐานข้อมูล
        $stmt = $pdo->prepare("SELECT * FROM home WHERE id_home = :id_home");
        $stmt->bindParam(':id_home', $id_home, PDO::PARAM_INT);
        $stmt->execute();
        $home = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$home) {
            $_SESSION['error'] = "ไม่พบข้อมูลที่ตรงกับ ID: $id_home";
            header("Location:../add_from/add_home.php");
            exit;
        }

        $currentImage = $home['img_home'];

        // อัปโหลดรูปภาพใหม่ถ้ามี
        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            $uploadDir = "../../img/home/$id_home/";

            // สร้างโฟลเดอร์หากยังไม่มี
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imageName = basename($image['name']);
            $imagePath = $uploadDir . $imageName;

            // ลบรูปเก่าถ้ามีและชื่อไฟล์ใหม่ไม่ซ้ำ
            if ($currentImage && $currentImage !== $imageName) {
                $oldImagePath = $uploadDir . $currentImage;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // อัปโหลดไฟล์ใหม่
            if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
                $_SESSION['error'] = "ไม่สามารถอัปโหลดรูปภาพได้";
                header("Location: ../edit/edit_list_home.php?id=$id_home");
                exit;
            }
        } else {
            $imageName = $currentImage; // ใช้รูปภาพเดิม
        }

        // อัปเดตข้อมูลในฐานข้อมูล
        $stmt = $pdo->prepare("
            UPDATE home 
            SET text1_th_home = :text1_th, 
                text1_en_home = :text1_en, 
                text2_th_home = :text2_th, 
                text2_en_home = :text2_en, 
                vdo_home = :vdo_home, 
                img_home = :img_home 
            WHERE id_home = :id_home
        ");
        $stmt->bindParam(':text1_th', $text1_th, PDO::PARAM_STR);
        $stmt->bindParam(':text1_en', $text1_en, PDO::PARAM_STR);
        $stmt->bindParam(':text2_th', $text2_th, PDO::PARAM_STR);
        $stmt->bindParam(':text2_en', $text2_en, PDO::PARAM_STR);
        $stmt->bindParam(':vdo_home', $vdo_home, PDO::PARAM_STR); // เพิ่มการ bind ฟิลด์ vdo_home
        $stmt->bindParam(':img_home', $imageName, PDO::PARAM_STR);
        $stmt->bindParam(':id_home', $id_home, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success'] = "แก้ไขข้อมูลสำเร็จ";
        } else {
            $_SESSION['error'] = "ไม่สามารถแก้ไขข้อมูลได้";
        }

    } catch (PDOException $e) {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
    }

    header("Location: ../add_from/add_home.php");
    exit;
} else {
    $_SESSION['error'] = "คำขอไม่ถูกต้อง";
    header("Location: ../add_from/add_home.php");
    exit;
}
?>
