<?php
include('../includes/db_connect.php');



if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'government') {
    header('Location: login.php');
    exit();
}

$government_id = $_SESSION['user_id'];

// Check subscription
$subscribed = false;
$sub_query = "SELECT * FROM subscriptions WHERE government_id = $government_id AND status = 'active'";
$sub_result = mysqli_query($conn, $sub_query);
if (mysqli_num_rows($sub_result) > 0) {
    $subscribed = true;
}

$product_limit = $subscribed ? "" : "LIMIT 5";
$query = "SELECT * FROM products ORDER BY created_at DESC $product_limit";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <h2 class="text-3xl font-bold text-amber-500 mb-6">Available Products</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="bg-white p-4 rounded shadow">
                <h3 class="text-xl font-semibold"><?= htmlspecialchars($row['product_name']) ?></h3>
                <p class="text-gray-600"><?= htmlspecialchars($row['description']) ?></p>
                <p class="mt-2 text-green-600 font-bold">₱<?= number_format($row['price'], 2) ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
