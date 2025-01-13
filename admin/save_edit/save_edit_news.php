<?php
require '../../db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_news = $_POST['id_news'];

        // ดึงข้อมูลปัจจุบันจากฐานข้อมูล
        $stmt = $pdo->prepare("SELECT * FROM news WHERE id_news = :id_news");
        $stmt->execute([':id_news' => $id_news]);
        $current_news = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$current_news) {
            throw new Exception("ไม่พบข้อมูลที่ต้องการแก้ไข");
        }

        // เตรียมข้อมูลสำหรับอัปเดต
        $fields_to_update = [];
        $parameters = [':id_news' => $id_news];

        // ตรวจสอบและอัปเดตข้อมูล
        foreach (['name_th_news', 'name_en_news', 'detail_th_news', 'detail_en_news'] as $field) {
            if ($current_news[$field] !== $_POST[$field]) {
                $fields_to_update[] = "$field = :$field";
                $parameters[":$field"] = $_POST[$field];
            }
        }

        // จัดการรูปภาพ
        $upload_dir = "../../img/news/$id_news/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        function uploadImage($file, $upload_dir, $old_image = null) {
            if ($file['error'] === UPLOAD_ERR_OK) {
                $file_name = uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
                $file_path = $upload_dir . $file_name;

                if ($old_image && file_exists($upload_dir . $old_image)) {
                    unlink($upload_dir . $old_image);
                }

                if (move_uploaded_file($file['tmp_name'], $file_path)) {
                    return $file_name;
                }
            }
            return $old_image;
        }

        foreach (['img_1_news', 'img_2_news'] as $img_field) {
            if (isset($_FILES[$img_field]) && $_FILES[$img_field]['error'] === UPLOAD_ERR_OK) {
                $new_image = uploadImage($_FILES[$img_field], $upload_dir, $current_news[$img_field]);
                $fields_to_update[] = "$img_field = :$img_field";
                $parameters[":$img_field"] = $new_image;
            }
        }

        // จัดการ img_3_news (หลายไฟล์)
        if (isset($_FILES['img_3_news']) && !empty($_FILES['img_3_news']['tmp_name'][0])) {
            $new_images = [];
            foreach ($_FILES['img_3_news']['tmp_name'] as $key => $tmp_name) {
                if (!empty($tmp_name)) {
                    $file_name = uniqid() . '.' . pathinfo($_FILES['img_3_news']['name'][$key], PATHINFO_EXTENSION);
                    $file_path = $upload_dir . $file_name;
                    if (move_uploaded_file($tmp_name, $file_path)) {
                        $new_images[] = $file_name;
                    }
                }
            }

            if (!empty($new_images)) {
                // ลบไฟล์เก่าหากมีการอัปโหลดใหม่
                $existing_images = json_decode($current_news['img_3_news'], true) ?? [];
                foreach ($existing_images as $existing_image) {
                    $existing_file_path = $upload_dir . $existing_image;
                    if (file_exists($existing_file_path)) {
                        unlink($existing_file_path);
                    }
                }

                $fields_to_update[] = "img_3_news = :img_3_news";
                $parameters[":img_3_news"] = json_encode($new_images);
            }
        }

        // อัปเดตข้อมูลในฐานข้อมูล
        if (!empty($fields_to_update)) {
            $sql = "UPDATE news SET " . implode(', ', $fields_to_update) . " WHERE id_news = :id_news";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($parameters);
            $_SESSION['success'] = "แก้ไขข้อมูลสำเร็จ!";
        } else {
            $_SESSION['success'] = "ไม่มีการเปลี่ยนแปลงข้อมูล!";
        }

        header("Location: ../table_news.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
        header("Location: ../edit/edit_list_news.php?id=" . $id_news);
        exit;
    }
} else {
    $_SESSION['error'] = "คำขอไม่ถูกต้อง!";
    header("Location: ../table_news.php");
    exit;
}
