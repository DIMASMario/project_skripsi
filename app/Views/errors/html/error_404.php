<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <title>404 - Halaman Tidak Ditemukan</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
            text-align: center;
            max-width: 500px;
        }
        h1 {
            color: #e74c3c;
            font-size: 3rem;
            margin-bottom: 10px;
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        p {
            color: #7f8c8d;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .btn {
            background: #3498db;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <h2>Halaman Tidak Ditemukan</h2>
        <p>Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman tersebut telah dipindahkan atau dihapus.</p>
        <a href="<?= base_url() ?>" class="btn">Kembali ke Beranda</a>
    </div>
</body>
</html>
