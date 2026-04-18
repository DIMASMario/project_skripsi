<?php

namespace App\Controllers;

use App\Models\BeritaModel;
use App\Models\VisitorLogModel;

class Berita extends BaseController
{
    protected $beritaModel;
    protected $visitorLogModel;

    public function __construct()
    {
        $this->beritaModel = new BeritaModel();
        $this->visitorLogModel = new VisitorLogModel();
    }

    /**
     * Halaman daftar berita frontend
     */
    public function index()
    {
        // Log visitor - disable sementara
        // $this->visitorLogModel->logVisitor('berita');

        // Get page from GET parameter
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 12;
        $search = $this->request->getGet('search');
        $kategori = $this->request->getGet('kategori');

        // Get berita dengan pagination
        $berita = $this->beritaModel->getBeritaWithSearch($perPage, $page, $search, $kategori);

        // Get pager instance
        $pager = $this->beritaModel->pager;

        // Get kategori list untuk filter
        $kategoriList = $this->beritaModel->select('kategori')
                                          ->where('status', 'publish')
                                          ->distinct()
                                          ->findAll();

        $data = [
            'title' => 'Berita - Website Desa Blanakan',
            'berita' => $berita,
            'pager' => $pager,
            'kategori_list' => $kategoriList,
            'current_search' => $search,
            'current_kategori' => $kategori,
            'total_berita' => $this->beritaModel->getTotalBeritaPublished()
        ];

        return view('berita/index', $data);
    }

    /**
     * Halaman detail berita berdasarkan slug
     */
    public function detailBerita($slug)
    {
        // Log visitor - disabled temporarily
        // $this->visitorLogModel->logVisitor('berita/' . $slug);

        // Get berita by slug
        $berita = $this->beritaModel->getBeritaBySlug($slug);

        if (!$berita) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Berita tidak ditemukan');
        }

        // Increment views
        $this->beritaModel->incrementViews($berita['id']);

        // Get berita terkait (sama kategori)
        $beritaTerkait = $this->beritaModel->getBeritaTerkait($berita['kategori'], $berita['id'], 3);

        // Get berita populer
        $beritaPopuler = $this->beritaModel->getBeritaPopuler(5);

        // Format tanggal dan alias untuk view compatibility
        $berita['formatted_date'] = date('d F Y', strtotime($berita['created_at']));
        $berita['formatted_time'] = date('H:i', strtotime($berita['created_at']));
        $berita['tanggal_publikasi'] = $berita['created_at']; // Alias untuk view
        $berita['penulis'] = $berita['author'] ?? 'Admin Desa'; // Alias untuk author

        $data = [
            'title' => $berita['judul'] . ' - Website Desa Blanakan',
            'berita' => $berita,
            'berita_terkait' => $beritaTerkait,
            'berita_populer' => $beritaPopuler,
            'breadcrumb' => [
                ['text' => 'Beranda', 'title' => 'Beranda', 'url' => base_url()],
                ['text' => 'Berita', 'title' => 'Berita', 'url' => base_url('berita')],
                ['text' => $berita['judul'], 'title' => $berita['judul'], 'url' => '']
            ]
        ];

        return view('frontend/detail_berita_new', $data);
    }

    /**
     * API untuk get berita kategori tertentu (for AJAX)
     */
    public function getByKategori($kategori)
    {
        $limit = $this->request->getGet('limit') ?? 5;
        $berita = $this->beritaModel->getBeritaByKategori($kategori, $limit);

        return $this->response->setJSON([
            'success' => true,
            'data' => $berita
        ]);
    }

    /**
     * API untuk search berita (for AJAX)
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');

        if (strlen($keyword) < 3) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Minimal 3 karakter'
            ]);
        }

        $berita = $this->beritaModel->select('id, judul, slug, excerpt, created_at')
                                    ->where('status', 'publish')
                                    ->groupStart()
                                    ->like('judul', $keyword)
                                    ->orLike('konten', $keyword)
                                    ->groupEnd()
                                    ->limit(10)
                                    ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $berita
        ]);
    }
}
