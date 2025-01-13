<?php
session_start();
require '../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // ตรวจสอบไฟล์รูปภาพ
        $image = isset($_FILES['img_lectureguide']) ? $_FILES['img_lectureguide'] : null;

        if (!$image || $image['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'กรุณาอัปโหลดรูปภาพ';
            header('Location: ../table_lectureguide.php');
            exit();
        }

        // เตรียมคำสั่ง SQL สำหรับเพิ่มข้อมูล
        $sql = "INSERT INTO lectureguide (img_lectureguide) VALUES ('')";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute()) {
            $id_lectureguide = $pdo->lastInsertId();

            // สร้างโฟลเดอร์สำหรับจัดเก็บรูปภาพ
            $uploadDir = '../../img/lectureguide/' . $id_lectureguide . '/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imagePath = $uploadDir . basename($image['name']);
            if (move_uploaded_file($image['tmp_name'], $imagePath)) {
                // อัปเดตชื่อไฟล์ในฐานข้อมูล
                $updateSql = "UPDATE lectureguide SET img_lectureguide = :img_lectureguide WHERE id_lectureguide = :id_lectureguide";
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->bindParam(':img_lectureguide', $image['name'], PDO::PARAM_STR);
                $updateStmt->bindParam(':id_lectureguide', $id_lectureguide, PDO::PARAM_INT);
                $updateStmt->execute();

                $_SESSION['success'] = 'เพิ่มข้อมูลและอัปโหลดรูปภาพเรียบร้อยแล้ว';
            } else {
                $_SESSION['error'] = 'ไม่สามารถอัปโหลดรูปภาพได้';
            }
        } else {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล';
        }

        header('Location: ../table_lectureguide.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'ข้อผิดพลาด: ' . $e->getMessage();
        header('Location: ../table_lectureguide.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'วิธีการร้องขอไม่ถูกต้อง';
    header('Location: ../table_lectureguide.php');
    exit();
}
