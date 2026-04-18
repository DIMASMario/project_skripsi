<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Surat Selesai</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 16px;
            color: #333333;
            margin-bottom: 20px;
        }
        .message {
            font-size: 14px;
            color: #555555;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .surat-info {
            background-color: #f9f9f9;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .surat-info p {
            margin: 8px 0;
            font-size: 14px;
            color: #333333;
        }
        .surat-info strong {
            color: #667eea;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            background-color: #667eea;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #764ba2;
        }
        .instructions {
            background-color: #e8f4f8;
            border-left: 4px solid #0099cc;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .instructions p {
            margin: 8px 0;
            font-size: 13px;
            color: #333333;
        }
        .instructions ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .instructions li {
            margin: 5px 0;
            font-size: 13px;
            color: #333333;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }
        .footer p {
            margin: 8px 0;
            font-size: 12px;
            color: #999999;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .important {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .important p {
            margin: 8px 0;
            font-size: 13px;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>✓ Surat Anda Sudah Siap!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <div class="greeting">
                Halo <strong><?= esc($nama) ?></strong>,
            </div>

            <!-- Main Message -->
            <div class="message">
                Kabar gembira! Pengajuan surat Anda telah selesai diproses oleh kantor desa. Surat <?= esc($jenis_surat) ?> Anda kini siap untuk diunduh.
            </div>

            <!-- Surat Info -->
            <div class="surat-info">
                <p><strong>Jenis Surat:</strong></p>
                <p><?= esc($jenis_surat) ?></p>
            </div>

            <!-- Download Instructions -->
            <div class="instructions">
                <p><strong>Cara Mengunduh Surat:</strong></p>
                <ol>
                    <li>Klik tombol "Unduh Surat" di bawah ini</li>
                    <li>File PDF akan otomatis terunduh ke perangkat Anda</li>
                    <li>Anda dapat membuka, mencetak, atau menyimpan file tersebut</li>
                </ol>
            </div>

            <!-- Download Button -->
            <div class="button-container">
                <a href="<?= esc($link_download) ?>" class="button">
                    Unduh Surat Sekarang
                </a>
            </div>

            <!-- Important Note -->
            <div class="important">
                <p>
                    <strong>📌 PENTING:</strong> Jika mengalami masalah saat mengunduh atau memiliki pertanyaan terkait surat Anda, 
                    silakan hubungi kantor desa kami atau kunjungi kembali website untuk informasi lebih lanjut.
                </p>
            </div>

            <!-- Closing -->
            <div class="message">
                Terima kasih telah menggunakan layanan online Desa Blanakan. Kami berkomitmen untuk memberikan pelayanan terbaik kepada Anda.
            </div>

            <div class="message">
                Salam hormat,<br>
                <strong>Pemerintah Desa Blanakan</strong>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                Email ini dikirim otomatis oleh sistem Informasi Desa Blanakan.
            </p>
            <p>
                Untuk informasi lengkap, kunjungi: 
                <a href="<?= base_url() ?>"><?= base_url() ?></a>
            </p>
            <p>
                &copy; 2026 Sistem Informasi Desa Blanakan. Semua hak dilindungi.
            </p>
        </div>
    </div>
</body>
</html>
