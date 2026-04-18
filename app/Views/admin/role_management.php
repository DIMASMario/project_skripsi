<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Manajemen Role Admin</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola role dan hak akses administrator</p>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex items-center">
                <i class="material-icons mr-2">check_circle</i>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex items-center">
                <i class="material-icons mr-2">error</i>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="material-icons text-blue-600">admin_panel_settings</i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total Admin</p>
                    <p class="text-2xl font-bold text-gray-900"><?= count($admins) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="material-icons text-green-600">verified_user</i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Admin Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">
                        <?= count(array_filter($admins, fn($a) => ($a['status'] ?? '') === 'approved')) ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="material-icons text-purple-600">people</i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Role Admin</p>
                    <p class="text-2xl font-bold text-gray-900">
                        <?= count(array_filter($admins, fn($a) => ($a['role'] ?? '') === 'admin')) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Daftar Administrator</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Admin
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Role
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Bergabung
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($admins)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data administrator
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($admins as $admin): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold">
                                                <?= strtoupper(substr($admin['username'] ?? $admin['email'] ?? 'A', 0, 1)) ?>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= esc($admin['username'] ?? $admin['email']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?= esc($admin['email'] ?? '') ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?= ($admin['role'] ?? '') === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' ?>">
                                        <i class="material-icons text-xs mr-1">
                                            <?= ($admin['role'] ?? '') === 'admin' ? 'admin_panel_settings' : 'person' ?>
                                        </i>
                                        <?= ucfirst($admin['role'] ?? 'unknown') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        <?php
                                            $status = $admin['status'] ?? 'pending';
                                            if ($status === 'approved') echo 'bg-green-100 text-green-800';
                                            elseif ($status === 'rejected') echo 'bg-red-100 text-red-800';
                                            else echo 'bg-yellow-100 text-yellow-800';
                                        ?>">
                                        <?= ucfirst($status) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('d M Y', strtotime($admin['created_at'] ?? 'now')) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <?php if (($admin['id'] ?? 0) != session()->get('user_id')): ?>
                                        <button onclick="changeRole(<?= $admin['id'] ?>, '<?= esc($admin['username'] ?? $admin['email']) ?>')" 
                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="material-icons text-sm align-middle">edit</i>
                                            Ubah Role
                                        </button>
                                    <?php else: ?>
                                        <span class="text-gray-400 text-xs">Anda sendiri</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="material-icons text-blue-400">info</i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Tentang Role Management</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li><strong>Admin:</strong> Memiliki akses penuh ke seluruh sistem</li>
                        <li><strong>Warga:</strong> Hanya bisa mengajukan surat dan melihat berita</li>
                        <li>Anda tidak dapat mengubah role Anda sendiri</li>
                        <li>Perubahan role akan berlaku setelah user login ulang</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubah Role -->
<div id="roleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ubah Role Administrator</h3>
        
        <form id="roleForm" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" id="adminId" name="admin_id">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Administrator: <strong id="adminName"></strong>
                </label>
            </div>

            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Role Baru
                </label>
                <select id="role" name="role" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="admin">Admin</option>
                    <option value="warga">Warga</option>
                </select>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal()" 
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    <i class="material-icons text-sm align-middle mr-1">save</i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function changeRole(id, name) {
    document.getElementById('adminId').value = id;
    document.getElementById('adminName').textContent = name;
    document.getElementById('roleForm').action = `<?= base_url('admin/role-management/assign/') ?>${id}`;
    document.getElementById('roleModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('roleModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('roleModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

<?= $this->endsection() ?>
