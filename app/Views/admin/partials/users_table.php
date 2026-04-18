<div class="table-container overflow-x-auto">
    <table class="w-full relative">
        <thead>
            <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                <th class="text-left py-3 px-6 w-12">
                    <input type="checkbox" id="selectAll" 
                           class="rounded border-gray-300 dark:border-gray-600 text-primary focus:ring-primary">
                </th>
                <th class="text-left py-3 px-6 font-medium text-gray-500 dark:text-gray-400">Pengguna</th>
                <th class="text-left py-3 px-6 font-medium text-gray-500 dark:text-gray-400">Kontak</th>
                <th class="text-left py-3 px-6 font-medium text-gray-500 dark:text-gray-400">Status</th>
                <th class="text-left py-3 px-6 font-medium text-gray-500 dark:text-gray-400">Tanggal Daftar</th>
                <th class="text-left py-3 px-6 font-medium text-gray-500 dark:text-gray-400 w-40">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="6" class="py-12 px-6 text-center">
                        <div class="text-gray-500 dark:text-gray-400">
                            <span class="material-symbols-outlined text-4xl mb-2 block">group</span>
                            <p>
                                <?php 
                                $status = $_GET['status'] ?? '';
                                if ($status === 'pending') {
                                    echo 'Belum ada user yang menunggu verifikasi';
                                } elseif ($status === 'suspended') {
                                    echo 'Belum ada user yang di-suspend';
                                } elseif ($status === 'approved') {
                                    echo 'Belum ada user yang disetujui';
                                } else {
                                    echo 'Belum ada pengguna terdaftar';
                                }
                                ?>
                            </p>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr class="user-row border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="py-3 px-6">
                            <input type="checkbox" value="<?= $user['id'] ?? '' ?>" 
                                   class="rounded border-gray-300 dark:border-gray-600 text-primary focus:ring-primary">
                        </td>
                        <td class="py-3 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-medium">
                                    <?= strtoupper(substr($user['nama_lengkap'] ?? $user['nama'] ?? 'U', 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white user-name">
                                        <?= esc($user['nama_lengkap'] ?? $user['nama'] ?? 'Tidak diketahui') ?>
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 user-nik">
                                        NIK: <?= esc($user['nik'] ?? 'Tidak diketahui') ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-6">
                            <div class="text-sm">
                                <div class="text-gray-900 dark:text-white mb-1 flex items-center gap-1">
                                    <?= esc($user['email'] ?? 'Tidak ada email') ?>
                                </div>
                                <?php if (!empty($user['no_hp'])): ?>
                                    <div class="text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">phone</span>
                                        <?= esc($user['no_hp']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="py-3 px-6">
                            <?php 
                            $status = $user['status'] ?? 'menunggu';
                            $badgeClass = '';
                            $textClass = '';
                            $icon = '';
                            
                            switch ($status) {
                                case 'disetujui':
                                    $badgeClass = 'bg-green-100 dark:bg-green-900/20';
                                    $textClass = 'text-green-800 dark:text-green-200';
                                    $icon = 'check_circle';
                                    break;
                                case 'ditolak':
                                    $badgeClass = 'bg-red-100 dark:bg-red-900/20';
                                    $textClass = 'text-red-800 dark:text-red-200';
                                    $icon = 'cancel';
                                    break;
                                case 'suspended':
                                    $badgeClass = 'bg-gray-100 dark:bg-gray-900/20';
                                    $textClass = 'text-gray-800 dark:text-gray-200';
                                    $icon = 'block';
                                    break;
                                default:
                                    $badgeClass = 'bg-yellow-100 dark:bg-yellow-900/20';
                                    $textClass = 'text-yellow-800 dark:text-yellow-200';
                                    $icon = 'schedule';
                                    $status = 'menunggu';
                            }
                            ?>
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium <?= $badgeClass ?> <?= $textClass ?>">
                                <span class="material-symbols-outlined text-xs"><?= $icon ?></span>
                                <?= ucfirst($status) ?>
                            </span>
                        </td>
                        <td class="py-3 px-6">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900 dark:text-white">
                                    <?= date('d/m/Y', strtotime($user['created_at'] ?? 'now')) ?>
                                </div>
                                <div class="text-gray-500 dark:text-gray-400">
                                    <?= date('H:i', strtotime($user['created_at'] ?? 'now')) ?> WIB
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-6">
                            <div class="flex items-center gap-2">
                                <!-- Detail Button -->
                                <button type="button" 
                                        class="inline-flex items-center justify-center w-8 h-8 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors"
                                        data-bs-toggle="modal" data-bs-target="#detailModal<?= $user['id'] ?? 0 ?>"
                                        title="Lihat Detail">
                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                </button>
                                
                                <?php if (($user['status'] ?? 'menunggu') === 'menunggu'): ?>
                                    <!-- Approve Button -->
                                    <button type="button" 
                                            class="inline-flex items-center justify-center w-8 h-8 text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/20 rounded-lg transition-colors"
                                            onclick="approveUser(<?= $user['id'] ?? 0 ?>)"
                                            title="Setujui">
                                        <span class="material-symbols-outlined text-sm">check_circle</span>
                                    </button>
                                    
                                    <!-- Reject Button -->
                                    <button type="button" 
                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                            onclick="rejectUser(<?= $user['id'] ?? 0 ?>)"
                                            title="Tolak">
                                        <span class="material-symbols-outlined text-sm">cancel</span>
                                    </button>
                                <?php endif; ?>
                                
                                <!-- More Actions Dropdown -->
                                <div class="dropdown-container relative inline-block">
                                    <button type="button" onclick="toggleDropdown(this, <?= $user['id'] ?? 0 ?>)" 
                                            class="dropdown-toggle inline-flex items-center justify-center w-8 h-8 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                        <span class="material-symbols-outlined text-sm">more_vert</span>
                                    </button>
                                    
                                    <div id="dropdown-<?= $user['id'] ?? 0 ?>" class="dropdown-menu hidden absolute top-full right-0 mt-1 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-[9999] max-h-64 overflow-y-auto">
                                        <div class="py-1">
                                            <a href="#" onclick="editUser(<?= $user['id'] ?? 0 ?>); closeDropdown(<?= $user['id'] ?? 0 ?>)" 
                                               class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                                                <span class="material-symbols-outlined text-sm mr-3 w-4 h-4 flex-shrink-0">edit</span>
                                                <span>Edit User</span>
                                            </a>
                                            <a href="#" onclick="resetPassword(<?= $user['id'] ?? 0 ?>); closeDropdown(<?= $user['id'] ?? 0 ?>)" 
                                               class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                                                <span class="material-symbols-outlined text-sm mr-3 w-4 h-4 flex-shrink-0">key</span>
                                                <span>Reset Password</span>
                                            </a>
                                            <div class="border-t border-gray-200 dark:border-gray-600 my-1"></div>
                                            <a href="#" onclick="deleteUser(<?= $user['id'] ?? 0 ?>); closeDropdown(<?= $user['id'] ?? 0 ?>)" 
                                               class="flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150">
                                                <span class="material-symbols-outlined text-sm mr-3 w-4 h-4 flex-shrink-0">delete</span>
                                                <span>Hapus User</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Detail Modal -->
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50" 
                         id="detailModal<?= $user['id'] ?? 0 ?>">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
                            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    <span class="material-symbols-outlined mr-2">person</span>
                                    Detail Pengguna
                                </h3>
                                <button type="button" onclick="closeModal('detailModal<?= $user['id'] ?? 0 ?>')"
                                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                    <span class="material-symbols-outlined">close</span>
                                </button>
                            </div>
                            
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <h4 class="font-medium text-primary mb-3 flex items-center">
                                            <span class="material-symbols-outlined mr-2 text-sm">badge</span>
                                            Data Pribadi
                                        </h4>
                                        <div class="space-y-3">
                                            <div>
                                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Lengkap</label>
                                                <p class="text-gray-900 dark:text-white"><?= esc($user['nama_lengkap'] ?? $user['nama'] ?? '-') ?></p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">NIK</label>
                                                <p class="text-gray-900 dark:text-white"><?= esc($user['nik'] ?? '-') ?></p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Tempat Lahir</label>
                                                <p class="text-gray-900 dark:text-white"><?= esc($user['tempat_lahir'] ?? '-') ?></p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Lahir</label>
                                                <p class="text-gray-900 dark:text-white"><?= esc($user['tanggal_lahir'] ?? '-') ?></p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Kelamin</label>
                                                <p class="text-gray-900 dark:text-white"><?= esc($user['jenis_kelamin'] ?? '-') ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <h4 class="font-medium text-green-600 dark:text-green-400 mb-3 flex items-center">
                                            <span class="material-symbols-outlined mr-2 text-sm">contact_mail</span>
                                            Kontak & Alamat
                                        </h4>
                                        <div class="space-y-3">
                                            <div>
                                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                                                <p class="text-gray-900 dark:text-white"><?= esc($user['email'] ?? '-') ?></p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">No. HP</label>
                                                <p class="text-gray-900 dark:text-white"><?= esc($user['no_hp'] ?? '-') ?></p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</label>
                                                <p class="text-gray-900 dark:text-white"><?= esc($user['alamat'] ?? '-') ?></p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">RT/RW</label>
                                                <p class="text-gray-900 dark:text-white"><?= esc($user['rt_rw'] ?? '-') ?></p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Desa/Kelurahan</label>
                                                <p class="text-gray-900 dark:text-white"><?= esc($user['desa'] ?? '-') ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Status & Waktu Info -->
                                <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                                    <h4 class="font-medium text-blue-600 dark:text-blue-400 mb-3 flex items-center">
                                        <span class="material-symbols-outlined mr-2 text-sm">info</span>
                                        Status & Informasi Akun
                                    </h4>
                                    <div class="flex flex-wrap gap-3">
                                        <?php 
                                        $status = $user['status'] ?? 'menunggu';
                                        $badgeClass = '';
                                        $textClass = '';
                                        $icon = '';
                                        
                                        switch ($status) {
                                            case 'disetujui':
                                                $badgeClass = 'bg-green-100 dark:bg-green-900/20';
                                                $textClass = 'text-green-800 dark:text-green-200';
                                                $icon = 'check_circle';
                                                break;
                                            case 'ditolak':
                                                $badgeClass = 'bg-red-100 dark:bg-red-900/20';
                                                $textClass = 'text-red-800 dark:text-red-200';
                                                $icon = 'cancel';
                                                break;
                                            default:
                                                $badgeClass = 'bg-yellow-100 dark:bg-yellow-900/20';
                                                $textClass = 'text-yellow-800 dark:text-yellow-200';
                                                $icon = 'schedule';
                                                $status = 'menunggu';
                                        }
                                        ?>
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium <?= $badgeClass ?> <?= $textClass ?>">
                                            <span class="material-symbols-outlined text-xs"><?= $icon ?></span>
                                            Status: <?= ucfirst($status) ?>
                                        </span>
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                            <span class="material-symbols-outlined text-xs">calendar_today</span>
                                            Daftar: <?= date('d/m/Y H:i', strtotime($user['created_at'] ?? 'now')) ?> WIB
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Modal Footer -->
                            <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700">
                                <?php if (($user['status'] ?? 'menunggu') === 'menunggu'): ?>
                                    <button type="button" onclick="approveUser(<?= $user['id'] ?? 0 ?>)"
                                            class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                        <span class="material-symbols-outlined mr-2 text-sm">check_circle</span>
                                        Setujui
                                    </button>
                                    <button type="button" onclick="rejectUser(<?= $user['id'] ?? 0 ?>)"
                                            class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                        <span class="material-symbols-outlined mr-2 text-sm">cancel</span>
                                        Tolak
                                    </button>
                                <?php endif; ?>
                                <button type="button" onclick="closeModal('detailModal<?= $user['id'] ?? 0 ?>')"
                                        class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                                    <span class="material-symbols-outlined mr-2 text-sm">close</span>
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Custom CSS for Dropdown -->
<style>
.dropdown-container {
    position: relative;
    display: inline-block;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    z-index: 9999;
    min-width: 12rem;
    max-height: 16rem;
    overflow-y: auto;
    background-color: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    margin-top: 0.25rem;
}

.dark .dropdown-menu {
    background-color: #374151;
    border-color: #4b5563;
}

.dropdown-menu.hidden {
    display: none;
}

.dropdown-menu a {
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    text-decoration: none;
    transition: background-color 0.15s ease-in-out;
}

.dropdown-menu a:hover {
    background-color: #f3f4f6;
}

.dark .dropdown-menu a:hover {
    background-color: #4b5563;
}

/* Ensure table overflow doesn't hide dropdown */
.table-container {
    position: relative;
    overflow: visible !important;
}

/* Override table cell overflow */
td {
    overflow: visible !important;
}

/* Responsive behavior */
@media (max-width: 768px) {
    .dropdown-menu {
        right: -50%;
        min-width: 10rem;
    }
}
</style>

<script>
let currentOpenDropdown = null;

function toggleDropdown(button, userId) {
    const dropdownId = `dropdown-${userId}`;
    const dropdown = document.getElementById(dropdownId);
    
    // Close any currently open dropdown
    if (currentOpenDropdown && currentOpenDropdown !== dropdown) {
        currentOpenDropdown.classList.add('hidden');
    }
    
    // Toggle current dropdown
    if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden');
        currentOpenDropdown = dropdown;
        
        // Position dropdown properly
        positionDropdown(button, dropdown);
    } else {
        dropdown.classList.add('hidden');
        currentOpenDropdown = null;
    }
}

function closeDropdown(userId) {
    const dropdown = document.getElementById(`dropdown-${userId}`);
    if (dropdown) {
        dropdown.classList.add('hidden');
        currentOpenDropdown = null;
    }
}

function positionDropdown(button, dropdown) {
    // Get button position
    const buttonRect = button.getBoundingClientRect();
    const dropdownRect = dropdown.getBoundingClientRect();
    const viewportWidth = window.innerWidth;
    const viewportHeight = window.innerHeight;
    
    // Reset position classes
    dropdown.classList.remove('right-0', 'left-0');
    
    // Check if dropdown would go off screen horizontally
    if (buttonRect.right - dropdownRect.width < 0) {
        dropdown.classList.add('left-0');
    } else {
        dropdown.classList.add('right-0');
    }
    
    // Check if dropdown would go off screen vertically
    const spaceBelow = viewportHeight - buttonRect.bottom;
    const spaceAbove = buttonRect.top;
    
    if (spaceBelow < dropdownRect.height && spaceAbove > spaceBelow) {
        // Show dropdown above the button
        dropdown.style.top = 'auto';
        dropdown.style.bottom = '100%';
        dropdown.style.marginBottom = '0.25rem';
        dropdown.style.marginTop = '0';
    } else {
        // Show dropdown below the button (default)
        dropdown.style.top = '100%';
        dropdown.style.bottom = 'auto';
        dropdown.style.marginTop = '0.25rem';
        dropdown.style.marginBottom = '0';
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown-container')) {
        if (currentOpenDropdown) {
            currentOpenDropdown.classList.add('hidden');
            currentOpenDropdown = null;
        }
    }
});

// Close dropdown on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && currentOpenDropdown) {
        currentOpenDropdown.classList.add('hidden');
        currentOpenDropdown = null;
    }
});

function approveUser(userId) {
    if (confirm('Yakin ingin menyetujui pengguna ini?')) {
        // Create a form to submit as POST
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= base_url('admin/users/verifikasi/') ?>${userId}`;
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);
        
        // Add action parameter
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'setujui';
        form.appendChild(actionInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function rejectUser(userId) {
    if (confirm('Yakin ingin menolak pengguna ini?')) {
        // Create a form to submit as POST
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= base_url('admin/users/verifikasi/') ?>${userId}`;
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);
        
        // Add action parameter
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'tolak';
        form.appendChild(actionInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function editUser(userId) {
    window.location.href = `<?= base_url('admin/users/edit/') ?>${userId}`;
}

function resetPassword(userId) {
    if (confirm('Yakin ingin reset password pengguna ini?\nPassword akan direset menjadi "123456"')) {
        // Show loading
        const button = event.target.closest('a');
        const originalText = button.innerHTML;
        button.innerHTML = '<span class="material-symbols-outlined text-sm mr-3 w-4 h-4 flex-shrink-0 animate-spin">sync</span><span>Mereset...</span>';
        button.style.pointerEvents = 'none';
        
        fetch(`<?= base_url('admin/users/reset-password/') ?>${userId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message + '\n\nPassword baru telah dikirim ke notifikasi user.');
                location.reload();
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan saat reset password');
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.style.pointerEvents = 'auto';
        });
    }
}

function deleteUser(userId) {
    if (confirm('⚠️ Yakin ingin menghapus pengguna ini?\n\n❗ PERINGATAN:\n- Tindakan ini tidak dapat dibatalkan\n- User tidak akan bisa login lagi\n- Riwayat aktivitas akan hilang\n\nLanjutkan menghapus?')) {
        // Show loading
        const button = event.target.closest('a');
        const originalText = button.innerHTML;
        button.innerHTML = '<span class="material-symbols-outlined text-sm mr-3 w-4 h-4 flex-shrink-0 animate-spin">sync</span><span>Menghapus...</span>';
        button.style.pointerEvents = 'none';
        
        // Get fresh CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?= csrf_hash() ?>';
        
        console.log('Deleting user ID:', userId);
        
        fetch(`<?= base_url('admin/users/delete/') ?>${userId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ userId: userId })
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Response is not JSON');
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success) {
                alert('✅ ' + data.message);
                // Remove the table row with animation
                const row = button.closest('tr');
                row.style.opacity = '0';
                row.style.transition = 'opacity 0.3s';
                setTimeout(() => {
                    row.remove();
                    // Check if table is empty and reload if needed
                    const tbody = document.querySelector('tbody');
                    if (tbody.children.length === 0) {
                        location.reload();
                    }
                }, 300);
            } else {
                // Check if force delete is available
                if (data.can_force_delete && data.surat_count > 0) {
                    const forceConfirm = confirm(
                        `❌ ${data.message}\n\n⚠️ OPSI FORCE DELETE:\n` + 
                        `User ini memiliki ${data.surat_count} dokumen surat.\n\n` +
                        `🚨 PERINGATAN KERAS:\n` +
                        `- Semua riwayat surat akan TERHAPUS PERMANEN!\n` +
                        `- Data tidak dapat dikembalikan!\n` +
                        `- Melanggar prosedur audit pemerintahan!\n\n` +
                        `Tetap lanjutkan FORCE DELETE?`
                    );
                    
                    if (forceConfirm) {
                        // Perform force delete
                        fetch(`<?= base_url('admin/users/delete/') ?>${userId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({ userId: userId, force: true })
                        })
                        .then(response => response.json())
                        .then(forceData => {
                            if (forceData.success) {
                                alert('⚠️ FORCE DELETE berhasil!\n\n' + forceData.message + '\n\nSemua riwayat surat user telah dihapus!');
                                location.reload();
                            } else {
                                alert('❌ Force delete gagal: ' + forceData.message);
                            }
                        });
                    }
                } else {
                    alert('❌ ' + (data.message || 'Gagal menghapus user'));
                }
                
                button.innerHTML = originalText;
                button.style.pointerEvents = 'auto';
            }
        })
        .catch(error => {
            console.error('Delete user error:', error);
            alert('❌ Terjadi kesalahan: ' + error.message);
            button.innerHTML = originalText;
            button.style.pointerEvents = 'auto';
        });
    }
}

// Modal functions
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Open modal when clicking detail button
document.addEventListener('click', function(e) {
    if (e.target.closest('[data-bs-toggle="modal"]')) {
        const modalTarget = e.target.closest('[data-bs-toggle="modal"]').getAttribute('data-bs-target');
        const modalId = modalTarget.replace('#', '');
        document.getElementById(modalId).classList.remove('hidden');
    }
});

// Select All functionality with Bulk Actions
const selectAllCheckbox = document.getElementById('selectAll');
if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActionsVisibility();
    });
}

// Individual checkbox handlers
document.addEventListener('change', function(e) {
    if (e.target.matches('tbody input[type="checkbox"]')) {
        updateBulkActionsVisibility();
        updateSelectAllState();
    }
});

// Update bulk actions toolbar visibility
function updateBulkActionsVisibility() {
    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]:checked');
    const toolbar = document.getElementById('bulkActionsToolbar');
    const selectedCount = document.getElementById('selectedCount');
    
    if (checkboxes.length > 0) {
        toolbar.classList.remove('hidden');
        selectedCount.textContent = checkboxes.length;
    } else {
        toolbar.classList.add('hidden');
    }
}

// Update select all checkbox state
function updateSelectAllState() {
    const allCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    const checkedCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]:checked');
    
    if (selectAllCheckbox) {
        if (checkedCheckboxes.length === 0) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        } else if (checkedCheckboxes.length === allCheckboxes.length) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = true;
        }
    }
}

// Get selected user IDs
function getSelectedUserIds() {
    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]:checked');
    const userIds = Array.from(checkboxes).map(cb => cb.value);
    console.log('🔍 Selected checkboxes:', checkboxes.length);
    console.log('📋 Selected user IDs:', userIds);
    return userIds;
}

// Clear selection
function clearSelection() {
    document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    updateBulkActionsVisibility();
}

// Bulk Status Change
function bulkChangeStatus(status) {
    console.log('🔄 bulkChangeStatus() called with status:', status);
    
    const userIds = getSelectedUserIds();
    console.log('📋 Users selected for status change:', userIds);
    
    if (userIds.length === 0) {
        console.warn('⚠️ No users selected for status change');
        alert('❌ Tidak ada user yang dipilih');
        return;
    }

    const statusLabels = {
        'disetujui': 'Disetujui',
        'suspended': 'Suspended',
        'menunggu': 'Menunggu Verifikasi',
        'ditolak': 'Ditolak'
    };

    if (confirm(`⚠️ Yakin ingin mengubah status ${userIds.length} user menjadi "${statusLabels[status]}"?`)) {
        console.log('✅ User confirmed status change');
        
        // Show loading - find button by event target or closest button
        const btn = event.target.closest('button') || document.getElementById('bulkStatusBtn');
        if (!btn) {
            console.error('❌ Status change button not found');
            alert('❌ Error: Button not found');
            return;
        }
        
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-outlined text-sm mr-2 animate-spin">sync</span>Mengubah...';
        btn.disabled = true;

        const bulkStatusUrl = '<?= base_url('admin/users/bulk-status') ?>';
        console.log('📡 Bulk status URL:', bulkStatusUrl);
        
        // Get fresh CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?= csrf_hash() ?>';
        console.log('🔐 CSRF Token:', csrfToken);
        
        fetch(bulkStatusUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                userIds: userIds,
                status: status
            })
        })
        .then(response => {
            console.log('📊 Bulk status response:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('✅ Bulk status response data:', data);
            
            if (data.success) {
                alert('✅ ' + data.message);
                location.reload();
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(error => {
            console.error('💥 Bulk status error:', error);
            alert('❌ Terjadi kesalahan saat mengubah status: ' + error.message);
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
    
    // Close dropdown
    document.getElementById('bulkStatusDropdown').classList.add('hidden');
}

// Bulk Delete
function bulkDelete() {
    console.log('🗑️ bulkDelete() function called');
    
    const userIds = getSelectedUserIds();
    console.log('📦 Users selected for bulk delete:', userIds);
    
    if (userIds.length === 0) {
        console.warn('⚠️ No users selected for bulk delete');
        alert('❌ Tidak ada user yang dipilih');
        return;
    }

    if (confirm(`⚠️ PERINGATAN!\n\nYakin ingin menghapus ${userIds.length} user?\n\n❗ Tindakan ini TIDAK DAPAT DIBATALKAN!\n- User yang dihapus tidak akan bisa login lagi\n- Data dan riwayat akan hilang permanen\n- User dengan riwayat surat tidak akan dihapus\n\nLanjutkan menghapus?`)) {
        // Show loading
        const btn = event.target.closest('button');
        if (!btn) {
            console.error('❌ Bulk delete button not found');
            alert('❌ Error: Button not found');
            return;
        }
        
        console.log('📦 Starting bulk delete for users:', userIds);
        
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-outlined text-sm mr-2 animate-spin">sync</span>Menghapus...';
        btn.disabled = true;

        const bulkUrl = '<?= base_url('admin/users/bulk-delete') ?>';
        console.log('📡 Bulk delete URL:', bulkUrl);
        
        // Get fresh CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?= csrf_hash() ?>';
        console.log('🔐 CSRF Token:', csrfToken);

        fetch(bulkUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                userIds: userIds
            })
        })
        .then(response => {
            console.log('📊 Bulk delete response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('✅ Bulk delete response data:', data);
            
            if (data.success) {
                let message = '✅ ' + data.message;
                if (data.errors && data.errors.length > 0) {
                    message += '\n\n❌ Errors:\n' + data.errors.join('\n');
                }
                alert(message);
                location.reload();
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(error => {
            console.error('💥 Bulk delete error:', error);
            alert('❌ Terjadi kesalahan saat menghapus users: ' + error.message);
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
}

// Bulk Export
function bulkExport() {
    const userIds = getSelectedUserIds();
    
    if (userIds.length === 0) {
        alert('❌ Tidak ada user yang dipilih');
        return;
    }

    // Show loading
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="material-symbols-outlined text-sm mr-2 animate-spin">sync</span>Mengexport...';
    btn.disabled = true;

    fetch('<?= base_url('admin/users/bulk-export') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        },
        body: JSON.stringify({
            userIds: userIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message + '\n\nFile akan didownload otomatis...');
            
            // Auto download
            const link = document.createElement('a');
            link.href = data.download_url;
            link.download = data.filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            clearSelection();
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan saat export data');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

// Bulk Status Dropdown Toggle
document.addEventListener('DOMContentLoaded', function() {
    const bulkStatusBtn = document.getElementById('bulkStatusBtn');
    const bulkStatusDropdown = document.getElementById('bulkStatusDropdown');
    
    if (bulkStatusBtn && bulkStatusDropdown) {
        console.log('✅ Bulk status elements found, adding event listeners');
        
        bulkStatusBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('🔽 Bulk status dropdown toggle clicked');
            bulkStatusDropdown.classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#bulkStatusBtn') && !e.target.closest('#bulkStatusDropdown')) {
                bulkStatusDropdown.classList.add('hidden');
            }
        });
    } else {
        console.error('❌ Bulk status elements not found:', { 
            bulkStatusBtn: !!bulkStatusBtn, 
            bulkStatusDropdown: !!bulkStatusDropdown 
        });
    }
});
</script>