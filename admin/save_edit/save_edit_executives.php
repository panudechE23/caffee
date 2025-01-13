<?php
session_start();
require '../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // รับค่าจากฟอร์ม
        $id_executive = intval($_POST['id']);
        $name_th_executive = trim($_POST['name_th_executive']);
        $position_th_executive = trim($_POST['position_th_executive']);
        $name_en_executive = trim($_POST['name_en_executive']);
        $position_en_executive = trim($_POST['position_en_executive']);

        // ตรวจสอบว่ามี ID หรือไม่
        if ($id_executive === 0) {
            $_SESSION['error'] = 'ID ไม่ถูกต้อง';
            header('Location: ../table_executives.php');
            exit();
        }

        // ตรวจสอบไฟล์รูปภาพ
        $image = isset($_FILES['image']) ? $_FILES['image'] : null;
        $imagePath = '';

        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../img/executives/' . $id_executive . '/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imagePath = $uploadDir . basename($image['name']);
            if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
                $_SESSION['error'] = 'ไม่สามารถอัปโหลดรูปภาพได้';
                header('Location: edit_list_executives.php?id=' . $id_executive);
                exit();
            }
            $imagePath = basename($image['name']);
        }

        // อัปเดตข้อมูลในฐานข้อมูล
        $sql = "UPDATE executives 
                SET name_th_executive = :name_th, 
                    position_th_executive = :position_th, 
                    name_en_executive = :name_en, 
                    position_en_executive = :position_en" .
                ($imagePath ? ", img_executive = :img_executive" : "") .
                " WHERE id_executive = :id_executive";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name_th', $name_th_executive, PDO::PARAM_STR);
        $stmt->bindParam(':position_th', $position_th_executive, PDO::PARAM_STR);
        $stmt->bindParam(':name_en', $name_en_executive, PDO::PARAM_STR);
        $stmt->bindParam(':position_en', $position_en_executive, PDO::PARAM_STR);
        $stmt->bindParam(':id_executive', $id_executive, PDO::PARAM_INT);
        if ($imagePath) {
            $stmt->bindParam(':img_executive', $imagePath, PDO::PARAM_STR);
        }

        if ($stmt->execute()) {
            $_SESSION['success'] = 'แก้ไขข้อมูลเรียบร้อยแล้ว';
        } else {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล';
        }

        header('Location: ../table_executives.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'ข้อผิดพลาด: ' . $e->getMessage();
        header('Location: ../table_executives.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'วิธีการร้องขอไม่ถูกต้อง';
    header('Location: ../table_executives.php');
    exit();
}
