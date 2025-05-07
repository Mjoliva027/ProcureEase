<?php
include('../includes/db_connect.php');
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Government Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- Navbar -->
<nav class="bg-gray-800 p-4 text-white shadow-md">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="font-bold text-2xl">ProcureEase</div>
        <a href="logout.php" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600 transition">Logout</a>
    </div>
</nav>

<!-- Dashboard Content -->
<div class="max-w-7xl mx-auto p-8">
    <h2 class="text-4xl font-bold mb-10 text-amber-500">Government Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Moderation Card -->
        <div class="bg-white shadow-lg rounded-2xl p-6 hover:shadow-xl transition">
            <h3 class="text-xl font-semibold text-gray-800 mb-3">User & Content Moderation</h3>
            <p class="text-gray-600 mb-4">Approve or reject supplier and government registration requests.</p>
            <a href="moderation.php" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Review Registrations</a>
        </div>

        <!-- Procurement Review Card -->
        <div class="bg-white shadow-lg rounded-2xl p-6 hover:shadow-xl transition">
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Procurement Post Review</h3>
            <p class="text-gray-600 mb-4">Ensure procurement posts meet all platform guidelines before publication.</p>
            <a href="review_posts.php" class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Review Posts</a>
        </div>

        <!-- Analytics Card -->
        <div class="bg-white shadow-lg rounded-2xl p-6 hover:shadow-xl transition">
            <h3 class="text-xl font-semibold text-gray-800 mb-3">System Analytics & Reporting</h3>
            <p class="text-gray-600 mb-4">Track transactions, monitor supplier activity, and analyze system usage.</p>
            <a href="analytics.php" class="inline-block bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition">View Reports</a>
        </div>
    </div>
</div>

</body>
</html>
