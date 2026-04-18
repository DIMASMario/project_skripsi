<div class="galeri-container row" id="galeriContainer">
    <?php if (empty($galeri)): ?>
        <div class="col-12">
            <div class="text-center py-5">
                <div class="d-flex flex-column align-items-center">
                    <i class="fas fa-images text-muted mb-3" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mb-2">Belum ada media di galeri</h5>
                    <p class="text-muted mb-3">Tambahkan foto atau video pertama untuk memulai galeri</p>
                    <a href="<?= base_url('admin/tambah_galeri') ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Media Pertama
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($galeri as $item): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4 galeri-item">
                <div class="card h-100 shadow-sm">
                    <div class="position-relative">
                        <?php if (($item['jenis'] ?? 'foto') === 'foto'): ?>
                            <img src="<?= base_url('uploads/galeri/' . ($item['file'] ?? 'default.jpg')) ?>" 
                                 class="card-img-top" alt="<?= esc($item['judul'] ?? '') ?>"
                                 style="height: 200px; object-fit: cover;" 
                                 data-bs-toggle="modal" data-bs-target="#viewModal<?= $item['id'] ?? 0 ?>">
                        <?php else: ?>
                            <div class="bg-dark d-flex align-items-center justify-content-center" style="height: 200px;">
                                <div class="text-center text-white">
                                    <i class="fas fa-play-circle fs-1 mb-2"></i>
                                    <div>Video</div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Type Badge -->
                        <div class="position-absolute top-0 start-0 m-2">
                            <?php if (($item['jenis'] ?? 'foto') === 'foto'): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-image me-1"></i>Foto
                                </span>
                            <?php else: ?>
                                <span class="badge bg-warning">
                                    <i class="fas fa-video me-1"></i>Video
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Action Dropdown -->
                        <div class="position-absolute top-0 end-0 m-2">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" 
                                           data-bs-target="#viewModal<?= $item['id'] ?? 0 ?>">
                                            <i class="fas fa-eye me-2"></i>Lihat
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?= base_url('admin/edit_galeri/' . ($item['id'] ?? '')) ?>">
                                            <i class="fas fa-edit me-2"></i>Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="downloadMedia(<?= $item['id'] ?? 0 ?>)">
                                            <i class="fas fa-download me-2"></i>Download
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" onclick="hapusGaleri(<?= $item['id'] ?? 0 ?>)">
                                            <i class="fas fa-trash me-2"></i>Hapus
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h6 class="card-title galeri-judul mb-2">
                            <?= esc(substr($item['judul'] ?? 'Tanpa Judul', 0, 40)) ?>
                            <?= strlen($item['judul'] ?? '') > 40 ? '...' : '' ?>
                        </h6>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="badge bg-info galeri-kategori">
                                <?= esc($item['kategori'] ?? 'Umum') ?>
                            </small>
                            <small class="text-muted">
                                <?= date('d/m/Y', strtotime($item['created_at'] ?? 'now')) ?>
                            </small>
                        </div>
                        
                        <?php if (!empty($item['deskripsi'])): ?>
                            <p class="card-text text-muted small">
                                <?= esc(substr($item['deskripsi'], 0, 60)) ?>
                                <?= strlen($item['deskripsi']) > 60 ? '...' : '' ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-footer bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary" 
                                        data-bs-toggle="modal" data-bs-target="#viewModal<?= $item['id'] ?? 0 ?>">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="<?= base_url('admin/edit_galeri/' . ($item['id'] ?? '')) ?>" 
                                   class="btn btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="hapusGaleri(<?= $item['id'] ?? 0 ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>
                                <?= number_format($item['views'] ?? 0) ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- View Modal -->
            <div class="modal fade" id="viewModal<?= $item['id'] ?? 0 ?>" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-<?= ($item['jenis'] ?? 'foto') === 'foto' ? 'image' : 'video' ?> me-2"></i>
                                <?= esc($item['judul'] ?? 'Media') ?>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-0">
                            <?php if (($item['jenis'] ?? 'foto') === 'foto'): ?>
                                <img src="<?= base_url('uploads/galeri/' . ($item['file'] ?? 'default.jpg')) ?>" 
                                     class="img-fluid w-100" alt="<?= esc($item['judul'] ?? '') ?>">
                            <?php else: ?>
                                <video class="w-100" controls>
                                    <source src="<?= base_url('uploads/galeri/' . ($item['file'] ?? '')) ?>" type="video/mp4">
                                    Browser Anda tidak mendukung video HTML5.
                                </video>
                            <?php endif; ?>
                        </div>
                        <div class="modal-footer">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div>
                                    <span class="badge bg-<?= ($item['jenis'] ?? 'foto') === 'foto' ? 'success' : 'warning' ?> me-2">
                                        <i class="fas fa-<?= ($item['jenis'] ?? 'foto') === 'foto' ? 'image' : 'video' ?> me-1"></i>
                                        <?= ucfirst($item['jenis'] ?? 'foto') ?>
                                    </span>
                                    <span class="badge bg-info me-2">
                                        <?= esc($item['kategori'] ?? 'Umum') ?>
                                    </span>
                                    <small class="text-muted">
                                        <?= date('d/m/Y H:i', strtotime($item['created_at'] ?? 'now')) ?> WIB
                                    </small>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" 
                                            onclick="downloadMedia(<?= $item['id'] ?? 0 ?>)">
                                        <i class="fas fa-download me-1"></i>Download
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                            <?php if (!empty($item['deskripsi'])): ?>
                                <div class="w-100 mt-2">
                                    <small class="text-muted"><?= nl2br(esc($item['deskripsi'])) ?></small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function hapusGaleri(galeriId) {
    if (confirm('Yakin ingin menghapus media ini? Tindakan ini tidak dapat dibatalkan!')) {
        window.location.href = `<?= base_url('admin/hapusGaleri/') ?>${galeriId}`;
    }
}

function downloadMedia(galeriId) {
    window.open(`<?= base_url('admin/downloadGaleri/') ?>${galeriId}`, '_blank');
}
</script>