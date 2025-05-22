<?php
include('../includes/db_connect.php');
session_start();

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Get filters
$search = $_GET['q'] ?? '';
$min = $_GET['min'] ?? '';
$max = $_GET['max'] ?? '';

$query = "
    SELECT 
        p.product_id,           
        p.product_name, 
        p.product_description, 
        p.product_price, 
        pi.image_path 
    FROM 
        products p
    LEFT JOIN 
        product_images pi ON p.product_id = pi.product_id
";

// Build WHERE clause
$conditions = [];
$params = [];
$types = "";

if ($search) {
    $conditions[] = "(p.product_name LIKE ? OR p.product_description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "ss";
}
if (is_numeric($min)) {
    $conditions[] = "p.product_price >= ?";
    $params[] = $min;
    $types .= "d";
}
if (is_numeric($max)) {
    $conditions[] = "p.product_price <= ?";
    $params[] = $max;
    $types .= "d";
}

if ($conditions) {
    $query .= " WHERE " . implode(" AND ", $conditions);
} else {
    $query .= " ORDER BY RAND()"; // Only apply randomization when no filters
}

$stmt = $conn->prepare($query);
if ($types) {
    $stmt->bind_param($types, ...$params);
}

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $products = [];

    while ($row = $result->fetch_assoc()) {
        $id = $row['product_id'];
        if (!isset($products[$id])) {
            $products[$id] = [
                'product_id' => $id,
                'product_name' => $row['product_name'],
                'product_description' => $row['product_description'],
                'product_price' => $row['product_price'],
                'images' => []
            ];
        }
        if ($row['image_path']) {
            $products[$id]['images'][] = $row['image_path'];
        }
    }

    echo json_encode(array_values($products));
} else {
    echo json_encode(['error' => 'Failed to fetch products']);
}
?>
