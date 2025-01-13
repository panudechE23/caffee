<?php
require '../../db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // รับค่าจากฟอร์ม
        $name_th_news = trim($_POST['name_th_news']);
        $name_en_news = trim($_POST['name_en_news']);
        $detail_th_news = trim($_POST['detail_th_news']);
        $detail_en_news = trim($_POST['detail_en_news']);

        // เพิ่มข้อมูลเบื้องต้นในฐานข้อมูลเพื่อสร้าง ID
        $sql = "INSERT INTO news (name_th_news, name_en_news, detail_th_news, detail_en_news) VALUES (:name_th, :name_en, :detail_th, :detail_en)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name_th', $name_th_news, PDO::PARAM_STR);
        $stmt->bindParam(':name_en', $name_en_news, PDO::PARAM_STR);
        $stmt->bindParam(':detail_th', $detail_th_news, PDO::PARAM_STR);
        $stmt->bindParam(':detail_en', $detail_en_news, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $id_news = $pdo->lastInsertId();

            // สร้างโฟลเดอร์สำหรับเก็บรูปภาพ
            $uploadDir = '../../img/news/' . $id_news . '/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // อัปโหลดรูปภาพ
            $imgPaths = [];
            foreach (['img_1_news', 'img_2_news'] as $imgField) {
                $img = $_FILES[$imgField] ?? null;
                if ($img && $img['error'] === UPLOAD_ERR_OK) {
                    $imgPath = $uploadDir . basename($img['name']);
                    move_uploaded_file($img['tmp_name'], $imgPath);
                    $imgPaths[$imgField] = basename($img['name']);
                } else {
                    $imgPaths[$imgField] = null;
                }
            }

            // จัดการ img_3_news (หลายไฟล์)
            $img3Paths = [];
            if (isset($_FILES['img_3_news']['tmp_name']) && is_array($_FILES['img_3_news']['tmp_name'])) {
                foreach ($_FILES['img_3_news']['tmp_name'] as $key => $tmpName) {
                    if (!empty($tmpName)) {
                        $fileName = uniqid() . '.' . pathinfo($_FILES['img_3_news']['name'][$key], PATHINFO_EXTENSION);
                        $filePath = $uploadDir . $fileName;
                        if (move_uploaded_file($tmpName, $filePath)) {
                            $img3Paths[] = $fileName;
                        }
                    }
                }
            }

            // อัปเดตรูปภาพในฐานข้อมูล
            $updateSql = "UPDATE news SET img_1_news = :img_1, img_2_news = :img_2, img_3_news = :img_3 WHERE id_news = :id";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->bindParam(':img_1', $imgPaths['img_1_news'], PDO::PARAM_STR);
            $updateStmt->bindParam(':img_2', $imgPaths['img_2_news'], PDO::PARAM_STR);
            $updateStmt->bindParam(':img_3', json_encode($img3Paths), PDO::PARAM_STR);
            $updateStmt->bindParam(':id', $id_news, PDO::PARAM_INT);
            $updateStmt->execute();

            $_SESSION['success'] = 'เพิ่มข้อมูลโปรโมชั่นสำเร็จ';
        } else {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล';
        }

        header('Location: ../table_news.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'ข้อผิดพลาด: ' . $e->getMessage();
        header('Location: ../table_news.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'วิธีการร้องขอไม่ถูกต้อง';
    header('Location: ../table_news.php');
    exit();
}
