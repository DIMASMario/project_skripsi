<?= $this->extend('admin/layout/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="mb-4">
        <h2 class="mb-1"><i class="bi bi-plus-circle text-primary"></i> Buat Kode Registrasi</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('admin/kode-registrasi') ?>">Kode Registrasi</a></li>
                <li class="breadcrumb-item active">Buat Kode</li>
            </ol>
        </nav>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-key-fill"></i> Form Pembuatan Kode Registrasi</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= base_url('admin/kode-registrasi/create') ?>">
                        <?= csrf_field() ?>

                        <!-- RT -->
                        <div class="mb-3">
                            <label for="rt" class="form-label fw-bold">
                                <i class="bi bi-house"></i> RT <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control <?= $validation->hasError('rt') ? 'is-invalid' : '' ?>" 
                                   id="rt" name="rt" value="<?= old('rt') ?>" placeholder="Contoh: 03" maxlength="3" required>
                            <?php if ($validation->hasError('rt')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('rt') ?></div>
                            <?php endif; ?>
                            <small class="text-muted">Masukkan nomor RT (1-999)</small>
                        </div>

                        <!-- RW -->
                        <div class="mb-3">
                            <label for="rw" class="form-label fw-bold">
                                <i class="bi bi-houses"></i> RW <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control <?= $validation->hasError('rw') ? 'is-invalid' : '' ?>" 
                                   id="rw" name="rw" value="<?= old('rw') ?>" placeholder="Contoh: 02" maxlength="3" required>
                            <?php if ($validation->hasError('rw')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('rw') ?></div>
                            <?php endif; ?>
                            <small class="text-muted">Masukkan nomor RW (1-999)</small>
                        </div>

                        <!-- Jumlah Kode -->
                        <div class="mb-3">
                            <label for="jumlah" class="form-label fw-bold">
                                <i class="bi bi-123"></i> Jumlah Kode yang Dibuat <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control <?= $validation->hasError('jumlah') ? 'is-invalid' : '' ?>" 
                                   id="jumlah" name="jumlah" value="<?= old('jumlah', 1) ?>" min="1" max="100" required>
                            <?php if ($validation->hasError('jumlah')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('jumlah') ?></div>
                            <?php endif; ?>
                            <small class="text-muted">Maksimal 100 kode per batch. Nomor urut akan dilanjutkan secara otomatis.</small>
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-4">
                            <label for="keterangan" class="form-label fw-bold">
                                <i class="bi bi-chat-text"></i> Keterangan (Opsional)
                            </label>
                            <textarea class="form-control <?= $validation->hasError('keterangan') ? 'is-invalid' : '' ?>" 
                                      id="keterangan" name="keterangan" rows="3" placeholder="Catatan tambahan..."><?= old('keterangan') ?></textarea>
                            <?php if ($validation->hasError('keterangan')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('keterangan') ?></div>
                            <?php endif; ?>
                            <small class="text-muted">Contoh: Untuk warga baru bulan Februari 2026</small>
                        </div>

                        <!-- Preview Kode -->
                        <div class="alert alert-info" id="preview-kode" style="display: none;">
                            <h6 class="mb-2"><i class="bi bi-eye"></i> Preview Format Kode:</h6>
                            <div class="p-3 bg-white border rounded text-center">
                                <code style="font-size: 1.2rem; font-weight: bold;" id="preview-text">BLK-RT00RW00-0001</code>
                            </div>
                            <small class="text-muted d-block mt-2">Nomor urut akan dilanjutkan otomatis</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('admin/kode-registrasi') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Buat Kode
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm bg-light">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-info-circle-fill text-primary"></i> Informasi</h5>
                    <hr>
                    <h6 class="mb-2">Format Kode Registrasi:</h6>
                    <div class="p-3 bg-white border rounded text-center mb-3">
                        <code style="font-size: 1.1rem; font-weight: bold;">BLK-RT03RW02-0007</code>
                    </div>
                    
                    <h6 class="mb-2">Penjelasan:</h6>
                    <ul class="small">
                        <li><strong>BLK</strong> = Kode Desa Blanakan</li>
                        <li><strong>RT03</strong> = RT 03</li>
                        <li><strong>RW02</strong> = RW 02</li>
                        <li><strong>0007</strong> = Nomor urut warga ke-7</li>
                    </ul>
                    
                    <hr>
                    
                    <h6 class="mb-2">Catatan Penting:</h6>
                    <ul class="small">
                        <li>Kode bersifat <strong>unik</strong> dan hanya dapat digunakan <strong>sekali</strong></li>
                        <li>Nomor urut akan <strong>otomatis dilanjutkan</strong> dari kode terakhir di RT/RW tersebut</li>
                        <li>Kode yang dibuat akan berstatus "Belum Digunakan"</li>
                        <li>Cetak kode untuk distribusi ke warga</li>
                        <li>Warga harus memasukkan kode saat registrasi online</li>
                    </ul>
                    
                    <hr>
                    
                    <h6 class="mb-2">Tips:</h6>
                    <ul class="small mb-0">
                        <li>Buat kode secukupnya sesuai kebutuhan</li>
                        <li>Catat keterangan untuk memudahkan tracking</li>
                        <li>Bagikan kode kepada warga yang sudah terverifikasi identitasnya</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rtInput = document.getElementById('rt');
    const rwInput = document.getElementById('rw');
    const previewDiv = document.getElementById('preview-kode');
    const previewText = document.getElementById('preview-text');
    
    function updatePreview() {
        const rt = rtInput.value || '00';
        const rw = rwInput.value || '00';
        
        if (rt && rw) {
            const rtFormatted = rt.padStart(2, '0');
            const rwFormatted = rw.padStart(2, '0');
            previewText.textContent = `BLK-RT${rtFormatted}RW${rwFormatted}-0001`;
            previewDiv.style.display = 'block';
        } else {
            previewDiv.style.display = 'none';
        }
    }
    
    rtInput.addEventListener('input', updatePreview);
    rwInput.addEventListener('input', updatePreview);
    
    // Initial preview if old values exist
    if (rtInput.value || rwInput.value) {
        updatePreview();
    }
});
</script>

<?= $this->endSection() ?>
