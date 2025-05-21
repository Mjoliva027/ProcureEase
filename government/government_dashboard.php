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

$subscribed = false;

// Prepare and execute subscription check query
// Only consider payments with status 'approved' or 'active' (whichever you use)
$query = "SELECT * FROM subscription_payments WHERE user_id = ? AND status = 'approved' LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $government_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $subscription = $result->fetch_assoc();
    $today = date('Y-m-d');

    // Check if today's date is within the subscription period
    if ($today >= $subscription['start_date'] && $today <= $subscription['end_date']) {
        $subscribed = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
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
            <a href="?page=orders" class="hover:text-amber-400">Orders</a>
            <a href="?page=profile" class="hover:text-amber-400">Profile</a>
            <a href="../logout.php" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">Logout</a>
        </div>
    </div>
</nav>

<?php if (!$subscribed): ?>
<!-- Subscription Banner -->
<div id="subscriptionBanner" class="bg-yellow-400 cursor-pointer text-black py-3 px-4">
    You are viewing limited product suggestions. Please 
    <a href="#" id="subscriptionLink" class="underline text-blue-700">subscribe</a> to see full access.
</div>

<!-- Subscription Plan Modal -->
<div id="subscriptionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-lg w-full p-6 relative shadow-xl">
        <button id="closeSubscriptionModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-3xl font-bold leading-none">&times;</button>
        <h2 class="text-2xl font-bold mb-4 text-amber-600">Choose a Subscription Plan</h2>

        <p class="mb-6 text-gray-700 border rounded-lg p-4 shadow hover:shadow-md transition cursor-pointer">
            <strong>Free Plan</strong> <br>
            View up to 5 posted products. Upgrade to Premium for unlimited access:
        </p>

        <div class="space-y-4">
            <div onclick="openPaymentForm('Premium Monthly', 499)" class="border rounded-lg p-4 shadow hover:shadow-md transition cursor-pointer">
                <h3 class="text-xl font-semibold text-gray-800">Premium Monthly Plan</h3>
                <p class="text-gray-600 mb-2">₱499 / 30 days - Unlimited product views</p>
                <span class="bg-amber-500 hover:bg-amber-700 text-white px-4 py-2 rounded inline-block mt-2">Subscribe Monthly</span>
            </div>

            <div onclick="openPaymentForm('Premium Yearly', 4999)" class="border rounded-lg p-4 shadow hover:shadow-md transition cursor-pointer">
                <h3 class="text-xl font-semibold text-gray-800">Premium Yearly Plan</h3>
                <p class="text-gray-600 mb-2">₱4,999 / 365 days - Save ₱989 with yearly subscription</p>
                <span class="bg-amber-500 hover:bg-amber-700 text-white px-4 py-2 rounded inline-block mt-2">Subscribe Yearly</span>
            </div>
        </div>
    </div>
</div>
<!-- Payment Form Modal -->
<div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg w-full max-w-4xl p-6 relative shadow-xl">
        <button id="closePaymentModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-3xl font-bold leading-none">&times;</button>
        <h2 class="text-2xl font-bold mb-4 text-amber-600">Complete Payment</h2>
       <form id="subscriptionForm" class="grid grid-cols-1 md:grid-cols-2 gap-6" enctype="multipart/form-data">
            <input type="hidden" id="plan_type" name="plan_type">
            <input type="hidden" id="plan_amount" name="plan_amount">

            <div>
                <label class="block font-semibold text-gray-700">Subscription Type:</label>
                <input type="text" id="display_plan_type" class="w-full border px-3 py-2 rounded" disabled>
            </div>

            <div>
                <label class="block font-semibold text-gray-700">Amount:</label>
                <input type="text" id="display_plan_amount" class="w-full border px-3 py-2 rounded" disabled>
            </div>

            <div>
                <label class="block font-semibold text-gray-700">Full Name:</label>
                <input type="text" name="full_name" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div>
                <label class="block font-semibold text-gray-700">Contact Number:</label>
                <input type="text" name="contact_number" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div>
                <label class="block font-semibold text-gray-700">Payment Method:</label>
                <select name="payment_method" class="w-full border px-3 py-2 rounded" required>
                    <option value="">-- Select --</option>
                    <option value="gcash">GCash</option>
                    <option value="paypal">PayPal</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold text-gray-700">Amount Sent:</label>
                <input type="number" name="amount_sent" class="w-full border px-3 py-2 rounded" required>
            </div>

            <!-- GCash QR Code -->
            <div id="gcashQRCode" class="md:col-span-2 mt-4 hidden">
                <label class="block font-semibold text-gray-700 mb-2">Scan this GCash QR Code:</label>
                <img src="path/to/your-gcash-qr-code.png" alt="GCash QR Code" class="mx-auto w-32 object-contain" />
            </div>

            <!-- PayPal QR Code -->
            <div id="paypalQRCode" class="md:col-span-2 mt-4 hidden">
                <label class="block font-semibold text-gray-700 mb-2">Pay with PayPal using this QR Code:</label>
                <img src="path/to/your-paypal-qr-code.png" alt="PayPal QR Code" class="mx-auto w-32 object-contain" />
            </div>

            <div class="md:col-span-2">
                <label class="block font-semibold text-gray-700">Upload Proof of Payment:</label>
                <input type="file" name="proof_of_payment" accept="image/*" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div class="md:col-span-2 text-right">
        <button type="submit" class="bg-amber-500 hover:bg-amber-700 text-white px-4 py-2 rounded">Submit</button>
    </div>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- Main Content -->
<main class="bg-gray-100 p-6 min-h-screen">
    <?php
    switch ($page) {
        case 'home':
            // Fetch Suggested Products (limit 3) with supplier name
            $suggestions_query = "
                SELECT p.*, s.company_name 
                FROM products p 
                LEFT JOIN suppliers s ON p.user_id = s.user_id
                ORDER BY p.created_at DESC LIMIT 3
            ";
            $suggestions_result = mysqli_query($conn, $suggestions_query);
            ?>

            <h1 class="text-2xl font-bold text-amber-600 mb-4">Suggested Products</h1>

            <div id="productContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php while($product = mysqli_fetch_assoc($suggestions_result)): ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col h-full">
                        <?php
                        $image_query = "SELECT image_path FROM product_images WHERE product_id = " . intval($product['product_id']) . " LIMIT 1";
                        $image_result = mysqli_query($conn, $image_query);
                        $image = mysqli_fetch_assoc($image_result);
                        ?>
                        <?php if ($image): ?>
                            <img src="../uploads/<?= htmlspecialchars($image['image_path']) ?>" class="w-full h-48 object-cover rounded-t-lg" alt="Product Image" />
                        <?php else: ?>
                            <p class="text-center text-gray-500 p-4">No Image Available</p>
                        <?php endif; ?>

                        <div class="flex-1 p-4">
                            <h3 class="text-lg font-semibold text-center"><?= htmlspecialchars($product['product_name']) ?></h3>
                            <p class="text-sm text-gray-600 text-center mb-2"><?= htmlspecialchars($product['product_description']) ?></p>
                            <p class="text-lg font-semibold text-center text-green-600">₱<?= number_format($product['product_price'], 2) ?></p>
                        </div>

                        <?php
                        // Fetch all images for modal
                        $images_query = "SELECT image_path FROM product_images WHERE product_id = " . intval($product['product_id']);
                        $images_result = mysqli_query($conn, $images_query);
                        $images = [];
                        while($img = mysqli_fetch_assoc($images_result)) {
                            $images[] = $img['image_path'];
                        }
                        ?>
                        <div class="p-4">
                            <button
                                class="w-full text-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 view-product-btn"
                                data-product='<?= json_encode([
                                    'id' => $product['product_id'],
                                    'name' => $product['product_name'],
                                    'description' => $product['product_description'],
                                    'price' => number_format($product['product_price'], 2),
                                    'images' => $images,
                                    'supplier' => $product['company_name'] ?? 'Unknown Supplier'
                                ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
                            >
                                View Product
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <?php
            break;

        case 'products':
            include 'government_viewproducts.php';
            break;
        case 'orders':
            include 'government_orders.php';
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

<!-- Product View Modal -->
<div id="productViewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-3xl w-full p-6 relative shadow-lg overflow-auto max-h-[90vh]">
        <button id="closeProductModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-3xl font-bold leading-none">&times;</button>
        <p id="modalSupplierName" class="mb-2 italic text-gray-600"></p>
        <div id="modalImages" class="flex overflow-x-auto space-x-4 mb-4"></div>
        <h2 id="modalProductName" class="text-3xl font-bold mb-2"></h2>
        <p id="modalProductDesc" class="mb-4 text-gray-700"></p>
        <p id="modalProductPrice" class="text-xl font-semibold"></p>
    </div>
</div>

<script>
feather.replace();

// PRODUCT VIEW MODAL
document.querySelectorAll('.view-product-btn').forEach(button => {
    button.addEventListener('click', () => {
        const modal = document.getElementById('productViewModal');
        const productData = JSON.parse(button.dataset.product);

        document.getElementById('modalProductName').textContent = productData.name;
        document.getElementById('modalSupplierName').textContent = "Supplier: " + productData.supplier;
        document.getElementById('modalProductDesc').textContent = productData.description;
        document.getElementById('modalProductPrice').textContent = '₱' + productData.price;

        const imagesContainer = document.getElementById('modalImages');
        imagesContainer.innerHTML = '';

        if (productData.images.length > 0) {
            productData.images.forEach(imgPath => {
                const img = document.createElement('img');
                img.src = '../uploads/' + imgPath;
                img.alt = productData.name;
                img.className = 'w-48 h-48 object-cover rounded shadow';
                imagesContainer.appendChild(img);
            });
        } else {
            const img = document.createElement('img');
            img.src = '../uploads/no-image.png';
            img.alt = 'No image available';
            img.className = 'w-48 h-48 object-cover rounded shadow';
            imagesContainer.appendChild(img);
        }

        modal.classList.remove('hidden');
    });
});

// CLOSE MODALS
document.getElementById('closeProductModal')?.addEventListener('click', () => {
    document.getElementById('productViewModal').classList.add('hidden');
});

document.getElementById('subscriptionLink')?.addEventListener('click', (e) => {
    e.preventDefault();
    document.getElementById('subscriptionModal').classList.remove('hidden');
});

document.getElementById('closeSubscriptionModal')?.addEventListener('click', () => {
    document.getElementById('subscriptionModal').classList.add('hidden');
});

document.getElementById('closePaymentModal')?.addEventListener('click', () => {
    document.getElementById('paymentModal').classList.add('hidden');
});

// OPEN PAYMENT FORM FUNCTION (fixes your error)
function openPaymentForm(plan, amount) {
    document.getElementById('plan_type').value = plan;
    document.getElementById('plan_amount').value = amount;
    document.getElementById('display_plan_type').value = plan;
    document.getElementById('display_plan_amount').value = '₱' + amount;

    // Hide the subscription modal and show the payment modal
    document.getElementById('subscriptionModal').classList.add('hidden');
    document.getElementById('paymentModal').classList.remove('hidden');
}

// PAYMENT METHOD QR CODE TOGGLE
const paymentMethodSelect = document.querySelector('select[name="payment_method"]');
const gcashQRCodeDiv = document.getElementById('gcashQRCode');
const paypalQRCodeDiv = document.getElementById('paypalQRCode');

if(paymentMethodSelect) {
    paymentMethodSelect.addEventListener('change', () => {
        if (paymentMethodSelect.value === 'gcash') {
            gcashQRCodeDiv.classList.remove('hidden');
            paypalQRCodeDiv.classList.add('hidden');
        } else if (paymentMethodSelect.value === 'paypal') {
            paypalQRCodeDiv.classList.remove('hidden');
            gcashQRCodeDiv.classList.add('hidden');
        } else {
            gcashQRCodeDiv.classList.add('hidden');
            paypalQRCodeDiv.classList.add('hidden');
        }
    });
}
</script>

<script>
document.getElementById('subscriptionForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    formData.set('plan_type', document.getElementById('plan_type').value);
    formData.set('plan_amount', document.getElementById('plan_amount').value);

    fetch('government_subscription.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            document.getElementById('paymentModal').classList.add('hidden');
            form.reset();
        }
    })
    .catch(err => {
        console.error('Submission error:', err);
        alert('Something went wrong.');
    });
});
</script>
</body>
</html>
