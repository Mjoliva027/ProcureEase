<?php
include('./includes/db_connect.php');
session_start();

// Check if the user is a government and logged in
//if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'government') {
    //header('Location: login.php');
    //exit();
//}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Government Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script> 
</head>
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-gray-800 p-4 text-white">
    <div class="flex justify-between items-center">
        <div class="font-bold text-2xl">ProcureEase</div>
        <div>
            <a href="logout.php" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">Logout</a>
        </div>
    </div>
</nav>

<!-- Dashboard Content -->
<div class="container mx-auto p-8">
    <h2 class="text-3xl font-semibold mb-8 text-amber-500">Government Dashboard</h2>

    <!-- Display Products -->
    <div>
        <h3 class="text-2xl font-semibold mb-4">Available Products</h3>
        <div id="availableProducts" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Products will be loaded here via AJAX -->
        </div>
    </div>
</div>

<script src="product_fetch.js"></script>
</body>
</html>
