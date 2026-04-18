<?php $this->extend('admin/layouts/main') ?>

<?php $this->section('content') ?>
<div class="container-fluid px-6 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <?php 
                $status = $_GET['status'] ?? '';
                if ($status === 'pending') {
                    echo 'User Pending Verifikasi';
                } elseif ($status === 'suspended') {
                    echo 'User Suspended';
                } else {
                    echo 'Manajemen User';
                }
                ?>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                <?php 
                $status = $_GET['status'] ?? '';
                if ($status === 'pending') {
                    echo 'Manajemen Warga > User Pending Verifikasi';
                } elseif ($status === 'suspended') {
                    echo 'Manajemen Warga > User Suspended';
                } else {
                    echo 'Manajemen Warga > Daftar Warga';
                }
                ?>
            </p>
        </div>
        
        <!-- Stats Cards -->
        <div class="flex gap-4">
            <div class="bg-blue-50 dark:bg-blue-900/20 px-4 py-2 rounded-lg">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-sm">group</span>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Total User</div>
                        <div class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                            <?= count($users ?? []) ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-50 dark:bg-yellow-900/20 px-4 py-2 rounded-lg">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-sm">schedule</span>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Menunggu</div>
                        <div class="text-lg font-semibold text-yellow-600 dark:text-yellow-400">
                            <?= count(array_filter($users ?? [], fn($u) => ($u['status'] ?? 'menunggu') === 'menunggu')) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <?php $currentStatus = $_GET['status'] ?? ''; ?>
                    
                    <a href="<?= base_url('admin/users') ?>" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?= empty($currentStatus) ? 'bg-primary text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' ?>">
                        <span class="material-symbols-outlined text-sm mr-2">group</span>
                        Semua User
                    </a>
                    
                    <a href="<?= base_url('admin/users?status=pending') ?>" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?= $currentStatus === 'pending' ? 'bg-yellow-500 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' ?>">
                        <span class="material-symbols-outlined text-sm mr-2">schedule</span>
                        Menunggu Verifikasi
                    </a>
                    
                    <a href="<?= base_url('admin/users?status=approved') ?>" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?= $currentStatus === 'approved' ? 'bg-green-500 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' ?>">
                        <span class="material-symbols-outlined text-sm mr-2">check_circle</span>
                        Disetujui
                    </a>
                    
                    <a href="<?= base_url('admin/users?status=suspended') ?>" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?= $currentStatus === 'suspended' ? 'bg-red-500 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' ?>">
                        <span class="material-symbols-outlined text-sm mr-2">block</span>
                        Suspended
                    </a>
                </div>
                
                <!-- Search Box -->
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <input type="text" id="searchUser" 
                               class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm"
                               placeholder="Cari nama atau NIK...">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm">search</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Toolbar (Hidden by default) -->
    <div id="bulkActionsToolbar" class="hidden bg-primary/10 border border-primary/20 rounded-lg p-4 mb-4 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-primary">checklist</span>
                <span class="text-primary font-medium">
                    <span id="selectedCount">0</span> user dipilih
                </span>
            </div>
            
            <div class="flex items-center gap-2">
                <!-- Bulk Status Change -->
                <div class="relative">
                    <button type="button" id="bulkStatusBtn" 
                            class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-medium transition-colors">
                        <span class="material-symbols-outlined text-sm mr-2">swap_horiz</span>
                        Ubah Status
                        <span class="material-symbols-outlined text-sm ml-1">arrow_drop_down</span>
                    </button>
                    
                    <div id="bulkStatusDropdown" class="hidden absolute top-full right-0 mt-1 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50">
                        <div class="py-1">
                            <button type="button" onclick="bulkChangeStatus('disetujui')" 
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                                <span class="material-symbols-outlined text-sm mr-3 text-green-600">check_circle</span>
                                Setujui Semua
                            </button>
                            <button type="button" onclick="bulkChangeStatus('suspended')" 
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                                <span class="material-symbols-outlined text-sm mr-3 text-yellow-600">block</span>
                                Suspend Semua
                            </button>
                            <button type="button" onclick="bulkChangeStatus('menunggu')" 
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                                <span class="material-symbols-outlined text-sm mr-3 text-gray-600">schedule</span>
                                Set Menunggu
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Bulk Export -->
                <button type="button" onclick="bulkExport()" 
                        class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm font-medium transition-colors">
                    <span class="material-symbols-outlined text-sm mr-2">download</span>
                    Export Data
                </button>
                
                <!-- Bulk Delete -->
                <button type="button" onclick="bulkDelete()" 
                        class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-medium transition-colors">
                    <span class="material-symbols-outlined text-sm mr-2">delete</span>
                    Hapus Dipilih
                </button>
                
                <!-- Clear Selection -->
                <button type="button" onclick="clearSelection()" 
                        class="inline-flex items-center px-3 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm transition-colors">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                <?php 
                $status = $_GET['status'] ?? '';
                if ($status === 'pending') {
                    echo 'Daftar User Menunggu Verifikasi';
                } elseif ($status === 'suspended') {
                    echo 'Daftar User Suspended';
                } elseif ($status === 'approved') {
                    echo 'Daftar User Disetujui';
                } else {
                    echo 'Semua Daftar User';
                }
                ?>
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Kelola dan verifikasi pengguna yang mendaftar di sistem
            </p>
        </div>
        
        <div class="p-6">
            <?php 
            // Filter users based on status
            $status = $_GET['status'] ?? '';
            $filteredUsers = $users ?? [];
            
            if ($status === 'pending') {
                $filteredUsers = array_filter($users ?? [], fn($u) => ($u['status'] ?? 'menunggu') === 'menunggu');
            } elseif ($status === 'suspended') {
                $filteredUsers = array_filter($users ?? [], fn($u) => ($u['status'] ?? '') === 'suspended');
            } elseif ($status === 'approved') {
                $filteredUsers = array_filter($users ?? [], fn($u) => ($u['status'] ?? '') === 'disetujui');
            }
            ?>
            
            <?= $this->include('admin/partials/users_table', ['users' => $filteredUsers, 'filter' => $status ?? 'all']) ?>
        </div>
    </div>

    <?php if ($status === 'pending' && !empty($filteredUsers)): ?>
    <!-- Bulk Actions for Pending Users -->
    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400">info</span>
                <div>
                    <h4 class="font-medium text-yellow-800 dark:text-yellow-200">Aksi Cepat Verifikasi</h4>
                    <p class="text-sm text-yellow-700 dark:text-yellow-300">Pilih user dan lakukan verifikasi massal</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" id="selectAllBtn" class="px-3 py-1 text-xs bg-yellow-100 dark:bg-yellow-800 text-yellow-700 dark:text-yellow-200 rounded-md hover:bg-yellow-200 dark:hover:bg-yellow-700 transition-colors">
                    Pilih Semua
                </button>
                <button type="button" id="bulkApproveBtn" class="px-3 py-1 text-xs bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors" disabled>
                    Setujui Terpilih
                </button>
                <button type="button" id="bulkRejectBtn" class="px-3 py-1 text-xs bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors" disabled>
                    Tolak Terpilih
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script>
    // Search functionality
    document.getElementById('searchUser').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.user-row');
        
        rows.forEach(row => {
            const nameElement = row.querySelector('.user-name');
            const nikElement = row.querySelector('.user-nik');
            
            if (nameElement && nikElement) {
                const name = nameElement.textContent.toLowerCase();
                const nik = nikElement.textContent.toLowerCase();
                
                if (name.includes(searchTerm) || nik.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });

    // Bulk actions for pending users
    <?php if ($status === 'pending'): ?>
    const selectAllBtn = document.getElementById('selectAllBtn');
    const bulkApproveBtn = document.getElementById('bulkApproveBtn');
    const bulkRejectBtn = document.getElementById('bulkRejectBtn');
    
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][value]:not([id="selectAll"])');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(cb => {
                cb.checked = !allChecked;
            });
            
            updateBulkButtons();
        });
    }

    // Update bulk button states
    function updateBulkButtons() {
        const checkedBoxes = document.querySelectorAll('input[type="checkbox"][value]:checked:not([id="selectAll"])');
        const hasSelection = checkedBoxes.length > 0;
        
        if (bulkApproveBtn) bulkApproveBtn.disabled = !hasSelection;
        if (bulkRejectBtn) bulkRejectBtn.disabled = !hasSelection;
    }

    // Listen to checkbox changes
    document.addEventListener('change', function(e) {
        if (e.target.type === 'checkbox' && e.target.value) {
            updateBulkButtons();
        }
    });

    // Bulk approve
    if (bulkApproveBtn) {
        bulkApproveBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('input[type="checkbox"][value]:checked:not([id="selectAll"])');
            const userIds = Array.from(checkedBoxes).map(cb => cb.value);
            
            if (userIds.length > 0) {
                if (confirm(`Yakin ingin menyetujui ${userIds.length} user terpilih?`)) {
                    // Implement bulk approval logic here
                    console.log('Bulk approve:', userIds);
                }
            }
        });
    }

    // Bulk reject
    if (bulkRejectBtn) {
        bulkRejectBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('input[type="checkbox"][value]:checked:not([id="selectAll"])');
            const userIds = Array.from(checkedBoxes).map(cb => cb.value);
            
            if (userIds.length > 0) {
                if (confirm(`Yakin ingin menolak ${userIds.length} user terpilih?`)) {
                    // Implement bulk rejection logic here
                    console.log('Bulk reject:', userIds);
                }
            }
        });
    }
    <?php endif; ?>
</script>
<?php $this->endSection() ?>