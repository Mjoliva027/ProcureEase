<?php
include('../includes/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'government') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>View Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-2xl font-bold text-amber-600 mb-6">Suggested Products</h1>

<div id="productContainer" class="space-y-8">
    <!-- Products will be loaded here by AJAX -->
</div>

<script>
// Fetch products and render them
async function fetchProducts() {
    try {
        const response = await fetch('government_fetch_products.php');
        const products = await response.json();

        const container = document.getElementById('productContainer');
        container.innerHTML = '';

        if (products.error) {
            container.innerHTML = `<p class="text-red-600">${products.error}</p>`;
            return;
        }

        products.forEach(product => {
            const imagePath = product.images.length > 0 ? product.images[0] : null;
            const productHtml = `
                <div class="bg-white rounded-xl shadow-md p-4 max-w-[1000px] mx-auto">
                    <div class="mb-2">
                        <p class="text-base text-gray-700">${escapeHtml(product.product_description)}</p>
                    </div>
                    <div class="mb-4">
                        ${imagePath ? 
                            `<img src="../uploads/${escapeHtml(imagePath)}" class="w-full rounded-lg object-cover max-h-[300px]" alt="Product Image">` : 
                            `<div class="bg-gray-200 text-gray-500 text-center py-20 rounded-lg">No Image Available</div>`
                        }
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-bold">${escapeHtml(product.product_name)}</h2>
                            <p class="text-green-600 text-lg font-semibold">₱${Number(product.product_price).toFixed(2)}</p>
                        </div>
                        <a href="government_order_product.php?product_id=${product.product_id}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                           Order Now
                        </a>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', productHtml);
        });

    } catch (error) {
        document.getElementById('productContainer').innerHTML = `<p class="text-red-600">Error loading products.</p>`;
        console.error(error);
    }
}

// Helper to escape HTML to avoid XSS
function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/[&<>"']/g, function(m) {
        return ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        })[m];
    });
}

// Load products on page load
fetchProducts();
</script>

</body>
</html>