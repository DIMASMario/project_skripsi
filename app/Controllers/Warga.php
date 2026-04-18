<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\VisitorLogModel;

class Warga extends BaseController
{
    protected $userModel;
    protected $visitorLogModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->visitorLogModel = new VisitorLogModel();
    }

    /**
     * Halaman daftar warga dengan search dan filter
     */
    public function index()
    {
        try {
            // Get basic warga data
            $warga = $this->userModel->where('role', 'warga')
                                     ->where('status', 'disetujui')
                                     ->limit(20)
                                     ->findAll();

            // Basic statistics
            $totalWarga = count($warga);
            $laki_laki = 0;
            $perempuan = 0;
            foreach ($warga as $w) {
                if ($w['jenis_kelamin'] == 'L') $laki_laki++;
                if ($w['jenis_kelamin'] == 'P') $perempuan++;
            }

            $data = [
                'title' => 'Direktori Warga - Website Desa Blanakan',
                'warga_list' => $warga,
                'total_warga' => $totalWarga,
                'laki_laki' => $laki_laki,
                'perempuan' => $perempuan,
                'kepala_keluarga' => 0,
                'pager' => null
            ];

            return view('warga/index', $data);
        } catch (\Exception $e) {
            return view('errors/html/error_500', [
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Detail profil warga
     */
    public function detail($id)
    {
        // Log visitor
        $this->visitorLogModel->logVisitor('warga/' . $id);

        // Get warga
        $warga = $this->userModel->where('role', 'warga')
                                 ->where('status', 'disetujui')
                                 ->find($id);

        if (!$warga) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Warga tidak ditemukan');
        }

        // Format data
        $warga['formatted_tgl_lahir'] = date('d F Y', strtotime($warga['tanggal_lahir']));
        $warga['alamat_lengkap'] = $warga['alamat'] . ', RT. ' . $warga['rt'] . ' / RW. ' . $warga['rw'] . ', ' . 
                                   $warga['kelurahan_desa'] . ', ' . $warga['kecamatan'] . ', ' . 
                                   $warga['kota_kabupaten'] . ', ' . $warga['provinsi'];

        $data = [
            'title' => $warga['nama_lengkap'] . ' - Website Desa Blanakan',
            'warga' => $warga,
            'breadcrumb' => 'Detail Warga'
        ];

        return view('frontend/profil_warga', $data);
    }

    /**
     * API untuk search warga (AJAX)
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');

        if (strlen($keyword) < 2) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Minimal 2 karakter'
            ]);
        }

        $results = $this->userModel->searchWarga($keyword);

        // Format data untuk response
        $data = [];
        foreach ($results as $warga) {
            $data[] = [
                'id' => $warga['id'],
                'nama' => $warga['nama_lengkap'],
                'nik' => $warga['nik'],
                'alamat' => $warga['alamat'],
                'email' => $warga['email'],
                'url' => base_url('warga/' . $warga['id'])
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $data,
            'count' => count($data)
        ]);
    }

    /**
     * API untuk filter warga berdasarkan parameter
     */
    public function filter()
    {
        $jk = $this->request->getGet('jenis_kelamin');
        $alamat = $this->request->getGet('alamat');
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 20;

        $builder = $this->userModel->where('role', 'warga')
                                   ->where('status', 'disetujui');

        if ($jk) {
            $builder->where('jenis_kelamin', $jk);
        }

        if ($alamat) {
            $builder->where('alamat', 'LIKE', '%' . $alamat . '%');
        }

        $total = $builder->countAllResults(false);

        $warga = $builder->orderBy('nama_lengkap', 'ASC')
                        ->limit($perPage, ($page - 1) * $perPage)
                        ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $warga,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => ceil($total / $perPage)
        ]);
    }

    /**
     * API untuk get statistik warga
     */
    public function statistics()
    {
        $stats = [
            'total' => $this->userModel->where('role', 'warga')
                                       ->where('status', 'disetujui')
                                       ->countAllResults(),
            'by_jenis_kelamin' => $this->userModel->select('jenis_kelamin, COUNT(*) as total')
                                                   ->where('role', 'warga')
                                                   ->where('status', 'disetujui')
                                                   ->groupBy('jenis_kelamin')
                                                   ->findAll(),
            'by_wilayah' => $this->userModel->select('kelurahan_desa, COUNT(*) as total')
                                            ->where('role', 'warga')
                                            ->where('status', 'disetujui')
                                            ->groupBy('kelurahan_desa')
                                            ->findAll()
        ];

        return $this->response->setJSON([
            'success' => true,
            'data' => $stats
        ]);
    }
}
