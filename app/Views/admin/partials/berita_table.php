<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th width="5%">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAllBerita">
                    </div>
                </th>
                <th>Berita</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Views</th>
                <th width="15%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($berita)): ?>
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fas fa-newspaper text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">Belum ada berita</p>
                            <a href="<?= base_url('admin/tambah_berita') ?>" class="btn btn-primary btn-sm mt-2">
                                <i class="fas fa-plus me-1"></i>Tambah Berita Pertama
                            </a>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($berita as $item): ?>
                    <tr class="berita-row">
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="<?= $item['id'] ?? '' ?>">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-start">
                                <?php if (!empty($item['gambar'])): ?>
                                    <img src="<?= base_url('uploads/berita/' . $item['gambar']) ?>" 
                                         alt="Thumbnail" class="rounded me-3" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="fw-semibold berita-judul mb-1">
                                        <?= esc(substr($item['judul'] ?? 'Judul tidak tersedia', 0, 60)) ?>
                                        <?= strlen($item['judul'] ?? '') > 60 ? '...' : '' ?>
                                    </div>
                                    <small class="text-muted">
                                        <?= esc(substr($item['excerpt'] ?? strip_tags($item['konten'] ?? ''), 0, 80)) ?>
                                        <?= strlen($item['excerpt'] ?? strip_tags($item['konten'] ?? '')) > 80 ? '...' : '' ?>
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info berita-kategori">
                                <?= esc($item['kategori'] ?? 'Umum') ?>
                            </span>
                        </td>
                        <td>
                            <?php 
                            $status = $item['status'] ?? 'draft';
                            $badgeClass = $status === 'published' ? 'bg-success' : 'bg-warning';
                            $icon = $status === 'published' ? 'fas fa-check-circle' : 'fas fa-edit';
                            ?>
                            <span class="badge <?= $badgeClass ?>">
                                <i class="<?= $icon ?> me-1"></i>
                                <?= ucfirst($status) ?>
                            </span>
                        </td>
                        <td>
                            <div>
                                <div class="fw-medium"><?= date('d/m/Y', strtotime($item['created_at'] ?? 'now')) ?></div>
                                <small class="text-muted"><?= date('H:i', strtotime($item['created_at'] ?? 'now')) ?> WIB</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-eye text-muted me-1"></i>
                                <span><?= number_format($item['views'] ?? 0) ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="<?= base_url('berita/' . ($item['slug'] ?? '')) ?>" 
                                   class="btn btn-sm btn-outline-primary" target="_blank" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="<?= base_url('admin/edit_berita/' . ($item['id'] ?? '')) ?>" 
                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <?php if ($status === 'draft'): ?>
                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                            onclick="publishBerita(<?= $item['id'] ?? 0 ?>)" title="Publish">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                                            onclick="draftBerita(<?= $item['id'] ?? 0 ?>)" title="Jadikan Draft">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                <?php endif; ?>
                                
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="duplicateBerita(<?= $item['id'] ?? 0 ?>)">
                                                <i class="fas fa-copy me-2"></i>Duplikat
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="shareBerita('<?= $item['slug'] ?? '' ?>')">
                                                <i class="fas fa-share me-2"></i>Share
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" onclick="hapusBerita(<?= $item['id'] ?? 0 ?>)">
                                                <i class="fas fa-trash me-2"></i>Hapus
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function publishBerita(beritaId) {
    if (confirm('Yakin ingin mempublikasikan berita ini?')) {
        window.location.href = `<?= base_url('admin/publishBerita/') ?>${beritaId}`;
    }
}

function draftBerita(beritaId) {
    if (confirm('Yakin ingin menjadikan berita ini sebagai draft?')) {
        window.location.href = `<?= base_url('admin/draftBerita/') ?>${beritaId}`;
    }
}

function duplicateBerita(beritaId) {
    if (confirm('Yakin ingin menduplikat berita ini?')) {
        window.location.href = `<?= base_url('admin/duplicateBerita/') ?>${beritaId}`;
    }
}

function shareBerita(slug) {
    const url = `<?= base_url('berita/') ?>${slug}`;
    if (navigator.share) {
        navigator.share({
            title: 'Berita Desa',
            url: url
        });
    } else {
        navigator.clipboard.writeText(url).then(() => {
            alert('Link berhasil disalin ke clipboard!');
        });
    }
}

function hapusBerita(beritaId) {
    if (confirm('Yakin ingin menghapus berita ini? Tindakan ini tidak dapat dibatalkan!')) {
        window.location.href = `<?= base_url('admin/hapusBerita/') ?>${beritaId}`;
    }
}

// Select All functionality
document.getElementById('selectAllBerita').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('tbody .form-check-input');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});
</script>