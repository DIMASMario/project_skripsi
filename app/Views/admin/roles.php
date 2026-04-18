<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Manajemen Role & Permission</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola role dan hak akses pengguna</p>
            </div>
            <button onclick="openRoleModal()" 
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg transition-colors font-medium">
                <span class="material-symbols-outlined text-xl">add</span>
                Tambah Role
            </button>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Roles List -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">admin_panel_settings</span>
                    Daftar Role
                </h2>
            </div>
            
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                <?php if (!empty($roles)): ?>
                    <?php foreach ($roles as $role): ?>
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white"><?= esc($role['name']) ?></h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2"><?= esc($role['description']) ?></p>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-base">person</span>
                                            <?= $role['user_count'] ?? 0 ?> pengguna
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-base">security</span>
                                            <?= count($role['permissions'] ?? []) ?> permission
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button onclick="editRole(<?= $role['id'] ?>)" 
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        <span class="material-symbols-outlined">edit</span>
                                    </button>
                                    <?php if ($role['name'] !== 'Administrator'): ?>
                                    <button onclick="deleteRole(<?= $role['id'] ?>)" 
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Role Permissions Preview -->
                            <?php if (!empty($role['permissions'])): ?>
                                <div class="mt-3 flex flex-wrap gap-1">
                                    <?php foreach (array_slice($role['permissions'], 0, 5) as $permission): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                            <?= esc($permission) ?>
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if (count($role['permissions']) > 5): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400">
                                            +<?= count($role['permissions']) - 5 ?> lainnya
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-2">admin_panel_settings</span>
                        <p>Belum ada role yang dibuat</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Permissions List -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400">security</span>
                    Daftar Permission
                </h2>
            </div>
            
            <div class="p-6">
                <?php if (!empty($permissions)): ?>
                    <?php 
                    $groupedPermissions = [];
                    foreach ($permissions as $permission) {
                        $module = explode('.', $permission['name'])[0];
                        $groupedPermissions[$module][] = $permission;
                    }
                    ?>
                    
                    <?php foreach ($groupedPermissions as $module => $modulePermissions): ?>
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-900 mb-3 uppercase tracking-wide">
                                <?= ucfirst($module) ?>
                            </h3>
                            <div class="space-y-2">
                                <?php foreach ($modulePermissions as $permission): ?>
                                    <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-md">
                                        <div>
                                            <span class="text-sm font-medium text-gray-900">
                                                <?= esc(str_replace($module . '.', '', $permission['name'])) ?>
                                            </span>
                                            <p class="text-xs text-gray-500"><?= esc($permission['description']) ?></p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <?= $permission['usage_count'] ?? 0 ?> role
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-gray-500 py-8">
                        <i class="material-icons text-4xl text-gray-300 mb-2">security</i>
                        <p>Belum ada permission yang terdaftar</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Users by Role -->
    <div class="bg-white shadow rounded-lg overflow-hidden mt-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">
                <i class="material-icons text-gray-600 mr-2">people</i>
                Pengguna Berdasarkan Role
            </h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Login</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">
                                                <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                            </span>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900"><?= esc($user['name']) ?></div>
                                            <div class="text-sm text-gray-500"><?= esc($user['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <?= esc($user['role_name']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        <?= $user['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= $user['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'Belum pernah' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="changeUserRole(<?= $user['id'] ?>)" 
                                            class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="material-icons text-sm">admin_panel_settings</i>
                                    </button>
                                    <button onclick="toggleUserStatus(<?= $user['id'] ?>)" 
                                            class="text-<?= $user['is_active'] ? 'red' : 'green' ?>-600 hover:text-<?= $user['is_active'] ? 'red' : 'green' ?>-900">
                                        <i class="material-icons text-sm"><?= $user['is_active'] ? 'block' : 'check_circle' ?></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="material-icons text-4xl text-gray-300 mb-2">people</i>
                                    <p>Belum ada pengguna</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Role Modal -->
<div id="role-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-96 overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900" id="modal-title">Tambah Role</h3>
            <button onclick="closeRoleModal()" class="text-gray-400 hover:text-gray-600">
                <i class="material-icons">close</i>
            </button>
        </div>
        
        <form id="role-form" class="space-y-4">
            <input type="hidden" id="role-id" name="id">
            
            <div>
                <label for="role-name" class="block text-sm font-medium text-gray-700 mb-2">Nama Role</label>
                <input type="text" id="role-name" name="name" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="role-description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea id="role-description" name="description" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Permissions</label>
                <div id="permissions-list" class="space-y-3 max-h-64 overflow-y-auto border border-gray-200 rounded-md p-3">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" onclick="closeRoleModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const permissions = <?= json_encode($permissions) ?>;

function openRoleModal(roleId = null) {
    document.getElementById('role-modal').style.display = 'flex';
    
    if (roleId) {
        document.getElementById('modal-title').textContent = 'Edit Role';
        // Load role data
        loadRoleData(roleId);
    } else {
        document.getElementById('modal-title').textContent = 'Tambah Role';
        document.getElementById('role-form').reset();
        document.getElementById('role-id').value = '';
    }
    
    loadPermissions();
}

function closeRoleModal() {
    document.getElementById('role-modal').style.display = 'none';
}

function loadPermissions() {
    const container = document.getElementById('permissions-list');
    container.innerHTML = '';
    
    // Group permissions by module
    const groupedPermissions = {};
    permissions.forEach(permission => {
        const module = permission.name.split('.')[0];
        if (!groupedPermissions[module]) {
            groupedPermissions[module] = [];
        }
        groupedPermissions[module].push(permission);
    });
    
    // Create permission checkboxes
    Object.keys(groupedPermissions).forEach(module => {
        const moduleDiv = document.createElement('div');
        moduleDiv.className = 'mb-3';
        
        moduleDiv.innerHTML = `
            <h4 class="text-sm font-medium text-gray-900 mb-2 capitalize">${module}</h4>
            <div class="space-y-1">
                ${groupedPermissions[module].map(permission => `
                    <label class="flex items-center">
                        <input type="checkbox" name="permissions[]" value="${permission.id}" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">${permission.name.replace(module + '.', '')}</span>
                    </label>
                `).join('')}
            </div>
        `;
        
        container.appendChild(moduleDiv);
    });
}

function loadRoleData(roleId) {
    fetch(`<?= base_url('admin/roles/get') ?>/${roleId}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const role = data.role;
            document.getElementById('role-id').value = role.id;
            document.getElementById('role-name').value = role.name;
            document.getElementById('role-description').value = role.description;
            
            // Check permissions
            role.permissions.forEach(permissionId => {
                const checkbox = document.querySelector(`input[name="permissions[]"][value="${permissionId}"]`);
                if (checkbox) checkbox.checked = true;
            });
        }
    });
}

function editRole(roleId) {
    openRoleModal(roleId);
}

function deleteRole(roleId) {
    if (!confirm('Yakin ingin menghapus role ini?')) return;
    
    fetch('<?= base_url('admin/roles/delete') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ id: roleId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Role berhasil dihapus!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

function changeUserRole(userId) {
    // Implementation for changing user role
    // This would open another modal or dropdown
}

function toggleUserStatus(userId) {
    if (!confirm('Yakin ingin mengubah status pengguna ini?')) return;
    
    fetch('<?= base_url('admin/users/toggle-status') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ id: userId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

// Handle role form submission
document.getElementById('role-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const roleData = {};
    
    formData.forEach((value, key) => {
        if (key === 'permissions[]') {
            if (!roleData.permissions) roleData.permissions = [];
            roleData.permissions.push(value);
        } else {
            roleData[key] = value;
        }
    });
    
    const url = roleData.id ? '<?= base_url('admin/roles/update') ?>' : '<?= base_url('admin/roles/create') ?>';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(roleData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Role berhasil disimpan!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
});
</script>
<?= $this->endsection() ?>