<?php

namespace App\Controllers;

class LayananOnline extends BaseController
{
    protected $layananModel;

    public function __construct()
    {
        // Model untuk layanan online bisa dibuat nanti
        // $this->layananModel = new \App\Models\LayananModel();
    }

    /**
     * Halaman Layanan Online
     */
    public function index()
    {
        $data = [
            'title' => 'Layanan Online - Desa Blanakan',
            'meta_description' => 'Akses layanan administrasi desa secara online, mudah, cepat, dan efisien',
            'layanan_tersedia' => $this->getLayananTersedia(),
            'persyaratan_umum' => $this->getPersyaratanUmum(),
            'jam_operasional' => $this->getJamOperasional(),
            'panduan_penggunaan' => $this->getPanduanPenggunaan()
        ];

        return view('frontend/layanan_online_new', $data);
    }

    /**
     * Halaman form surat keterangan
     */
    public function suratKeterangan()
    {
        $data = [
            'title' => 'Surat Keterangan - Layanan Online Desa',
            'jenis_surat' => $this->getJenisSurat(),
            'persyaratan' => $this->getPersyaratanSurat('keterangan')
        ];

        return view('frontend/form_surat_keterangan', $data);
    }

    /**
     * Halaman form surat keterangan usaha
     */
    public function suratKeteranganUsaha()
    {
        $data = [
            'title' => 'Surat Keterangan Usaha - Layanan Online Desa',
            'persyaratan' => $this->getPersyaratanSurat('usaha')
        ];

        return view('frontend/form_surat_usaha', $data);
    }

    /**
     * Halaman form surat keterangan domisili
     */
    public function suratKeteranganDomisili()
    {
        $data = [
            'title' => 'Surat Keterangan Domisili - Layanan Online Desa',
            'persyaratan' => $this->getPersyaratanSurat('domisili')
        ];

        return view('frontend/form_surat_domisili', $data);
    }

    /**
     * Cek status permohonan
     */
    public function cekStatus()
    {
        $data = [
            'title' => 'Cek Status Permohonan - Layanan Online Desa'
        ];

        return view('frontend/cek_status_layanan', $data);
    }

    /**
     * Daftar layanan yang tersedia
     */
    private function getLayananTersedia()
    {
        return [
            [
                'id' => 'surat_keterangan',
                'nama' => 'Surat Keterangan',
                'deskripsi' => 'Permohonan berbagai jenis surat keterangan',
                'icon' => 'fas fa-file-alt',
                'url' => base_url('layanan-online/surat-keterangan'),
                'estimasi' => '1-2 hari kerja',
                'status' => 'aktif'
            ],
            [
                'id' => 'surat_keterangan_usaha',
                'nama' => 'Surat Keterangan Usaha',
                'deskripsi' => 'Surat keterangan untuk keperluan usaha/bisnis',
                'icon' => 'fas fa-briefcase',
                'url' => base_url('layanan-online/surat-keterangan-usaha'),
                'estimasi' => '2-3 hari kerja',
                'status' => 'aktif'
            ],
            [
                'id' => 'surat_domisili',
                'nama' => 'Surat Keterangan Domisili',
                'deskripsi' => 'Surat keterangan tempat tinggal/domisili',
                'icon' => 'fas fa-home',
                'url' => base_url('layanan-online/surat-keterangan-domisili'),
                'estimasi' => '1-2 hari kerja',
                'status' => 'aktif'
            ],
            [
                'id' => 'surat_tidak_mampu',
                'nama' => 'Surat Keterangan Tidak Mampu',
                'deskripsi' => 'Surat keterangan ekonomi tidak mampu',
                'icon' => 'fas fa-hand-holding-heart',
                'url' => '#',
                'estimasi' => '2-3 hari kerja',
                'status' => 'coming_soon'
            ],
            [
                'id' => 'legalisir_dokumen',
                'nama' => 'Legalisir Dokumen',
                'deskripsi' => 'Legalisir berbagai dokumen resmi',
                'icon' => 'fas fa-stamp',
                'url' => '#',
                'estimasi' => '1 hari kerja',
                'status' => 'coming_soon'
            ],
            [
                'id' => 'pengaduan_online',
                'nama' => 'Pengaduan Online',
                'deskripsi' => 'Layanan pengaduan dan aspirasi masyarakat',
                'icon' => 'fas fa-comments',
                'url' => '#',
                'estimasi' => 'Real-time',
                'status' => 'coming_soon'
            ]
        ];
    }

    /**
     * Persyaratan umum layanan online
     */
    private function getPersyaratanUmum()
    {
        return [
            'Warga Negara Indonesia (WNI)',
            'Terdaftar sebagai penduduk Desa Blanakan',
            'Memiliki KTP yang masih berlaku',
            'Memiliki Kartu Keluarga (KK)',
            'Mengisi formulir permohonan dengan lengkap dan benar',
            'Melampirkan dokumen pendukung sesuai jenis layanan'
        ];
    }

    /**
     * Jam operasional layanan
     */
    private function getJamOperasional()
    {
        return [
            'senin_kamis' => [
                'hari' => 'Senin - Kamis',
                'jam' => '08:00 - 16:00 WIB',
                'istirahat' => '12:00 - 13:00 WIB'
            ],
            'jumat' => [
                'hari' => 'Jumat',
                'jam' => '08:00 - 16:30 WIB',
                'istirahat' => '11:30 - 13:00 WIB'
            ],
            'sabtu_minggu' => [
                'hari' => 'Sabtu - Minggu',
                'jam' => 'Tutup',
                'istirahat' => '-'
            ],
            'catatan' => 'Layanan online dapat diakses 24/7, namun proses verifikasi mengikuti jam operasional kantor desa'
        ];
    }

    /**
     * Panduan penggunaan layanan online
     */
    private function getPanduanPenggunaan()
    {
        return [
            [
                'step' => 1,
                'judul' => 'Pilih Layanan',
                'deskripsi' => 'Pilih jenis layanan yang dibutuhkan dari menu yang tersedia'
            ],
            [
                'step' => 2,
                'judul' => 'Isi Formulir',
                'deskripsi' => 'Lengkapi formulir permohonan dengan data yang benar dan valid'
            ],
            [
                'step' => 3,
                'judul' => 'Upload Dokumen',
                'deskripsi' => 'Lampirkan dokumen pendukung sesuai persyaratan yang diminta'
            ],
            [
                'step' => 4,
                'judul' => 'Submit Permohonan',
                'deskripsi' => 'Kirim permohonan dan catat nomor registrasi untuk tracking'
            ],
            [
                'step' => 5,
                'judul' => 'Cek Status',
                'deskripsi' => 'Pantau status permohonan melalui nomor registrasi'
            ],
            [
                'step' => 6,
                'judul' => 'Ambil Dokumen',
                'deskripsi' => 'Ambil dokumen yang telah selesai di kantor desa atau melalui kurir'
            ]
        ];
    }

    /**
     * Jenis surat keterangan
     */
    private function getJenisSurat()
    {
        return [
            'keterangan_umum' => 'Surat Keterangan Umum',
            'keterangan_kelahiran' => 'Surat Keterangan Kelahiran',
            'keterangan_kematian' => 'Surat Keterangan Kematian',
            'keterangan_pindah' => 'Surat Keterangan Pindah',
            'keterangan_belum_nikah' => 'Surat Keterangan Belum Nikah',
            'keterangan_penghasilan' => 'Surat Keterangan Penghasilan'
        ];
    }

    /**
     * Persyaratan per jenis surat
     */
    private function getPersyaratanSurat($jenis)
    {
        $persyaratan = [
            'keterangan' => [
                'Fotocopy KTP yang masih berlaku',
                'Fotocopy Kartu Keluarga (KK)',
                'Pas foto 3x4 sebanyak 2 lembar',
                'Surat pengantar dari RT/RW'
            ],
            'usaha' => [
                'Fotocopy KTP yang masih berlaku',
                'Fotocopy Kartu Keluarga (KK)',
                'Pas foto 3x4 sebanyak 2 lembar',
                'Surat pengantar dari RT/RW',
                'Foto tempat usaha',
                'Sketsa lokasi usaha'
            ],
            'domisili' => [
                'Fotocopy KTP yang masih berlaku',
                'Fotocopy Kartu Keluarga (KK)',
                'Pas foto 3x4 sebanyak 2 lembar',
                'Surat pengantar dari RT/RW',
                'Fotocopy kontrak sewa/bukti kepemilikan rumah (jika ada)'
            ]
        ];

        return $persyaratan[$jenis] ?? $persyaratan['keterangan'];
    }
}