<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #0c5c8c;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #0c5c8c;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        .content {
            padding: 20px 0;
        }
        .greeting {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
        }
        .surat-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #0c5c8c;
            margin: 20px 0;
            border-radius: 4px;
        }
        .surat-info h3 {
            color: #0c5c8c;
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            color: #333;
        }
        .info-value {
            color: #666;
        }
        .button-group {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            margin: 10px 5px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            transition: all 0.3s;
        }
        .btn-primary {
            background-color: #0c5c8c;
            color: white;
        }
        .btn-primary:hover {
            background-color: #094a6d;
        }
        .btn-secondary {
            background-color: #e0e0e0;
            color: #333;
        }
        .btn-secondary:hover {
            background-color: #d0d0d0;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 12px;
            margin-top: 20px;
        }
        .footer p {
            margin: 5px 0;
        }
        .success-badge {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📄 Surat Anda Telah Selesai</h1>
            <p>Sistem Informasi Desa Blanakan</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                <p>Assalamu'alaikum Wr. Wb., <strong><?= $nama_warga ?></strong></p>
                <p>Kami dengan senang hati mengumumkan bahwa surat yang Anda ajukan telah selesai diproses dan siap untuk diunduh.</p>
            </div>

            <!-- Surat Information -->
            <div class="surat-info">
                <h3>✓ Informasi Surat Anda</h3>
                <div class="info-item">
                    <span class="info-label">Jenis Surat:</span>
                    <span class="info-value"><?= $jenis_surat ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Nomor Surat:</span>
                    <span class="info-value">#<?= $no_surat ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal Selesai:</span>
                    <span class="info-value"><?= $tanggal_selesai ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status:</span>
                    <span class="success-badge">SELESAI</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="button-group">
                <p style="color: #666; margin-bottom: 15px;">Anda dapat mengunduh surat Anda melalui link di bawah ini:</p>
                <a href="<?= $download_link ?>" class="btn btn-primary">📥 Unduh Surat PDF</a>
                <a href="<?= $dashboard_link ?>" class="btn btn-secondary">📊 Lihat Detail Surat</a>
            </div>

            <!-- Additional Information -->
            <div style="background-color: #f0f7ff; padding: 15px; border-radius: 4px; margin-top: 20px;">
                <p style="margin: 0; color: #0c5c8c; font-weight: bold;">ℹ️ Informasi Penting</p>
                <ul style="margin: 10px 0 0 0; padding-left: 20px; color: #333; font-size: 14px;">
                    <li>Surat ini dapat diunduh kapan saja melalui dashboard Anda</li>
                    <li>Simpan file PDF dengan baik untuk referensi di masa mendatang</li>
                    <li>Jika mengalami masalah, silakan hubungi kantor desa</li>
                </ul>
            </div>

            <p style="margin-top: 20px; color: #666;">
                Terima kasih telah menggunakan layanan online Desa Blanakan. Semoga surat ini bermanfaat bagi Anda.
            </p>
            <p style="color: #666;">
                Wassalamu'alaikum Wr. Wb.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Desa Blanakan - Sistem Informasi Digital</strong></p>
            <p>Jl. Raya Blanakan, Kabupaten Subang, Jawa Barat</p>
            <p>Email: <?= config('Email')->fromEmail ?></p>
            <p style="margin-top: 10px; font-style: italic;">Ini adalah email otomatis. Mohon jangan balas email ini.</p>
        </div>
    </div>
</body>
</html>
