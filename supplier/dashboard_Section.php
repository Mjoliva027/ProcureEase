
    <h2 class="text-3xl font-bold mb-6 text-amber-500">Dashboard</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-lg shadow flex items-center space-x-4">
            <div class="bg-amber-500 p-3 rounded-full">
                <i data-feather="box" class="text-white"></i>
            </div>
            <div>
                <h4 class="text-lg font-semibold">Total Products</h4>
                <p class="text-gray-600" id="totalProductCount">Loading...</p>
                
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow flex items-center space-x-4">
            <div class="bg-amber-500 p-3 rounded-full">
                <i data-feather="user-check" class="text-white"></i>
            </div>
            <div>
                <h4 class="text-lg font-semibold">Profile Status</h4>
                <p class="text-gray-600">Complete</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow flex items-center space-x-4">
            <div class="bg-amber-500 p-3 rounded-full">
                <i data-feather="activity" class="text-white"></i>
            </div>
            <div>
                <h4 class="text-lg font-semibold">Total Sales</h4>
                <p class="text-gray-600">1123</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow flex items-center space-x-4">
            <div class="bg-amber-500 p-3 rounded-full">
                <i data-feather="shopping-cart" class="text-white"></i>
            </div>
            <div>
                <h4 class="text-lg font-semibold">New Orders</h4>
                <p class="text-gray-600">5 new orders</p>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow mb-10">
        <h3 class="text-2xl font-bold mb-4">Recent Activities</h3>
        <ul class="space-y-4">
            <li class="flex items-center space-x-4">
                <i data-feather="plus-circle" class="text-amber-500"></i>
                <span>Added new product <strong>Wireless Mouse</strong>.</span>
            </li>
            <li class="flex items-center space-x-4">
                <i data-feather="edit" class="text-amber-500"></i>
                <span>Updated price for <strong>Office Chair</strong>.</span>
            </li>
            <li class="flex items-center space-x-4">
                <i data-feather="check-circle" class="text-amber-500"></i>
                <span>Completed order #2345.</span>
            </li>
        </ul>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-bold mb-4">Quick Actions</h3>
        <div class="flex space-x-4">
            <a href="#" class="flex items-center bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-lg shadow transition">
                <i data-feather="plus" class="mr-2"></i> Add Product
            </a>
            <a href="#" class="flex items-center bg-gray-800 hover:bg-gray-900 text-white px-5 py-3 rounded-lg shadow transition">
                <i data-feather="refresh-cw" class="mr-2"></i> Update Stock
            </a>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
    fetch('total_products.php')
        .then(res => res.json())
        .then(data => {
            const totalCount = data.total ?? 0;
            document.getElementById('totalProductCount').textContent = 
                `You have ${totalCount} product${totalCount !== 1 ? 's' : ''}`;
        })
        .catch(err => {
            console.error('Failed to load total products:', err);
            document.getElementById('totalProductCount').textContent = 
                'Failed to load count';
        });
});
</script>
