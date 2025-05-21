<?php
require_once '../includes/db_connect.php'; // Adjust the path if needed
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Decode JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Check if JSON decoding worked
if ($data === null) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
    exit;
}

// Validate required fields
if (
    !isset($data['product_id']) ||
    !isset($data['product_name']) ||
    !isset($data['product_description']) ||
    !isset($data['product_price']) ||
    !isset($data['quantity'])
) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Sanitize & assign variables
$id = intval($data['product_id']);
$name = trim($data['product_name']);
$description = trim($data['product_description']);
$price = floatval($data['product_price']);
$quantity = intval($data['quantity']);

// Prepare and execute update with error handling
$stmt = $conn->prepare("UPDATE products SET 
    product_name = ?, 
    product_description = ?, 
    product_price = ?, 
    quantity = ?
    WHERE product_id = ?");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ssdii", $name, $description, $price, $quantity, $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No rows updated. Check if product_id exists.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Update failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
