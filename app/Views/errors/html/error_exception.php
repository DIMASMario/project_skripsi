<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <title>Server Error</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
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
            margin: 5px;
        }
        .btn:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>500</h1>
        <h2>Server Error</h2>
        <p>Terjadi kesalahan pada server. Silakan coba beberapa saat lagi atau hubungi administrator.</p>
        <a href="<?= base_url() ?>" class="btn">Kembali ke Beranda</a>
    </div>
</body>
</html>