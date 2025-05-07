<?php
// Fetch products
$pendingProducts = $conn->query("SELECT p.product_id, p.product_name, g.agency_name, p.created_at
    FROM products p
    JOIN governments g ON p.user_id = g.user_id
    ORDER BY p.created_at DESC
    LIMIT 10");
?>

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
