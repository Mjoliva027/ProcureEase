<?php
include('../includes/db_connect.php');
session_start();


// Fetch pending users
$pendingUsers = $conn->query("SELECT id, name, email, role FROM users WHERE is_new = 1");

// Fetch products (procurement posts) – assuming all need review
$pendingProducts = $conn->query("SELECT p.product_id, p.product_name, g.agency_name, p.created_at
    FROM products p
    JOIN governments g ON p.user_id = g.user_id
    ORDER BY p.created_at DESC
    LIMIT 10");
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

<nav class="bg-gray-800 p-4 text-white shadow">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="text-2xl font-bold">ProcureEase</div>
        <a href="../logout.php" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">Logout</a>
    </div>
</nav>

<div class="max-w-7xl mx-auto p-8 space-y-16">
    <h2 class="text-4xl font-bold text-amber-500">Admin Dashboard</h2>

    <!-- User Moderation -->
    <section>
        <h3 class="text-2xl font-semibold mb-4">User & Content Moderation</h3>
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Role</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($user = $pendingUsers->fetch_assoc()): ?>
                    <tr class="border-b">
                        <td class="px-4 py-2"><?= htmlspecialchars($user['name']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($user['email']) ?></td>
                        <td class="px-4 py-2"><?= ucfirst($user['role']) ?></td>
                        <td class="px-4 py-2 text-center">
                            <button class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Approve</button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Reject</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Procurement Review -->
    <section>
        <h3 class="text-2xl font-semibold mb-4">Procurement Post Review</h3>
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">Product</th>
                        <th class="px-4 py-2 text-left">Agency</th>
                        <th class="px-4 py-2 text-left">Posted</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($prod = $pendingProducts->fetch_assoc()): ?>
                    <tr class="border-b">
                        <td class="px-4 py-2"><?= htmlspecialchars($prod['product_name']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($prod['agency_name']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($prod['created_at']) ?></td>
                        <td class="px-4 py-2 text-center">
                            <button class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Approve</button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Reject</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Analytics -->
    <section>
        <h3 class="text-2xl font-semibold mb-6">System Analytics & Reporting</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h4 class="text-lg font-medium mb-2">Monthly Transactions</h4>
                <canvas id="transactionsChart" height="200"></canvas>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h4 class="text-lg font-medium mb-2">Supplier Engagement</h4>
                <canvas id="engagementChart" height="200"></canvas>
            </div>
        </div>
    </section>
</div>

<script>
const transactionsChart = new Chart(document.getElementById('transactionsChart'), {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
        datasets: [{
            label: 'Transactions',
            data: [120, 190, 300, 250, 400],
            backgroundColor: 'rgba(59, 130, 246, 0.7)',
            borderRadius: 6
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

const engagementChart = new Chart(document.getElementById('engagementChart'), {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
        datasets: [{
            label: 'Active Suppliers',
            data: [30, 45, 60, 55, 80],
            fill: true,
            backgroundColor: 'rgba(34, 197, 94, 0.2)',
            borderColor: 'rgba(34, 197, 94, 1)',
            tension: 0.3
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>

</body>
</html>
