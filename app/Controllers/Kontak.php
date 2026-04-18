<?php

namespace App\Controllers;

use App\Models\KontakModel;

class Kontak extends BaseController
{
    protected $kontakModel;

    public function __construct()
    {
        $this->kontakModel = new KontakModel();
    }

    /**
     * Halaman Kontak dengan data dinamis
     */
    public function index()
    {
        // Ambil data kontak dari database
        $kontakData = $this->kontakModel->getKontakInfo();

        // Default data jika database kosong
        if (!$kontakData) {
            $kontakData = $this->getDefaultKontakData();
        }

        $data = [
            'title' => 'Kontak - Desa Blanakan',
            'meta_description' => 'Hubungi kami untuk informasi, layanan, atau pengaduan terkait Desa Blanakan',
            'kontak_data' => $kontakData,
            'jam_operasional' => $this->getJamOperasional(),
            'peta_lokasi' => $this->getLokasiPeta(),
            'media_sosial' => $this->getMediaSosial(),
            'faq_kontak' => $this->getFaqKontak()
        ];

        return view('frontend/kontak_new', $data);
    }

    /**
     * Proses kirim pesan kontak
     */
    public function kirimPesan()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'telepon' => 'permit_empty|min_length[10]|max_length[15]',
            'subjek' => 'required|min_length[5]|max_length[200]',
            'pesan' => 'required|min_length[10]|max_length[1000]'
        ];

        $messages = [
            'nama' => [
                'required' => 'Nama harus diisi',
                'min_length' => 'Nama minimal 3 karakter',
                'max_length' => 'Nama maksimal 100 karakter'
            ],
            'email' => [
                'required' => 'Email harus diisi',
                'valid_email' => 'Format email tidak valid'
            ],
            'telepon' => [
                'min_length' => 'Nomor telepon minimal 10 digit',
                'max_length' => 'Nomor telepon maksimal 15 digit'
            ],
            'subjek' => [
                'required' => 'Subjek harus diisi',
                'min_length' => 'Subjek minimal 5 karakter',
                'max_length' => 'Subjek maksimal 200 karakter'
            ],
            'pesan' => [
                'required' => 'Pesan harus diisi',
                'min_length' => 'Pesan minimal 10 karakter',
                'max_length' => 'Pesan maksimal 1000 karakter'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'telepon' => $this->request->getPost('telepon'),
            'subjek' => $this->request->getPost('subjek'),
            'pesan' => $this->request->getPost('pesan'),
            'tanggal_kirim' => date('Y-m-d H:i:s'),
            'status' => 'baru',
            'ip_address' => $this->request->getIPAddress()
        ];

        try {
            $this->kontakModel->simpanPesan($data);
            return redirect()->back()->with('success', 'Pesan Anda telah berhasil dikirim. Terima kasih!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengirim pesan. Silakan coba lagi.');
        }
    }

    /**
     * Data kontak default
     */
    private function getDefaultKontakData()
    {
        return [
            'nama_instansi' => 'Kantor Desa Blanakan',
            'alamat' => 'Jl. Raya Blanakan No. 123, Blanakan, Subang, Jawa Barat 41265',
            'telepon' => '0260-421234',
            'fax' => '0260-421235',
            'email' => 'desa.blanakan@subang.go.id',
            'website' => 'https://blanakan.subang.go.id',
            'kepala_desa' => 'H. Ahmad Solihin',
            'sekretaris_desa' => 'Drs. Budiman Santoso',
            'latitude' => '-6.3089',
            'longitude' => '107.6253',
            'kode_pos' => '41265'
        ];
    }

    /**
     * Jam operasional kantor desa
     */
    private function getJamOperasional()
    {
        return [
            'senin_kamis' => [
                'hari' => 'Senin - Kamis',
                'jam_masuk' => '08:00',
                'jam_pulang' => '16:00',
                'istirahat_mulai' => '12:00',
                'istirahat_selesai' => '13:00'
            ],
            'jumat' => [
                'hari' => 'Jumat',
                'jam_masuk' => '08:00',
                'jam_pulang' => '16:30',
                'istirahat_mulai' => '11:30',
                'istirahat_selesai' => '13:00'
            ],
            'sabtu_minggu' => [
                'hari' => 'Sabtu - Minggu',
                'status' => 'Tutup',
                'keterangan' => 'Kecuali ada kegiatan khusus'
            ],
            'hari_libur' => [
                'status' => 'Tutup',
                'keterangan' => 'Mengikuti kalender hari libur nasional'
            ]
        ];
    }

    /**
     * Data lokasi untuk peta
     */
    private function getLokasiPeta()
    {
        return [
            'latitude' => -6.3089,
            'longitude' => 107.6253,
            'zoom_level' => 15,
            'marker_title' => 'Kantor Desa Blanakan',
            'marker_description' => 'Jl. Raya Blanakan No. 123, Blanakan, Subang, Jawa Barat',
            'google_maps_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.6!2d107.6253!3d-6.3089!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTgnMzIuMCJTIDEwN8KwMzcnMzEuMSJF!5e0!3m2!1sen!2sid!4v1625097600000!5m2!1sen!2sid'
        ];
    }

    /**
     * Media sosial desa
     */
    private function getMediaSosial()
    {
        return [
            [
                'platform' => 'Facebook',
                'url' => 'https://facebook.com/desa.blanakan',
                'icon' => 'fab fa-facebook-f',
                'username' => '@desa.blanakan',
                'color' => '#1877F2'
            ],
            [
                'platform' => 'Instagram',
                'url' => 'https://instagram.com/desa.blanakan',
                'icon' => 'fab fa-instagram',
                'username' => '@desa.blanakan',
                'color' => '#E4405F'
            ],
            [
                'platform' => 'Twitter',
                'url' => 'https://twitter.com/desa_blanakan',
                'icon' => 'fab fa-twitter',
                'username' => '@desa_blanakan',
                'color' => '#1DA1F2'
            ],
            [
                'platform' => 'YouTube',
                'url' => 'https://youtube.com/c/DesaBlanakan',
                'icon' => 'fab fa-youtube',
                'username' => 'Desa Blanakan Official',
                'color' => '#FF0000'
            ],
            [
                'platform' => 'WhatsApp',
                'url' => 'https://wa.me/6282123456789',
                'icon' => 'fab fa-whatsapp',
                'username' => '+62 821-2345-6789',
                'color' => '#25D366'
            ]
        ];
    }

    /**
     * FAQ terkait kontak
     */
    private function getFaqKontak()
    {
        return [
            [
                'pertanyaan' => 'Bagaimana cara mengajukan surat keterangan?',
                'jawaban' => 'Anda dapat mengajukan surat keterangan melalui layanan online di website ini atau datang langsung ke kantor desa dengan membawa persyaratan yang diperlukan.'
            ],
            [
                'pertanyaan' => 'Berapa lama proses pembuatan surat keterangan?',
                'jawaban' => 'Proses pembuatan surat keterangan umumnya membutuhkan waktu 1-3 hari kerja tergantung jenis surat dan kelengkapan dokumen.'
            ],
            [
                'pertanyaan' => 'Apakah ada biaya untuk layanan surat keterangan?',
                'jawaban' => 'Sebagian besar layanan surat keterangan tidak dikenakan biaya (gratis). Namun untuk beberapa jenis surat tertentu mungkin dikenakan biaya sesuai peraturan yang berlaku.'
            ],
            [
                'pertanyaan' => 'Bagaimana cara melaporkan keluhan atau pengaduan?',
                'jawaban' => 'Anda dapat menyampaikan keluhan melalui formulir kontak di website ini, datang langsung ke kantor desa, atau menghubungi nomor telepon yang tersedia.'
            ],
            [
                'pertanyaan' => 'Apakah bisa berkonsultasi di luar jam kerja?',
                'jawaban' => 'Untuk konsultasi di luar jam kerja, Anda dapat menghubungi melalui WhatsApp atau email. Namun respons akan diberikan pada jam kerja berikutnya.'
            ]
        ];
    }
}