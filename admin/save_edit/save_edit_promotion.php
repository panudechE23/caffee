<?php
require '../../db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_promotion = $_POST['id_promotion'];

        // ดึงข้อมูลปัจจุบันจากฐานข้อมูล
        $stmt = $pdo->prepare("SELECT * FROM promotion WHERE id_promotion = :id_promotion");
        $stmt->execute([':id_promotion' => $id_promotion]);
        $current_promotion = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$current_promotion) {
            throw new Exception("ไม่พบข้อมูลโปรโมชั่น");
        }

        // เตรียมข้อมูลสำหรับอัปเดต
        $fields_to_update = [];
        $parameters = [':id_promotion' => $id_promotion];

        // ตรวจสอบและอัปเดตข้อมูล
        foreach (['name_th_promotion', 'name_en_promotion', 'detail_th_promotion', 'detail_en_promotion'] as $field) {
            if ($current_promotion[$field] !== $_POST[$field]) {
                $fields_to_update[] = "$field = :$field";
                $parameters[":$field"] = $_POST[$field];
            }
        }

        // อัปเดตวันที่
        $start_date_input = $_POST['start_date_promotion'];
        $end_date_input = $_POST['end_date_promotion'];
        $start_date = DateTime::createFromFormat('d/m/Y', $start_date_input);
        $end_date = DateTime::createFromFormat('d/m/Y', $end_date_input);

        if ($start_date && $end_date) {
            $start_date_promotion = $start_date->format('Y-m-d');
            $end_date_promotion = $end_date->format('Y-m-d');

            if ($current_promotion['start_date_promotion'] !== $start_date_promotion) {
                $fields_to_update[] = "start_date_promotion = :start_date_promotion";
                $parameters[':start_date_promotion'] = $start_date_promotion;
            }

            if ($current_promotion['end_date_promotion'] !== $end_date_promotion) {
                $fields_to_update[] = "end_date_promotion = :end_date_promotion";
                $parameters[':end_date_promotion'] = $end_date_promotion;
            }
        }

        // จัดการรูปภาพ
        $upload_dir = "../../img/promotion/$id_promotion/";
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

        foreach (['img_1_promotion', 'img_2_promotion'] as $img_field) {
            if (isset($_FILES[$img_field]) && $_FILES[$img_field]['error'] === UPLOAD_ERR_OK) {
                $new_image = uploadImage($_FILES[$img_field], $upload_dir, $current_promotion[$img_field]);
                $fields_to_update[] = "$img_field = :$img_field";
                $parameters[":$img_field"] = $new_image;
            }
        }

        // จัดการ img_3_promotion (หลายไฟล์)
        if (isset($_FILES['img_3_promotion'])) {
            $existing_images = json_decode($current_promotion['img_3_promotion'], true) ?? [];

            // ลบไฟล์เก่า
            foreach ($existing_images as $existing_image) {
                $existing_file_path = $upload_dir . $existing_image;
                if (file_exists($existing_file_path)) {
                    unlink($existing_file_path);
                }
            }

            $new_images = [];
            foreach ($_FILES['img_3_promotion']['tmp_name'] as $key => $tmp_name) {
                if (!empty($tmp_name)) {
                    $file_name = uniqid() . '.' . pathinfo($_FILES['img_3_promotion']['name'][$key], PATHINFO_EXTENSION);
                    $file_path = $upload_dir . $file_name;
                    if (move_uploaded_file($tmp_name, $file_path)) {
                        $new_images[] = $file_name;
                    }
                }
            }

            $fields_to_update[] = "img_3_promotion = :img_3_promotion";
            $parameters[":img_3_promotion"] = json_encode($new_images);
        }

        // อัปเดตข้อมูลในฐานข้อมูล
        if (!empty($fields_to_update)) {
            $sql = "UPDATE promotion SET " . implode(', ', $fields_to_update) . " WHERE id_promotion = :id_promotion";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($parameters);
            $_SESSION['success'] = "แก้ไขข้อมูลสำเร็จ!";
        } else {
            $_SESSION['success'] = "ไม่มีการเปลี่ยนแปลงข้อมูล!";
        }

        header("Location: ../table_promotion.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
        header("Location: ../edit/edit_list_promotion.php?id=" . $id_promotion);
        exit;
    }
} else {
    $_SESSION['error'] = "คำขอไม่ถูกต้อง!";
    header("Location: ../table_promotion.php");
    exit;
}
