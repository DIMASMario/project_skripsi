<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Keamanan Sistem</h1>
        <button onclick="refreshSecurityStatus()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <i class="material-icons text-sm mr-2">refresh</i>
            Refresh Status
        </button>
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

    <!-- Security Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="material-icons text-green-600">security</i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-500">Security Score</p>
                    <p class="text-2xl font-semibold text-gray-900"><?= $security_score ?? 85 ?>%</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="material-icons text-blue-600">shield</i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-500">Failed Logins</p>
                    <p class="text-2xl font-semibold text-gray-900"><?= $failed_logins_today ?? 12 ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="material-icons text-yellow-600">warning</i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-500">Threats Blocked</p>
                    <p class="text-2xl font-semibold text-gray-900"><?= $threats_blocked ?? 3 ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="material-icons text-red-600">block</i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-500">Blocked IPs</p>
                    <p class="text-2xl font-semibold text-gray-900"><?= count($blocked_ips ?? []) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Security Settings -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                <i class="material-icons text-gray-600 mr-2">settings_applications</i>
                Pengaturan Keamanan
            </h2>
            
            <form id="security-settings-form" class="space-y-4">
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="two_factor_auth" <?= ($settings['two_factor_auth'] ?? false) ? 'checked' : '' ?>
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Aktifkan Two-Factor Authentication</span>
                    </label>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="login_attempt_limit" <?= ($settings['login_attempt_limit'] ?? true) ? 'checked' : '' ?>
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Batasi Percobaan Login</span>
                    </label>
                </div>

                <div>
                    <label for="max_login_attempts" class="block text-sm font-medium text-gray-700 mb-2">Maksimal Percobaan Login</label>
                    <input type="number" id="max_login_attempts" name="max_login_attempts" 
                           value="<?= $settings['max_login_attempts'] ?? 5 ?>" min="3" max="10"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="session_timeout" class="block text-sm font-medium text-gray-700 mb-2">Session Timeout (menit)</label>
                    <input type="number" id="session_timeout" name="session_timeout" 
                           value="<?= $settings['session_timeout'] ?? 60 ?>" min="15" max="480"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="ip_whitelist_enabled" <?= ($settings['ip_whitelist_enabled'] ?? false) ? 'checked' : '' ?>
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Aktifkan IP Whitelist untuk Admin</span>
                    </label>
                </div>

                <div>
                    <label for="allowed_ips" class="block text-sm font-medium text-gray-700 mb-2">IP yang Diizinkan</label>
                    <textarea id="allowed_ips" name="allowed_ips" rows="3" 
                              placeholder="192.168.1.1&#10;203.0.113.0"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?= implode("\n", $settings['allowed_ips'] ?? []) ?></textarea>
                    <p class="text-sm text-gray-500 mt-1">Satu IP per baris</p>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="force_https" <?= ($settings['force_https'] ?? false) ? 'checked' : '' ?>
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Paksa menggunakan HTTPS</span>
                    </label>
                </div>

                <div class="pt-4 border-t">
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="material-icons text-sm mr-2">save</i>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        <!-- Blocked IPs -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-medium text-gray-900">
                    <i class="material-icons text-gray-600 mr-2">block</i>
                    IP yang Diblokir
                </h2>
                <button onclick="openBlockIPModal()" class="text-sm bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700">
                    <i class="material-icons text-xs mr-1">add</i>
                    Blokir IP
                </button>
            </div>
            
            <div class="space-y-2 max-h-96 overflow-y-auto">
                <?php if (!empty($blocked_ips)): ?>
                    <?php foreach ($blocked_ips as $ip): ?>
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-md">
                            <div>
                                <div class="text-sm font-medium text-gray-900"><?= esc($ip['ip_address']) ?></div>
                                <div class="text-xs text-gray-500">
                                    Diblokir: <?= date('d/m/Y H:i', strtotime($ip['blocked_at'])) ?>
                                </div>
                                <?php if ($ip['reason']): ?>
                                    <div class="text-xs text-gray-600"><?= esc($ip['reason']) ?></div>
                                <?php endif; ?>
                            </div>
                            <button onclick="unblockIP('<?= esc($ip['ip_address']) ?>')" 
                                    class="text-red-600 hover:text-red-900">
                                <i class="material-icons text-sm">delete</i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-gray-500 py-8">
                        <i class="material-icons text-4xl text-gray-300 mb-2">block</i>
                        <p>Tidak ada IP yang diblokir</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Security Logs -->
    <div class="bg-white shadow rounded-lg overflow-hidden mt-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">
                <i class="material-icons text-gray-600 mr-2">event_note</i>
                Log Keamanan
            </h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Agent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($security_logs)): ?>
                        <?php foreach ($security_logs as $log): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= esc($log['event']) ?></div>
                                    <?php if ($log['description']): ?>
                                        <div class="text-sm text-gray-500"><?= esc($log['description']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= esc($log['ip_address']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="max-w-32 truncate">
                                        <?= esc($log['user_agent']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        <?php 
                                        switch($log['severity']) {
                                            case 'high': echo 'bg-red-100 text-red-800'; break;
                                            case 'medium': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'low': echo 'bg-green-100 text-green-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?= ucfirst(esc($log['severity'])) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="material-icons text-4xl text-gray-300 mb-2">event_note</i>
                                    <p>Belum ada log keamanan</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Block IP Modal -->
<div id="block-ip-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Blokir IP Address</h3>
            <button onclick="closeBlockIPModal()" class="text-gray-400 hover:text-gray-600">
                <i class="material-icons">close</i>
            </button>
        </div>
        
        <form id="block-ip-form" class="space-y-4">
            <div>
                <label for="ip-address" class="block text-sm font-medium text-gray-700 mb-2">IP Address</label>
                <input type="text" id="ip-address" name="ip_address" required 
                       placeholder="192.168.1.1"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <div>
                <label for="block-reason" class="block text-sm font-medium text-gray-700 mb-2">Alasan</label>
                <textarea id="block-reason" name="reason" rows="3" 
                          placeholder="Alasan memblokir IP ini..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" onclick="closeBlockIPModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Blokir IP
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openBlockIPModal() {
    document.getElementById('block-ip-modal').style.display = 'flex';
}

function closeBlockIPModal() {
    document.getElementById('block-ip-modal').style.display = 'none';
    document.getElementById('block-ip-form').reset();
}

function unblockIP(ipAddress) {
    if (!confirm(`Yakin ingin menghapus blokir untuk IP ${ipAddress}?`)) return;
    
    fetch('<?= base_url('admin/security/unblock-ip') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ ip_address: ipAddress })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('IP berhasil dihapus dari daftar blokir!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus blokir IP');
    });
}

function refreshSecurityStatus() {
    fetch('<?= base_url('admin/security/refresh-status') ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat refresh status');
    });
}

// Handle security settings form
document.getElementById('security-settings-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const settingsData = {};
    
    formData.forEach((value, key) => {
        if (key === 'allowed_ips') {
            settingsData[key] = value.split('\n').filter(ip => ip.trim());
        } else {
            settingsData[key] = value;
        }
    });
    
    // Handle checkboxes
    const checkboxes = this.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        settingsData[checkbox.name] = checkbox.checked;
    });
    
    fetch('<?= base_url('admin/security/update-settings') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(settingsData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Pengaturan keamanan berhasil disimpan!');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan pengaturan');
    });
});

// Handle block IP form
document.getElementById('block-ip-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const blockData = {};
    formData.forEach((value, key) => {
        blockData[key] = value;
    });
    
    fetch('<?= base_url('admin/security/block-ip') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(blockData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('IP berhasil diblokir!');
            closeBlockIPModal();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memblokir IP');
    });
});
</script>
<?= $this->endsection() ?>