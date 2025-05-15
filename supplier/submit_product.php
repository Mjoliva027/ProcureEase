<?php
include('../includes/db_connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['user_role'] == 'supplier') {
    // Getting product details
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $product_quantity = $_POST['product_quantity'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO products (user_id, product_name, product_description, product_price, quantity) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issdi", $user_id, $product_name, $product_description, $product_price, $product_quantity);

    if ($stmt->execute()) {
        $product_id = $stmt->insert_id;  // Get the product ID of the inserted product

        // Process images if uploaded
        $upload_dir = "../uploads/";

        // Automatically create the uploads directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                echo "Failed to create uploads directory.";
                exit();
            }
        }

        // Check if product images are uploaded
        if (isset($_FILES['product_images'])) {
            foreach ($_FILES['product_images']['tmp_name'] as $key => $tmp_name) {
                $file_name = basename($_FILES['product_images']['name'][$key]);
                // Generate a unique file name to avoid overwriting
                $unique_name = time() . '_' . bin2hex(random_bytes(5)) . '_' . $file_name;
                $target_file = $upload_dir . $unique_name;

                // Move the uploaded file to the uploads directory
                if (move_uploaded_file($tmp_name, $target_file)) {
                    // Insert image paths into product_images table
                    $img_stmt = $conn->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");
                    $img_stmt->bind_param("is", $product_id, $target_file);
                    $img_stmt->execute();
                }
            }
        }

        // If everything is successful, return a success message
        echo 'success';
    } else {
        echo 'Failed to post product';
    }
}
?>
