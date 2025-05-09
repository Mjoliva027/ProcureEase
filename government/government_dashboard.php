<?php
include('../includes/db_connect.php');
session_start();

// Access Control
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'government') {
    header('Location: login.php');
    exit();
}

$government_id = $_SESSION['user_id'];
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Subscription Check
$subscribed = false;

$sub_query = "SELECT * FROM subscriptions WHERE government_id = $government_id AND status = 'active' ORDER BY start_date DESC LIMIT 1";
$sub_result = mysqli_query($conn, $sub_query);

if ($sub_result && mysqli_num_rows($sub_result) > 0) {
    $subscription = mysqli_fetch_assoc($sub_result);
    $today = date('Y-m-d');

    // Check if today is within the subscription period
    if ($today >= $subscription['start_date'] && $today <= $subscription['end_date']) {
        $subscribed = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Government Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-gray-800 p-4 text-white">
    <div class="flex justify-between items-center">
        <div class="font-bold text-2xl text-amber-400">ProcureEase</div>
        <div class="space-x-4">
            <a href="?page=home" class="hover:text-amber-400">Home</a>
            <a href="?page=products" class="hover:text-amber-400">Products</a>
            <a href="?page=subscription" class="hover:text-amber-400">Subscription</a>
            <a href="?page=profile" class="hover:text-amber-400">Profile</a>
            <a href="../logout.php" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">Logout</a>
        </div>
    </div>
</nav>

<!-- Subscription Suggestion -->
<?php
if (!$subscribed):
?>
    <div class="bg-yellow-200 p-4 mt-0 rounded text-yellow-800">
        <p>You are viewing limited product suggestions. <a href="?page=subscription" class="underline text-amber-500">Subscribe now</a> to unlock full access.</p>
    </div>
<?php endif; ?>

<!-- Main Content -->
<main class="container mx-auto p-8">
    <?php
    switch ($page) {
        case 'home':
            // Product suggestions
            $suggestions_query = "SELECT * FROM products ORDER BY created_at DESC LIMIT 3";
            $suggestions_result = mysqli_query($conn, $suggestions_query);
            ?>
            
            <div class="mt-8">
                <h3 class="text-2xl font-semibold text-amber-500 mb-4">Suggested Products</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php while($row = mysqli_fetch_assoc($suggestions_result)): ?>
                        <div class="bg-white p-4 rounded shadow">
                            <!-- Display Product Image -->
                            <?php
                            $image_query = "SELECT image_path FROM product_images WHERE product_id = " . $row['product_id'] . " LIMIT 1";
                            $image_result = mysqli_query($conn, $image_query);
                            $image = mysqli_fetch_assoc($image_result);
                            ?>
                            <?php if (!empty($image['image_path'])): ?>
                                <img src="../uploads/<?= htmlspecialchars($image['image_path']) ?>" class="w-full h-40 object-cover rounded" alt="Product Image">
                            <?php endif; ?>
                            
                            <h3 class="text-xl font-semibold"><?= htmlspecialchars($row['product_name']) ?></h3>
                            <p class="text-gray-600"><?= htmlspecialchars($row['product_description']) ?></p>
                            <p class="mt-2 text-green-600 font-bold">₱<?= number_format($row['product_price'], 2) ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php
            break;

        case 'products':
            include 'government_viewproducts.php';
            break;

        case 'subscription':
            include 'government_subscription.php';
            break;

        case 'profile':
            include 'government_profile.php';
            break;

        default:
            echo "<h2 class='text-3xl font-semibold mb-8 text-red-500'>Invalid Page</h2>";
    }
    ?>
</main>

<script>feather.replace()</script>
</body>
</html>
