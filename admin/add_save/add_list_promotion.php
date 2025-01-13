<?php
session_start();
require '../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // รับค่าจากฟอร์ม
        $name_th_promotion = trim($_POST['name_th_promotion']);
        $name_en_promotion = trim($_POST['name_en_promotion']);
        $detail_th_promotion = trim($_POST['detail_th_promotion']);
        $detail_en_promotion = trim($_POST['detail_en_promotion']);
        $start_date_promotion = date('Y-m-d', strtotime($_POST['start_date_promotion']));
        $end_date_promotion = date('Y-m-d', strtotime($_POST['end_date_promotion']));

        // เพิ่มข้อมูลเบื้องต้นในฐานข้อมูลเพื่อสร้าง ID
        $sql = "INSERT INTO promotion (name_th_promotion, name_en_promotion, detail_th_promotion, detail_en_promotion, start_date_promotion, end_date_promotion) 
                VALUES (:name_th, :name_en, :detail_th, :detail_en, :start_date, :end_date)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name_th' => $name_th_promotion,
            ':name_en' => $name_en_promotion,
            ':detail_th' => $detail_th_promotion,
            ':detail_en' => $detail_en_promotion,
            ':start_date' => $start_date_promotion,
            ':end_date' => $end_date_promotion,
        ]);

        $id_promotion = $pdo->lastInsertId();

        // สร้างโฟลเดอร์สำหรับเก็บรูปภาพ
        $uploadDir = "../../img/promotion/$id_promotion/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // อัปโหลดรูปภาพ img_1 และ img_2
        $imgPaths = [];
        foreach (['img_1_promotion', 'img_2_promotion'] as $imgField) {
            $img = $_FILES[$imgField] ?? null;
            if ($img && $img['error'] === UPLOAD_ERR_OK) {
                $fileName = uniqid() . '.' . pathinfo($img['name'], PATHINFO_EXTENSION);
                $filePath = $uploadDir . $fileName;
                if (move_uploaded_file($img['tmp_name'], $filePath)) {
                    $imgPaths[$imgField] = $fileName;
                }
            } else {
                $imgPaths[$imgField] = null;
            }
        }

        // จัดการ img_3_promotion (หลายไฟล์)
        $img3Paths = [];
        if (!empty($_FILES['img_3_promotion']['tmp_name'][0])) {
            foreach ($_FILES['img_3_promotion']['tmp_name'] as $key => $tmpName) {
                if (!empty($tmpName)) {
                    $fileName = uniqid() . '.' . pathinfo($_FILES['img_3_promotion']['name'][$key], PATHINFO_EXTENSION);
                    $filePath = $uploadDir . $fileName;
                    if (move_uploaded_file($tmpName, $filePath)) {
                        $img3Paths[] = $fileName;
                    }
                }
            }
        }

        // อัปเดตรูปภาพในฐานข้อมูล
        $updateSql = "UPDATE promotion SET img_1_promotion = :img_1, img_2_promotion = :img_2, img_3_promotion = :img_3 WHERE id_promotion = :id";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([
            ':img_1' => $imgPaths['img_1_promotion'] ?? null,
            ':img_2' => $imgPaths['img_2_promotion'] ?? null,
            ':img_3' => json_encode($img3Paths),
            ':id' => $id_promotion,
        ]);

        $_SESSION['success'] = 'เพิ่มข้อมูลโปรโมชั่นสำเร็จ';
        header('Location: ../table_promotion.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'ข้อผิดพลาด: ' . $e->getMessage();
        header('Location: ../table_promotion.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'วิธีการร้องขอไม่ถูกต้อง';
    header('Location: ../table_promotion.php');
    exit();
}
