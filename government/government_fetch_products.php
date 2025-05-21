<?php
include('../includes/db_connect.php');
session_start();

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$stmt = $conn->prepare("
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
");

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $products = [];

    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];
        if (!isset($products[$product_id])) {
            $products[$product_id] = [
                'product_id' => $product_id,
                'product_name' => $row['product_name'],
                'product_description' => $row['product_description'],
                'product_price' => $row['product_price'],
                'images' => []
            ];
        }
        if ($row['image_path']) {
            $products[$product_id]['images'][] = $row['image_path'];
        }
    }

    echo json_encode(array_values($products));
} else {
    echo json_encode(['error' => 'Failed to fetch products']);
}
?>
