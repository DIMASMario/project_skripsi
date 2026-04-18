<?php

namespace App\Controllers;

use App\Models\DesaModel;

class Profil extends BaseController
{
    protected $desaModel;

    public function __construct()
    {
        $this->desaModel = new DesaModel();
    }

    /**
     * Halaman Profil Desa dengan data dinamis
     */
    public function index()
    {
        $dataDesa = null;
        
        try {
            // Ambil data desa dari database
            $dataDesa = $this->desaModel->getDesaInfo();
        } catch (\Exception $e) {
            log_message('error', 'Error accessing DesaModel::getDesaInfo: ' . $e->getMessage());
        }

        // Default data jika database kosong atau ada error
        if (!$dataDesa) {
            $dataDesa = $this->getDefaultDesaData();
        }

        $data = [
            'title' => 'Profil Desa - ' . ($dataDesa['nama_desa'] ?? 'Desa Blanakan'),
            'meta_description' => 'Profil lengkap Desa Blanakan, sejarah, visi misi, dan struktur pemerintahan desa',
            'desa_data' => $dataDesa,
            'struktur_organisasi' => $this->getStrukturOrganisasi(),
            'sejarah_desa' => $this->getSejarahDesa(),
            'visi_misi' => $this->getVisiMisi()
        ];

        return view('frontend/profil', $data);
    }

    /**
     * Data default desa jika database kosong
     */
    private function getDefaultDesaData()
    {
        return [
            'nama_desa' => 'Blanakan',
            'kecamatan' => 'Blanakan',
            'kabupaten' => 'Subang',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '41265',
            'luas_wilayah' => '15.25',
            'jumlah_penduduk' => '12500',
            'jumlah_kk' => '3200',
            'batas_utara' => 'Desa Jayamukti',
            'batas_selatan' => 'Laut Jawa',
            'batas_timur' => 'Desa Muarablanakan',
            'batas_barat' => 'Desa Sukasari',
            'kepala_desa' => 'H. Ahmad Solihin',
            'alamat_kantor' => 'Jl. Raya Blanakan No. 123',
            'telepon' => '0260-421234',
            'email' => 'desa.blanakan@subang.go.id',
            'website' => 'https://blanakan.subang.go.id'
        ];
    }

    /**
     * Struktur organisasi desa
     */
    private function getStrukturOrganisasi()
    {
        return [
            [
                'jabatan' => 'Kepala Desa',
                'nama' => 'H. Ahmad Solihin',
                'nip' => '196801011989031005',
                'pendidikan' => 'S1 Administrasi Negara',
                'foto' => 'kepala_desa.jpg'
            ],
            [
                'jabatan' => 'Sekretaris Desa',
                'nama' => 'Drs. Budiman Santoso',
                'nip' => '197205151994031002',
                'pendidikan' => 'S1 Ilmu Pemerintahan',
                'foto' => 'sekretaris_desa.jpg'
            ],
            [
                'jabatan' => 'Kaur Keuangan',
                'nama' => 'Sri Mulyani, SE',
                'nip' => '198003201998032001',
                'pendidikan' => 'S1 Akuntansi',
                'foto' => 'kaur_keuangan.jpg'
            ],
            [
                'jabatan' => 'Kaur Umum',
                'nama' => 'Agus Wahyudi',
                'nip' => '197812051999031003',
                'pendidikan' => 'SMA',
                'foto' => 'kaur_umum.jpg'
            ],
            [
                'jabatan' => 'Kasi Pemerintahan',
                'nama' => 'Dedi Kurniawan, S.Sos',
                'nip' => '198506101996031001',
                'pendidikan' => 'S1 Sosiologi',
                'foto' => 'kasi_pemerintahan.jpg'
            ],
            [
                'jabatan' => 'Kasi Kesejahteraan',
                'nama' => 'Siti Nurhasanah, S.Pd',
                'nip' => '198209152000032002',
                'pendidikan' => 'S1 Pendidikan',
                'foto' => 'kasi_kesejahteraan.jpg'
            ],
            [
                'jabatan' => 'Kasi Pelayanan',
                'nama' => 'Bambang Sutrisno',
                'nip' => '197704031995031002',
                'pendidikan' => 'D3 Administrasi',
                'foto' => 'kasi_pelayanan.jpg'
            ]
        ];
    }

    /**
     * Sejarah singkat desa
     */
    private function getSejarahDesa()
    {
        return [
            'tahun_berdiri' => '1950',
            'asal_nama' => 'Nama Blanakan berasal dari kata "Blanak" yang merupakan nama ikan yang banyak ditemukan di perairan sekitar desa ini. Seiring waktu, kata "Blanak" berkembang menjadi "Blanakan".',
            'sejarah_singkat' => 'Desa Blanakan merupakan salah satu desa yang terletak di pesisir utara Kabupaten Subang. Desa ini memiliki sejarah panjang sebagai daerah nelayan dan pertanian. Pada masa penjajahan Belanda, wilayah ini menjadi jalur perdagangan penting karena letaknya yang strategis di pesisir Laut Jawa.',
            'perkembangan' => [
                '1950' => 'Pembentukan Desa Blanakan',
                '1965' => 'Pembangunan Pelabuhan Perikanan',
                '1980' => 'Pembangunan jalan raya penghubung',
                '1995' => 'Pembangunan kantor desa baru',
                '2010' => 'Program modernisasi infrastruktur',
                '2020' => 'Digitalisasi pelayanan desa'
            ]
        ];
    }

    /**
     * Visi dan misi desa
     */
    private function getVisiMisi()
    {
        return [
            'visi' => 'Terwujudnya Desa Blanakan sebagai desa yang maju, mandiri, dan sejahtera berbasis ekonomi kerakyatan dengan tetap menjaga kelestarian lingkungan dan nilai-nilai budaya lokal.',
            'misi' => [
                'Meningkatkan kualitas sumber daya manusia melalui pendidikan dan pelatihan',
                'Mengembangkan potensi ekonomi desa berbasis kearifan lokal',
                'Meningkatkan kualitas infrastruktur dan fasilitas umum',
                'Memperkuat tata kelola pemerintahan yang transparan dan akuntabel',
                'Melestarikan lingkungan hidup dan budaya lokal',
                'Meningkatkan partisipasi masyarakat dalam pembangunan desa'
            ],
            'tujuan' => [
                'Terciptanya masyarakat yang berdaya dan mandiri',
                'Terwujudnya ekonomi desa yang berkelanjutan',
                'Tersedianya infrastruktur yang memadai',
                'Terlaksananya pemerintahan yang baik dan bersih',
                'Terjaganya kelestarian lingkungan dan budaya',
                'Meningkatnya kesejahteraan masyarakat'
            ]
        ];
    }
}