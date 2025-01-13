<?php
session_start();
include '../../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $id_contact = 1;
        $address_th_contact = $_POST['address_th_contact'];
        $email_contact = $_POST['email_contact'];
        $phone_contact = $_POST['phone_contact'];
        $open_th_contact = $_POST['open_th_contact'];
        $address_en_contact = $_POST['address_en_contact'];
        $open_en_contact = $_POST['open_en_contact'];
        $map_link_contact = $_POST['map_link_contact'];
        $line_contact = $_POST['line_contact']; // New field
        $facebook_contact = $_POST['facebook_contact']; // New field
        $youtube_contact = $_POST['youtube_contact']; // New field

        $stmt = $pdo->prepare("UPDATE contact SET 
            address_th_contact = :address_th_contact,
            email_contact = :email_contact,
            phone_contact = :phone_contact,
            open_th_contact = :open_th_contact,
            address_en_contact = :address_en_contact,
            open_en_contact = :open_en_contact,
            map_link_contact = :map_link_contact,
            line_contact = :line_contact,
            facebook_contact = :facebook_contact, 
            youtube_contact = :youtube_contact 
            WHERE id_contact = :id_contact");

        $stmt->execute([
            ':address_th_contact' => $address_th_contact,
            ':email_contact' => $email_contact,
            ':phone_contact' => $phone_contact,
            ':open_th_contact' => $open_th_contact,
            ':address_en_contact' => $address_en_contact,
            ':open_en_contact' => $open_en_contact,
            ':map_link_contact' => $map_link_contact,
            ':line_contact' => $line_contact, // New field
            ':facebook_contact' => $facebook_contact, // New field
            ':youtube_contact' => $youtube_contact, // New field
            ':id_contact' => $id_contact
        ]);

        $_SESSION['success'] = "Data updated successfully.";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }

    header('Location: ../page_contact.php');
    exit();
}
?>
