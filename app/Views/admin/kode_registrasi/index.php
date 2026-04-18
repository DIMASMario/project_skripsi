<?= $this->extend('admin/layout/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-key-fill text-primary"></i> Manajemen Kode Registrasi</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Kode Registrasi</li>
                </ol>
            </nav>
        </div>
        <a href="<?= base_url('admin/kode-registrasi/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Buat Kode Baru
        </a>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Total Kode</h6>
                            <h3 class="mb-0"><?= number_format($statistik['total']) ?></h3>
                        </div>
                        <div class="text-primary">
                            <i class="bi bi-key" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Belum Digunakan</h6>
                            <h3 class="mb-0"><?= number_format($statistik['belum_digunakan']) ?></h3>
                        </div>
                        <div class="text-success">
                            <i class="bi bi-check-circle" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Sudah Digunakan</h6>
                            <h3 class="mb-0"><?= number_format($statistik['sudah_digunakan']) ?></h3>
                        </div>
                        <div class="text-info">
                            <i class="bi bi-person-check" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Kadaluarsa</h6>
                            <h3 class="mb-0"><?= number_format($statistik['kadaluarsa']) ?></h3>
                        </div>
                        <div class="text-warning">
                            <i class="bi bi-clock-history" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter dan Tabel -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-3">Daftar Kode Registrasi</h5>
            
            <!-- Filter Form -->
            <form method="get" action="<?= base_url('admin/kode-registrasi') ?>" class="row g-3">
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="belum_digunakan" <?= ($filters['status'] ?? '') === 'belum_digunakan' ? 'selected' : '' ?>>Belum Digunakan</option>
                        <option value="sudah_digunakan" <?= ($filters['status'] ?? '') === 'sudah_digunakan' ? 'selected' : '' ?>>Sudah Digunakan</option>
                        <option value="kadaluarsa" <?= ($filters['status'] ?? '') === 'kadaluarsa' ? 'selected' : '' ?>>Kadaluarsa</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="rt" class="form-control" placeholder="RT" value="<?= $filters['rt'] ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <input type="text" name="rw" class="form-control" placeholder="RW" value="<?= $filters['rw'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari kode..." value="<?= $filters['search'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filter</button>
                    <a href="<?= base_url('admin/kode-registrasi') ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Reset</a>
                    <a href="<?= base_url('admin/kode-registrasi/export') . '?' . http_build_query($filters) ?>" class="btn btn-success"><i class="bi bi-download"></i> Export</a>
                </div>
            </form>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="20%">Kode Registrasi</th>
                            <th width="8%">RT</th>
                            <th width="8%">RW</th>
                            <th width="15%">Status</th>
                            <th width="15%">Dibuat</th>
                            <th width="15%">Digunakan</th>
                            <th width="14%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($kode_list)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                    <p class="mt-2 mb-0">Tidak ada data kode registrasi</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($kode_list as $index => $kode): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><code style="font-size: 1rem;"><?= esc($kode['kode_registrasi']) ?></code></td>
                                    <td><?= str_pad($kode['rt'], 2, '0', STR_PAD_LEFT) ?></td>
                                    <td><?= str_pad($kode['rw'], 2, '0', STR_PAD_LEFT) ?></td>
                                    <td>
                                        <?php
                                        $statusBadge = [
                                            'belum_digunakan' => '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Belum Digunakan</span>',
                                            'sudah_digunakan' => '<span class="badge bg-info"><i class="bi bi-person-check"></i> Sudah Digunakan</span>',
                                            'kadaluarsa' => '<span class="badge bg-warning"><i class="bi bi-clock-history"></i> Kadaluarsa</span>'
                                        ];
                                        echo $statusBadge[$kode['status']] ?? '<span class="badge bg-secondary">Unknown</span>';
                                        ?>
                                    </td>
                                    <td><small><?= date('d/m/Y H:i', strtotime($kode['created_at'])) ?></small></td>
                                    <td>
                                        <?php if ($kode['used_at']): ?>
                                            <small><?= date('d/m/Y H:i', strtotime($kode['used_at'])) ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('admin/kode-registrasi/detail/' . $kode['id']) ?>" class="btn btn-sm btn-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <?php if ($kode['status'] === 'belum_digunakan'): ?>
                                            <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="<?= $kode['id'] ?>" data-kode="<?= esc($kode['kode_registrasi']) ?>" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete kode registrasi
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const kode = this.dataset.kode;
            
            if (confirm(`Yakin ingin menghapus kode ${kode}?`)) {
                fetch(`<?= base_url('admin/kode-registrasi/delete/') ?>${id}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
