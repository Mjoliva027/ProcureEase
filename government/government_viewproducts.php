<?php
include('../includes/db_connect.php');

// Check if user is authorized
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'government') {
    header('Location: login.php');
    exit();
}

// Fetch products for government user
$government_id = $_SESSION['user_id'];

// Query to fetch all the products posted by suppliers
$query = "
    SELECT 
        p.product_id,
        p.product_name,
        p.product_description,
        p.product_price,
        pi.image_path
    FROM 
        products AS p
    LEFT JOIN 
        product_images AS pi ON pi.product_id = p.product_id
    ORDER BY 
        p.created_at DESC
";

$suggestions_result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$suggestions_result) {
    die('Query failed: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Posted Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold text-amber-600 mb-4">Posted Products</h1>
    <div id="productContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>

    <div class="mt-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while($row = mysqli_fetch_assoc($suggestions_result)): ?>
                <div class="bg-white p-4 rounded shadow">
                    <!-- Display Product Images -->
                    <?php if (!empty($row['image_path'])): ?>
                        <div class="mb-4">
                            <img src="../uploads/<?= htmlspecialchars($row['image_path']) ?>" class="w-full h-40 object-cover rounded" alt="Product Image">
                        </div>
                    <?php endif; ?>

                    <h3 class="text-xl font-semibold"><?= htmlspecialchars($row['product_name']) ?></h3>
                    <p class="text-gray-600"><?= htmlspecialchars($row['product_description']) ?></p>
                    <p class="mt-2 text-green-600 font-bold">₱<?= number_format($row['product_price'], 2) ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script>
    fetch('government_fetch_products.php')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('productContainer');
            if (data.error) {
                container.innerHTML = `<p class="text-red-600">${data.error}</p>`;
                return;
            }
            data.forEach(product => {
                const images = product.images.map(img =>
                    `<img src="${img}" class="w-full h-40 object-cover rounded mb-2" alt="Product Image">`
                ).join('');

                container.innerHTML += `
                    <div class="bg-white p-4 rounded shadow">
                        ${images}
                        <h3 class="text-lg font-semibold">${product.product_name}</h3>
                        <p class="text-gray-600">${product.product_description}</p>
                        <p class="mt-2 text-green-600 font-bold">₱${parseFloat(product.product_price).toFixed(2)}</p>
                    </div>
                `;
            });
        })
        .catch(error => {
            document.getElementById('productContainer').innerHTML = 
                `<p class="text-red-600">Failed to load products</p>`;
            console.error(error);
        });
    </script>
</body>
</html>
