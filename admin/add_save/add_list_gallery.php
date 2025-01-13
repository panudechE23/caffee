<?php
session_start();
require '../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // ตรวจสอบไฟล์รูปภาพ
        $image = isset($_FILES['img_gallery']) ? $_FILES['img_gallery'] : null;

        if (!$image || $image['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'กรุณาอัปโหลดรูปภาพ';
            header('Location: ../table_gallery.php');
            exit();
        }

        // เตรียมคำสั่ง SQL สำหรับเพิ่มข้อมูล
        $sql = "INSERT INTO gallery (img_gallery) VALUES ('')";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute()) {
            $id_gallery = $pdo->lastInsertId();

            // สร้างโฟลเดอร์สำหรับจัดเก็บรูปภาพ
            $uploadDir = '../../img/gallery/' . $id_gallery . '/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imagePath = $uploadDir . basename($image['name']);
            if (move_uploaded_file($image['tmp_name'], $imagePath)) {
                // อัปเดตชื่อไฟล์ในฐานข้อมูล
                $updateSql = "UPDATE gallery SET img_gallery = :img_gallery WHERE id_gallery = :id_gallery";
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->bindParam(':img_gallery', $image['name'], PDO::PARAM_STR);
                $updateStmt->bindParam(':id_gallery', $id_gallery, PDO::PARAM_INT);
                $updateStmt->execute();

                $_SESSION['success'] = 'เพิ่มข้อมูลและอัปโหลดรูปภาพเรียบร้อยแล้ว';
            } else {
                $_SESSION['error'] = 'ไม่สามารถอัปโหลดรูปภาพได้';
            }
        } else {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล';
        }

        header('Location: ../table_gallery.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'ข้อผิดพลาด: ' . $e->getMessage();
        header('Location: ../table_gallery.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'วิธีการร้องขอไม่ถูกต้อง';
    header('Location: ../table_gallery.php');
    exit();
}
