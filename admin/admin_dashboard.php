<?php
include('../includes/db_connect.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- Sidebar -->
<aside id="sidebar" class="sidebar sidebar-expanded fixed top-0 left-0 h-full bg-gray-800 text-white flex flex-col p-4 transition-all duration-300 z-30">
    <div class="flex items-center justify-between mb-8">
        <div class="text-2xl font-bold sidebar-text text-amber-400">ProcureEase</div>
        <button id="toggleSidebar" class="md:block text-white p-1 rounded hover:bg-gray-700">
            <i data-feather="menu"></i>
        </button>
    </div>
    <nav class="flex flex-col space-y-2">
        <a href="#" data-target="userModeration" class="nav-link flex items-center space-x-3 p-3 rounded hover:bg-gray-700 active-link bg-gray-700">
            <i data-feather="home"></i><span class="sidebar-text">User Moderation</span>
        </a>
        <a href="#" data-target="procurementReview" class="nav-link flex items-center space-x-3 p-3 rounded hover:bg-gray-700">
            <i data-feather="plus-square"></i><span class="sidebar-text">Procurement Review</span>
        </a>
        <a href="#" data-target="systemAnalytics" class="nav-link flex items-center space-x-3 p-3 rounded hover:bg-gray-700">
            <i data-feather="box"></i><span class="sidebar-text">System Analytics</span>
        </a>
    </nav>
    <a href="../logout.php" class="mt-auto bg-red-500 text-center p-3 rounded hover:bg-red-600 flex items-center justify-center space-x-2">
        <i data-feather="log-out"></i><span class="sidebar-text">Logout</span>
    </a>
</aside>

<!-- Main Content -->
<main id="mainContent" class="flex-1 pl-[260px] pr-6 pt-6 pb-6 space-y-10 overflow-y-auto transition-all duration-300">
    <h2 class="text-4xl font-bold text-amber-500 mb-4">Admin Dashboard</h2>

    <section id="userModeration">
        <?php include './user_moderation.php'; ?>
    </section>

    <section id="procurementReview" class="hidden">
        <?php include './procurement_review.php'; ?>
    </section>

    <section id="systemAnalytics" class="hidden">
        <?php include './system_analytics.php'; ?>
    </section>
</main>

<script>
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            // Toggle active class
            document.querySelectorAll('.nav-link').forEach(nav => nav.classList.remove('bg-gray-700'));
            link.classList.add('bg-gray-700');

            // Show/Hide sections
            const target = link.getAttribute('data-target');
            document.querySelectorAll('main > section').forEach(sec => {
                sec.classList.add('hidden');
            });
            document.getElementById(target).classList.remove('hidden');
        });
    });
</script>
</body>
</html>
