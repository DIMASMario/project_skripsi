<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">
            <?= isset($template) ? 'Edit Template' : 'Tambah Template' ?>
        </h1>
        <a href="<?= base_url('admin/surat-template') ?>" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
            <i class="material-icons text-sm mr-2">arrow_back</i>
            Kembali
        </a>
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

    <div class="bg-white shadow rounded-lg p-6">
        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama_template" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Template <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama_template" 
                           name="nama_template" 
                           value="<?= old('nama_template', $template['nama_template'] ?? '') ?>" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <?php if (isset($validation) && $validation->hasError('nama_template')): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $validation->getError('nama_template') ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="jenis_surat" class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Surat <span class="text-red-500">*</span>
                    </label>
                    <select id="jenis_surat" name="jenis_surat" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Jenis Surat</option>
                        <option value="domisili" <?= old('jenis_surat', $template['jenis_surat'] ?? '') == 'domisili' ? 'selected' : '' ?>>
                            Surat Domisili
                        </option>
                        <option value="sktm" <?= old('jenis_surat', $template['jenis_surat'] ?? '') == 'sktm' ? 'selected' : '' ?>>
                            Surat Keterangan Tidak Mampu
                        </option>
                        <option value="nikah" <?= old('jenis_surat', $template['jenis_surat'] ?? '') == 'nikah' ? 'selected' : '' ?>>
                            Surat Pengantar Nikah
                        </option>
                        <option value="usaha" <?= old('jenis_surat', $template['jenis_surat'] ?? '') == 'usaha' ? 'selected' : '' ?>>
                            Surat Keterangan Usaha
                        </option>
                        <option value="skck" <?= old('jenis_surat', $template['jenis_surat'] ?? '') == 'skck' ? 'selected' : '' ?>>
                            Surat Pengantar SKCK
                        </option>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('jenis_surat')): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $validation->getError('jenis_surat') ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea id="deskripsi" 
                          name="deskripsi" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?= old('deskripsi', $template['deskripsi'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="isi_template" class="block text-sm font-medium text-gray-700 mb-2">
                    Isi Template <span class="text-red-500">*</span>
                </label>
                <div class="mb-2">
                    <div class="text-xs text-gray-600 bg-gray-50 p-2 rounded border">
                        <strong>Variabel yang tersedia:</strong>
                        <code>{{nama_lengkap}}</code>, <code>{{nik}}</code>, <code>{{tempat_lahir}}</code>, 
                        <code>{{tanggal_lahir}}</code>, <code>{{alamat}}</code>, <code>{{rt}}</code>, 
                        <code>{{rw}}</code>, <code>{{tanggal_surat}}</code>, <code>{{nomor_surat}}</code>
                    </div>
                </div>
                <textarea id="isi_template" 
                          name="isi_template" 
                          rows="15" 
                          required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"><?= old('isi_template', $template['isi_template'] ?? '') ?></textarea>
                <?php if (isset($validation) && $validation->hasError('isi_template')): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $validation->getError('isi_template') ?></p>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="kop_surat" class="block text-sm font-medium text-gray-700 mb-2">Kop Surat</label>
                    <textarea id="kop_surat" 
                              name="kop_surat" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?= old('kop_surat', $template['kop_surat'] ?? '') ?></textarea>
                </div>

                <div>
                    <label for="ttd_surat" class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Surat</label>
                    <textarea id="ttd_surat" 
                              name="ttd_surat" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?= old('ttd_surat', $template['ttd_surat'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="flex items-center space-x-6">
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1"
                           <?= old('is_active', $template['is_active'] ?? '1') ? 'checked' : '' ?>
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">Template Aktif</label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" 
                           id="auto_numbering" 
                           name="auto_numbering" 
                           value="1"
                           <?= old('auto_numbering', $template['auto_numbering'] ?? '1') ? 'checked' : '' ?>
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="auto_numbering" class="ml-2 block text-sm text-gray-900">Penomoran Otomatis</label>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-6 border-t">
                <a href="<?= base_url('admin/surat-template') ?>" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Batal
                </a>
                <button type="button" onclick="previewTemplate()" 
                        class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    <i class="material-icons text-sm mr-2">visibility</i>
                    Preview
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="material-icons text-sm mr-2">save</i>
                    <?= isset($template) ? 'Update' : 'Simpan' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Preview Modal -->
<div id="preview-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-96 overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Preview Template</h3>
            <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-600">
                <i class="material-icons">close</i>
            </button>
        </div>
        
        <div id="preview-content" class="border border-gray-300 p-4 bg-white rounded min-h-96">
            <!-- Preview content will be loaded here -->
        </div>
        
        <div class="flex justify-end mt-4">
            <button onclick="closePreviewModal()" 
                    class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
function previewTemplate() {
    const isiTemplate = document.getElementById('isi_template').value;
    const kopSurat = document.getElementById('kop_surat').value;
    const ttdSurat = document.getElementById('ttd_surat').value;
    
    // Sample data untuk preview
    const sampleData = {
        nama_lengkap: 'John Doe',
        nik: '1234567890123456',
        tempat_lahir: 'Jakarta',
        tanggal_lahir: '01 Januari 1990',
        alamat: 'Jl. Contoh No. 123',
        rt: '001',
        rw: '005',
        tanggal_surat: new Date().toLocaleDateString('id-ID'),
        nomor_surat: '001/SURAT/2024'
    };
    
    // Replace variables with sample data
    let previewContent = isiTemplate;
    Object.keys(sampleData).forEach(key => {
        const regex = new RegExp(`{{${key}}}`, 'g');
        previewContent = previewContent.replace(regex, sampleData[key]);
    });
    
    // Build complete preview
    let fullPreview = '';
    if (kopSurat) {
        fullPreview += `<div class="text-center mb-6">${kopSurat.replace(/\n/g, '<br>')}</div>`;
    }
    fullPreview += `<div class="whitespace-pre-line">${previewContent}</div>`;
    if (ttdSurat) {
        fullPreview += `<div class="mt-6">${ttdSurat.replace(/\n/g, '<br>')}</div>`;
    }
    
    document.getElementById('preview-content').innerHTML = fullPreview;
    document.getElementById('preview-modal').style.display = 'flex';
}

function closePreviewModal() {
    document.getElementById('preview-modal').style.display = 'none';
}

// Auto-resize textareas
document.querySelectorAll('textarea').forEach(textarea => {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});
</script>
<?= $this->endsection() ?>