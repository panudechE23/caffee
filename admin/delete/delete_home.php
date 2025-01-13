<?php
session_start();
require "../../db.php"; // Updated the path to db.php

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../../login.php"); // Updated the path to login.php
    exit;
}

if (isset($_GET['id'])) {
    $id_home = $_GET['id'];

    try {
        // ดึงชื่อโฟลเดอร์และไฟล์ที่เกี่ยวข้อง
        $stmt = $pdo->prepare("SELECT img_home FROM home WHERE id_home = :id_home");
        $stmt->bindParam(':id_home', $id_home, PDO::PARAM_INT);
        $stmt->execute();
        $home = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($home) {
            $folder_path = "../../img/home/$id_home"; // Updated the path to the folder

            // ลบโฟลเดอร์และไฟล์ทั้งหมดในโฟลเดอร์
            if (is_dir($folder_path)) {
                $files = array_diff(scandir($folder_path), array('.', '..'));
                foreach ($files as $file) {
                    unlink("$folder_path/$file");
                }
                rmdir($folder_path); // ลบโฟลเดอร์เมื่อไม่มีไฟล์เหลืออยู่
            }

            // ลบข้อมูลจากฐานข้อมูล
            $deleteStmt = $pdo->prepare("DELETE FROM home WHERE id_home = :id_home");
            $deleteStmt->bindParam(':id_home', $id_home, PDO::PARAM_INT);

            if ($deleteStmt->execute()) {
                $_SESSION['success'] = "ลบข้อมูลและโฟลเดอร์สำเร็จ!";
            } else {
                $_SESSION['error'] = "เกิดข้อผิดพลาดในการลบข้อมูล";
            }
        } else {
            $_SESSION['error'] = "ไม่พบข้อมูลที่ต้องการลบ";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
    }

    header("Location: ../addhome.php"); // Updated the path to addhome.php
    exit;
} else {
    $_SESSION['error'] = "ไม่พบ ID ที่ต้องการลบ";
    header("Location: ../addhome.php"); // Updated the path to addhome.php
    exit;
}
?>
