
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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-10">
    <!-- Left: Order Status -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-bold mb-4">Latest Order Status</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-gray-100 rounded-lg">
                <span class="font-medium">Order #2345</span>
                <span class="text-green-600 font-semibold">Received by Government</span>
            </div>
            <div class="flex items-center justify-between p-4 bg-gray-100 rounded-lg">
                <span class="font-medium">Order #2346</span>
                <span class="text-yellow-500 font-semibold">In Transit</span>
            </div>
            <div class="flex items-center justify-between p-4 bg-gray-100 rounded-lg">
                <span class="font-medium">Order #2347</span>
                <span class="text-red-500 font-semibold">Pending Dispatch</span>
            </div>
        </div>
    </div>

    <!-- Right: Sales Chart -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-bold mb-4">Sales Overview</h3>
        <canvas id="salesChart" height="100"></canvas>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('salesChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Total Sales',
                data: [1500, 1800, 1250, 2000, 2300, 1900], // Replace with dynamic data if needed
                backgroundColor: 'rgba(251, 191, 36, 0.2)',
                borderColor: 'rgba(251, 191, 36, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                pointRadius: 5,
                pointBackgroundColor: 'rgba(251, 191, 36, 1)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Sales (₱)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                }
            }
        }
    });
});
</script>
