<?php
session_start();
require '../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // รับค่าจากฟอร์ม
        $name_th_executives = trim($_POST['name_th_executives']);
        $position_th_executives = trim($_POST['position_th_executives']);
        $name_en_executives = trim($_POST['name_en_executives']);
        $position_en_executives = trim($_POST['position_en_executives']);

        // ตรวจสอบว่ามีไฟล์อัปโหลดหรือไม่
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'กรุณาอัปโหลดรูปภาพ';
            header('Location: ../table_executives.php');
            exit();
        }

        // อัปโหลดไฟล์รูป
        $image = $_FILES['image'];
        $uploadDir = '../../img/executives/';

        // เตรียมคำสั่ง SQL สำหรับเพิ่มข้อมูล
        $sql = "INSERT INTO executives (name_th_executive, position_th_executive, name_en_executive, position_en_executive, img_executive)
                VALUES (:name_th, :position_th, :name_en, :position_en, '')";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name_th', $name_th_executives);
        $stmt->bindParam(':position_th', $position_th_executives);
        $stmt->bindParam(':name_en', $name_en_executives);
        $stmt->bindParam(':position_en', $position_en_executives);

        if ($stmt->execute()) {
            $id_executive = $pdo->lastInsertId();

            // สร้างโฟลเดอร์สำหรับจัดเก็บรูปภาพ
            $targetDir = $uploadDir . $id_executive . '/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $imagePath = $targetDir . basename($image['name']);
            if (move_uploaded_file($image['tmp_name'], $imagePath)) {
                // อัปเดตชื่อไฟล์ในฐานข้อมูล
                $updateSql = "UPDATE executives SET img_executive = :img_executive WHERE id_executive = :id_executive";
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->bindParam(':img_executive', $image['name']);
                $updateStmt->bindParam(':id_executive', $id_executive);
                $updateStmt->execute();

                $_SESSION['success'] = 'เพิ่มข้อมูลและอัปโหลดรูปภาพเรียบร้อยแล้ว';
            } else {
                $_SESSION['error'] = 'ไม่สามารถอัปโหลดรูปภาพได้';
            }
        } else {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล';
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
