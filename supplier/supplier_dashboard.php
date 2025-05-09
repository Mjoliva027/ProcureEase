<?php
include('../includes/db_connect.php');
session_start();

// Check if the user is a supplier and logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'supplier') {
    header('Location: login.php');
    exit();
}

// Fetch supplier info
$user_id = $_SESSION['user_id'];
$query = "SELECT company_name, profile_image FROM suppliers WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$supplier = $result->fetch_assoc();

// Fallbacks in case of missing data
$companyName = $supplier['company_name'] ?? 'Your Company';
$profileImage = $supplier['profile_image'] ?? '../assets/profile-placeholder.png'; // Use relative path to default image

$query = "
    SELECT 
        u.name, u.email, s.company_name, s.contact, s.profile_image 
    FROM suppliers s
    JOIN users u ON s.user_id = u.id
    WHERE s.user_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$supplier = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supplier Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        .sidebar-collapsed .sidebar-text {
            display: none;
        }
        .sidebar-collapsed .sidebar {
            width: 80px;
        }
        .sidebar-expanded .sidebar {
            width: 260px;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex">

<!-- Sidebar -->
<aside id="sidebar" class="sidebar sidebar-expanded fixed top-0 left-0 h-full bg-gray-800 text-white flex flex-col p-4 transition-all duration-300 z-30">
 <!-- Toggle Button -->
    <div class="flex items-center justify-between mb-2">
        <div class="text-2xl font-bold sidebar-text text-amber-400">ProcureEase</div>
        <button id="toggleSidebar" class="md:block text-white p-1 rounded hover:bg-gray-700">
            <i data-feather="menu"></i>
        </button>
    </div> 
    <p class="sidebar-text text-sm text-gray-300 pl-6 mb-5">Welcome, Supplier</p>
<!-- Profile Section -->
<div class="flex flex-col items-center mb-8">
    <!-- Profile Image -->
    <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile" class="w-16 h-16 rounded-full border-4 border-amber-500 object-cover mb-2">
    
    <!-- Company Name -->
    <h2 class="text-lg font-bold text-amber-400 sidebar-text mb-2 text-center">
        <?php echo htmlspecialchars($companyName); ?>
    </h2>
</div>

   

    <!-- Navigation -->
    <nav class="flex flex-col space-y-2">
    <button id="profileBtn" class="flex items-center space-x-3 p-3 rounded hover:bg-gray-700 text-left w-full">
    <i data-feather="user"></i><span class="sidebar-text">Profile</span>
</button>
        <a href="#" data-target="dashboardSection" class="nav-link flex items-center space-x-3 p-3 rounded hover:bg-gray-700 active-link bg-gray-700">
            <i data-feather="home"></i><span class="sidebar-text">Dashboard</span>
        </a>
        <a href="#" data-target="postProductSection" class="nav-link flex items-center space-x-3 p-3 rounded hover:bg-gray-700">
            <i data-feather="plus-square"></i><span class="sidebar-text">Post Product</span>
        </a>
        <a href="#" data-target="yourProductsSection" class="nav-link flex items-center space-x-3 p-3 rounded hover:bg-gray-700">
            <i data-feather="box"></i><span class="sidebar-text">Your Products</span>
        </a>
        <a href="#" data-target="salesSection" class="nav-link flex items-center space-x-3 p-3 rounded hover:bg-gray-700">
            <i data-feather="bar-chart-2"></i><span class="sidebar-text">Sales</span>
        </a>
    </nav>

    <!-- Logout -->
    <a href="../logout.php" class="mt-auto bg-red-500 text-center p-3 rounded hover:bg-red-600 flex items-center justify-center space-x-2">
        <i data-feather="log-out"></i><span class="sidebar-text">Logout</span>
    </a>
</aside>

<!-- Profile Modal -->
<div id="profileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <button id="closeProfileModal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">
            <i data-feather="x"></i>
        </button>
        <div class="text-center">
            <img src="<?= htmlspecialchars($supplier['profile_image'] ?? '../assets/profile-placeholder.png') ?>" 
                 alt="Profile" class="w-24 h-24 mx-auto rounded-full border-4 border-amber-500 mb-4 object-cover">
            <h2 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($supplier['company_name'] ?? 'N/A') ?></h2>
            <p class="text-gray-600 mt-1">Name: <?= htmlspecialchars($supplier['name'] ?? 'N/A') ?></p>
            <p class="text-gray-600">Email: <?= htmlspecialchars($supplier['email'] ?? 'N/A') ?></p>
            <p class="text-gray-600">Phone: <?= htmlspecialchars($supplier['contact'] ?? 'N/A') ?></p>
        </div>
    </div>
</div>


<!-- Main Content -->
<main id="mainContent" class="flex-1 pl-[260px] pr-6 pt-6 pb-6 space-y-10 overflow-y-auto transition-all duration-300">

    <!-- Dashboard Section -->
    <section id="dashboardSection">
        <?php include './dashboard_section.php'; ?>
    </section>

    <!-- Post Product Section -->
    <section id="postProductSection" class="hidden"  style="margin-top:-5px;">
        <?php include './post_product_form.php'; ?>
    </section>

    <!-- Your Products Section -->
    <section id="yourProductsSection" class="hidden" style="margin-top:-5px;">
        <?php include './your_products_section.php'; ?>
    </section>

     <!-- Sales Section -->
     <section id="salesSection" class="hidden" style="margin-top:-5px;">
        <?php include './sales_section.php'; ?>
    </section>


</main>
<script src="../js/fetch_products.js"></script>
<script src="../js/product_submit.js"> </script>
<script>
    feather.replace();

    const sidebar = document.getElementById('sidebar');
    const toggleSidebar = document.getElementById('toggleSidebar');
    const mainContent = document.getElementById('mainContent');

    toggleSidebar.addEventListener('click', () => {
        sidebar.classList.toggle('sidebar-collapsed');
        sidebar.classList.toggle('sidebar-expanded');

        if (sidebar.classList.contains('sidebar-collapsed')) {
            mainContent.style.paddingLeft = '120px';
        } else {
            mainContent.style.paddingLeft = '260px';
        }
    });

    const links = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('main > section');

    links.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            links.forEach(l => l.classList.remove('bg-gray-700'));
            link.classList.add('bg-gray-700');

            const target = link.getAttribute('data-target');
            sections.forEach(section => {
                if (section.id === target) {
                    section.classList.remove('hidden');
                } else {
                    section.classList.add('hidden');
                }
            });
        });
    });

 // Feather icons refresh
 feather.replace();

// Profile modal toggle
const profileBtn = document.getElementById('profileBtn');
const profileModal = document.getElementById('profileModal');
const closeProfileModal = document.getElementById('closeProfileModal');

profileBtn.addEventListener('click', (e) => {
    e.preventDefault();
    profileModal.classList.remove('hidden');
});

closeProfileModal.addEventListener('click', () => {
    profileModal.classList.add('hidden');
});

window.addEventListener('click', (e) => {
    if (e.target === profileModal) {
        profileModal.classList.add('hidden');
    }
});
</script>

</body>
</html>
