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

<!-- Main Content -->
<main class="container mx-auto p-8">
    <?php
    switch ($page) {
        case 'home':
            echo "<h2 class='text-3xl font-bold text-amber-500 mb-6'>Welcome, Government User</h2>";
            echo "<p class='text-gray-600'>This is your dashboard home.</p>";
            break;

        case 'products':
            // Subscription check
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
            <h2 class="text-3xl font-bold text-amber-500 mb-6">Available Products</h2>
            <?php if (!$subscribed): ?>
                <div class="bg-yellow-200 p-4 mb-4 rounded text-yellow-800">
                    You are viewing a limited list of products. <a href="?page=subscription" class="underline">Subscribe now</a> to unlock full access.
                </div>
            <?php endif; ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="bg-white p-4 rounded shadow">
                        <h3 class="text-xl font-semibold"><?= htmlspecialchars($row['product_name']) ?></h3>
                        <p class="text-gray-600"><?= htmlspecialchars($row['description']) ?></p>
                        <p class="mt-2 text-green-600 font-bold">₱<?= number_format($row['price'], 2) ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
            <?php
            break;

        case 'subscription':
            echo "<h2 class='text-3xl font-bold text-amber-500 mb-6'>Subscription</h2>";
            echo "<p class='text-gray-600'>Here you can manage your subscription plan. (Placeholder content)</p>";
            break;

        case 'profile':
            echo "<h2 class='text-3xl font-bold text-amber-500 mb-6'>Your Profile</h2>";
            echo "<p class='text-gray-600'>Update your profile details here. (Placeholder content)</p>";
            break;

        default:
            echo "<h2 class='text-3xl font-semibold mb-8 text-red-500'>Invalid Page</h2>";
    }
    ?>
</main>

<script>feather.replace()</script>
</body>
</html>
