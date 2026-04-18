<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KodeRegistrasiModel;
use App\Models\UserModel;

class KodeRegistrasiController extends BaseController
{
    protected $kodeRegistrasiModel;
    protected $userModel;
    
    public function __construct()
    {
        $this->kodeRegistrasiModel = new KodeRegistrasiModel();
        $this->userModel = new UserModel();
        
        // Check if user is admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Halaman tidak ditemukan');
        }
    }
    
    /**
     * Halaman utama manajemen kode registrasi
     */
    public function index()
    {
        // Get filters from query string
        $filters = [
            'status' => $this->request->getGet('status'),
            'rt' => $this->request->getGet('rt'),
            'rw' => $this->request->getGet('rw'),
            'search' => $this->request->getGet('search')
        ];
        
        // Remove empty filters
        $filters = array_filter($filters);
        
        // Get kode registrasi with filters
        $kodeList = $this->kodeRegistrasiModel->getWithFilters($filters);
        
        // Get statistik
        $statistik = $this->kodeRegistrasiModel->getStatistik();
        
        // Get unique RT/RW for filter dropdown
        $rtRwList = $this->kodeRegistrasiModel->select('DISTINCT rt, rw')
                                               ->orderBy('rt', 'ASC')
                                               ->orderBy('rw', 'ASC')
                                               ->findAll();
        
        $data = [
            'title' => 'Manajemen Kode Registrasi',
            'kode_list' => $kodeList,
            'statistik' => $statistik,
            'rt_rw_list' => $rtRwList,
            'filters' => $filters
        ];
        
        return view('admin/kode_registrasi/index', $data);
    }
    
    /**
     * Form buat kode registrasi baru
     */
    public function create()
    {
        $data = [
            'title' => 'Buat Kode Registrasi',
            'validation' => \Config\Services::validation()
        ];
        
        // FIX: getMethod() returns uppercase 'POST', not lowercase 'post'
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'rt' => 'required|numeric|max_length[3]',
                'rw' => 'required|numeric|max_length[3]',
                'jumlah' => 'required|numeric|greater_than[0]|less_than_equal_to[100]',
                'keterangan' => 'permit_empty|max_length[500]'
            ];
            
            $messages = [
                'rt' => [
                    'required' => 'RT harus diisi',
                    'numeric' => 'RT harus berupa angka',
                    'max_length' => 'RT maksimal 3 digit'
                ],
                'rw' => [
                    'required' => 'RW harus diisi',
                    'numeric' => 'RW harus berupa angka',
                    'max_length' => 'RW maksimal 3 digit'
                ],
                'jumlah' => [
                    'required' => 'Jumlah kode harus diisi',
                    'numeric' => 'Jumlah harus berupa angka',
                    'greater_than' => 'Jumlah minimal 1',
                    'less_than_equal_to' => 'Jumlah maksimal 100 per batch'
                ]
            ];
            
            if ($this->validate($rules, $messages)) {
                $rt = $this->request->getPost('rt');
                $rw = $this->request->getPost('rw');
                $jumlah = (int) $this->request->getPost('jumlah');
                $keterangan = $this->request->getPost('keterangan');
                $createdBy = session()->get('user_id');
                
                try {
                    $createdCodes = $this->kodeRegistrasiModel->buatKodeBatch(
                        $rt,
                        $rw,
                        $jumlah,
                        $createdBy,
                        $keterangan
                    );
                    
                    if (count($createdCodes) > 0) {
                        session()->setFlashdata('success', 
                            "Berhasil membuat {$jumlah} kode registrasi untuk RT {$rt} RW {$rw}");
                        
                        // Log activity
                        log_message('info', "Admin {$createdBy} created {$jumlah} registration codes for RT {$rt} RW {$rw}");
                        
                        return redirect()->to('/admin/kode-registrasi');
                    } else {
                        session()->setFlashdata('error', 'Gagal membuat kode registrasi');
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Error creating registration codes: ' . $e->getMessage());
                    session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
                }
            } else {
                $data['validation'] = $this->validator;
                session()->setFlashdata('error', 'Mohon periksa kembali input Anda');
            }
        }
        
        return view('admin/kode_registrasi/create', $data);
    }
    
    /**
     * Detail kode registrasi
     */
    public function detail($id)
    {
        $kode = $this->kodeRegistrasiModel->find($id);
        
        if (!$kode) {
            session()->setFlashdata('error', 'Kode registrasi tidak ditemukan');
            return redirect()->to('/admin/kode-registrasi');
        }
        
        // Get user data if code has been used
        $user = null;
        if ($kode['user_id']) {
            $user = $this->userModel->find($kode['user_id']);
        }
        
        $data = [
            'title' => 'Detail Kode Registrasi',
            'kode' => $kode,
            'user' => $user
        ];
        
        return view('admin/kode_registrasi/detail', $data);
    }
    
    /**
     * Delete kode registrasi (hanya yang belum digunakan)
     */
    public function delete($id)
    {
        $kode = $this->kodeRegistrasiModel->find($id);
        
        if (!$kode) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Kode registrasi tidak ditemukan'
            ]);
        }
        
        // Hanya bisa hapus kode yang belum digunakan
        if ($kode['status'] !== 'belum_digunakan') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tidak dapat menghapus kode yang sudah digunakan atau kadaluarsa'
            ]);
        }
        
        if ($this->kodeRegistrasiModel->delete($id)) {
            log_message('info', "Admin " . session()->get('user_id') . " deleted registration code: {$kode['kode_registrasi']}");
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Kode registrasi berhasil dihapus'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus kode registrasi'
            ]);
        }
    }
    
    /**
     * Update status kode (kadaluarsakan manual)
     */
    public function updateStatus($id)
    {
        $kode = $this->kodeRegistrasiModel->find($id);
        
        if (!$kode) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Kode registrasi tidak ditemukan'
            ]);
        }
        
        $status = $this->request->getPost('status');
        
        if (!in_array($status, ['belum_digunakan', 'kadaluarsa'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Status tidak valid'
            ]);
        }
        
        // Tidak bisa mengubah status kode yang sudah digunakan
        if ($kode['status'] === 'sudah_digunakan') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tidak dapat mengubah status kode yang sudah digunakan'
            ]);
        }
        
        if ($this->kodeRegistrasiModel->update($id, ['status' => $status])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status kode berhasil diupdate'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengupdate status'
            ]);
        }
    }
    
    /**
     * Export kode registrasi ke CSV
     */
    public function export()
    {
        $filters = [
            'status' => $this->request->getGet('status'),
            'rt' => $this->request->getGet('rt'),
            'rw' => $this->request->getGet('rw')
        ];
        
        $filters = array_filter($filters);
        
        $kodeList = $this->kodeRegistrasiModel->getWithFilters($filters);
        
        // Generate CSV
        $filename = 'kode_registrasi_' . date('YmdHis') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header
        fputcsv($output, ['Kode Registrasi', 'RT', 'RW', 'Status', 'Digunakan Oleh', 'Tanggal Dibuat', 'Tanggal Digunakan']);
        
        // Data
        foreach ($kodeList as $kode) {
            $userName = '-';
            if ($kode['user_id']) {
                $user = $this->userModel->find($kode['user_id']);
                $userName = $user ? $user['nama_lengkap'] : '-';
            }
            
            fputcsv($output, [
                $kode['kode_registrasi'],
                $kode['rt'],
                $kode['rw'],
                ucfirst($kode['status']),
                $userName,
                $kode['created_at'],
                $kode['used_at'] ?? '-'
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Print kode registrasi untuk distribusi
     */
    public function printCodes()
    {
        $ids = $this->request->getPost('ids');
        
        if (empty($ids)) {
            $ids = $this->request->getGet('ids');
        }
        
        if (empty($ids)) {
            session()->setFlashdata('error', 'Tidak ada kode yang dipilih');
            return redirect()->back();
        }
        
        // Convert comma-separated IDs to array
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }
        
        $kodeList = $this->kodeRegistrasiModel->whereIn('id', $ids)
                                               ->where('status', 'belum_digunakan')
                                               ->findAll();
        
        if (empty($kodeList)) {
            session()->setFlashdata('error', 'Tidak ada kode valid yang dipilih');
            return redirect()->back();
        }
        
        $data = [
            'title' => 'Cetak Kode Registrasi',
            'kode_list' => $kodeList
        ];
        
        return view('admin/kode_registrasi/print', $data);
    }
}
