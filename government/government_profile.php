<?php
include('../includes/db_connect.php');


if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'government') {
    header('Location: login.php');
    exit();
}

$government_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $government_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <h2 class="text-3xl font-bold text-amber-500 mb-6">Your Profile</h2>

    <div class="bg-white p-6 rounded shadow-md max-w-lg">
        <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
    </div>
</body>
</html>
