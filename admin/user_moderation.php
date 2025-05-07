<?php
// Fetch pending users
$pendingUsers = $conn->query("SELECT id, name, email, role FROM users WHERE is_new = 1");
?>

<section>
    <h3 class="text-2xl font-semibold mb-4">User & Content Moderation</h3>
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Role</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = $pendingUsers->fetch_assoc()): ?>
                <tr class="border-b">
                    <td class="px-4 py-2"><?= htmlspecialchars($user['name']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($user['email']) ?></td>
                    <td class="px-4 py-2"><?= ucfirst($user['role']) ?></td>
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
