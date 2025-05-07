<?php
include('./includes/db_connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['user_role'] == 'government') {
    $agency_name = $_POST['agency_name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO governments (user_id, agency_name, address, contact) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $agency_name, $address, $contact);

    if ($stmt->execute()) {
        // Update is_new to 0
        $update = $conn->prepare("UPDATE users SET is_new = 0 WHERE id = ?");
        $update->bind_param("i", $user_id);
        $update->execute();

        echo 'success';
    } else {
        echo 'Failed to save data';
    }
}
?>
