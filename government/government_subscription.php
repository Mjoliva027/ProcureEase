<?php
include('../includes/db_connect.php');

// Access Control
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'government') {
    header('Location: login.php');
    exit();
}

$government_id = $_SESSION['user_id'];
$message = '';

// Check if subscription exists
$query = "SELECT * FROM subscriptions WHERE government_id = $government_id ORDER BY start_date DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$current_subscription = mysqli_fetch_assoc($result);

// Handle new subscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plan = $_POST['plan'];
    $start_date = date('Y-m-d');

    // Calculate end date
    if ($plan === 'Basic') {
        $end_date = date('Y-m-d', strtotime('+1 month'));
    } elseif ($plan === 'Premium') {
        $end_date = date('Y-m-d', strtotime('+1 year'));
    } else {
        $end_date = $start_date;
    }

    $status = 'active';

    $insert = "INSERT INTO subscriptions (government_id, plan, start_date, end_date, status)
               VALUES ('$government_id', '$plan', '$start_date', '$end_date', '$status')";
    if (mysqli_query($conn, $insert)) {
        $message = "Subscription to $plan plan successful!";
        $result = mysqli_query($conn, $query);
        $current_subscription = mysqli_fetch_assoc($result);
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<h2 class="text-3xl font-bold text-amber-500 mb-6">Subscription</h2>

<?php if ($message): ?>
    <div class="bg-green-100 text-green-800 p-4 rounded mb-4"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<?php if ($current_subscription): ?>
    <div class="bg-white p-6 rounded shadow mb-6">
        <h3 class="text-xl font-semibold mb-2">Current Subscription</h3>
        <p><strong>Plan:</strong> <?= htmlspecialchars($current_subscription['plan']) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($current_subscription['status']) ?></p>
        <p><strong>Start Date:</strong> <?= htmlspecialchars($current_subscription['start_date']) ?></p>
        <p><strong>End Date:</strong> <?= htmlspecialchars($current_subscription['end_date']) ?></p>
    </div>
<?php endif; ?>

<div class="bg-white p-6 rounded shadow">
    <h3 class="text-xl font-semibold mb-4">Choose a Plan</h3>
    <form method="POST">
        <label class="block mb-4">
            <input type="radio" name="plan" value="Basic" required class="mr-2">
            <span class="font-medium">Basic Plan - ₱500/month</span>
        </label>
        <label class="block mb-4">
            <input type="radio" name="plan" value="Premium" required class="mr-2">
            <span class="font-medium">Premium Plan - ₱5000/year</span>
        </label>
        <button type="submit" class="bg-amber-500 text-white px-4 py-2 rounded hover:bg-amber-600">
            Subscribe
        </button>
    </form>
</div>
