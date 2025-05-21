<?php
// DB connection
include('../includes/db_connect.php');

// Check for connection error
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed.']));
}

// Handle file upload
$proofPath = '';
if (isset($_FILES['proof']) && $_FILES['proof']['error'] === 0) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $filename = uniqid() . '_' . basename($_FILES['proof']['name']);
    $proofPath = $uploadDir . $filename;

    if (!move_uploaded_file($_FILES['proof']['tmp_name'], $proofPath)) {
        echo json_encode(['success' => false, 'message' => 'Failed to upload proof of payment.']);
        exit;
    }
}

// Sanitize & retrieve POST data
$plan = $conn->real_escape_string($_POST['plan']);
$amount = (float) $_POST['amount'];
$name = $conn->real_escape_string($_POST['name']);
$contact = $conn->real_escape_string($_POST['contact']);
$payment_method = $conn->real_escape_string($_POST['payment_method']);
$amount_sent = (float) $_POST['amount_sent'];
$date_paid = date('Y-m-d H:i:s');

// Insert into database
$sql = "INSERT INTO subscription (plan, amount, name, contact, payment_method, amount_sent, proof, date_paid)
        VALUES ('$plan', $amount, '$name', '$contact', '$payment_method', $amount_sent, '$proofPath', '$date_paid')";

if ($conn->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'Payment submitted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$conn->close();