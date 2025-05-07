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
