<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Surat - <?= esc($surat['jenis_surat'] ?? '') ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
            background: #fff;
            padding: 2cm;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 5px 0;
            text-transform: uppercase;
        }
        
        .header h2 {
            font-size: 16pt;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .header p {
            font-size: 11pt;
            margin: 2px 0;
        }
        
        .nomor-surat {
            text-align: center;
            margin: 20px 0;
            text-decoration: underline;
            font-weight: bold;
        }
        
        .content {
            margin: 30px 0;
        }
        
        .content table {
            width: 100%;
            margin: 20px 0;
        }
        
        .content table td {
            padding: 5px;
            vertical-align: top;
        }
        
        .content table td:first-child {
            width: 180px;
        }
        
        .content table td:nth-child(2) {
            width: 20px;
        }
        
        .footer {
            margin-top: 50px;
        }
        
        .signature {
            margin-top: 30px;
            float: right;
            text-align: center;
            width: 250px;
        }
        
        .signature-space {
            height: 80px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
            
            @page {
                margin: 2cm;
            }
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #0ea5e9;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 14px;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #0284c7;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 10pt;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-proses {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .status-selesai {
            background: #dcfce7;
            color: #166534;
        }
        
        .status-ditolak {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <button class="print-button no-print" onclick="window.print()">
        🖨️ Cetak Surat
    </button>

    <!-- Header Surat -->
    <div class="header">
        <h1>PEMERINTAH DESA TANJUNG BARU</h1>
        <h2>KECAMATAN BLANAKAN</h2>
        <p>Jalan Raya Blanakan No. 123, Blanakan, Subang 41254</p>
        <p>Telp: (0260) 123456 | Email: desatanjungbaru@blanakan.go.id</p>
    </div>

    <?php
    // Status mapping
    $status = $surat['status'] ?? 'menunggu';
    $statusClass = '';
    $statusText = '';
    
    switch ($status) {
        case 'selesai':
        case 'disetujui':
            $statusClass = 'status-selesai';
            $statusText = 'SELESAI';
            break;
        case 'diproses':
            $statusClass = 'status-proses';
            $statusText = 'SEDANG DIPROSES';
            break;
        case 'ditolak':
            $statusClass = 'status-ditolak';
            $statusText = 'DITOLAK';
            break;
        default:
            $statusClass = 'status-pending';
            $statusText = 'MENUNGGU VERIFIKASI';
    }
    
    // Format jenis surat
    $jenisSurat = ucwords(str_replace('_', ' ', $surat['jenis_surat'] ?? ''));
    ?>

    <!-- Status Badge (No Print) -->
    <div class="no-print" style="text-align: center;">
        <span class="status-badge <?= $statusClass ?>">
            STATUS: <?= $statusText ?>
        </span>
    </div>

    <!-- Nomor Surat -->
    <div class="nomor-surat">
        <p>SURAT <?= strtoupper(esc($jenisSurat)) ?></p>
        <?php if (!empty($surat['nomor_surat'])): ?>
            <p>Nomor: <?= esc($surat['nomor_surat']) ?></p>
        <?php else: ?>
            <p>Nomor: [Akan diterbitkan]</p>
        <?php endif; ?>
    </div>

    <!-- Content -->
    <div class="content">
        <p>Yang bertanda tangan di bawah ini, Kepala Desa Tanjung Baru, Kecamatan Blanakan, Kabupaten Subang, menerangkan bahwa:</p>
        
        <table>
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td><strong><?= esc($surat['nama_lengkap'] ?? '-') ?></strong></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td><?= esc($surat['nik'] ?? '-') ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><?= esc($surat['alamat'] ?? '-') ?></td>
            </tr>
            <tr>
                <td>Jenis Surat</td>
                <td>:</td>
                <td><?= esc($jenisSurat) ?></td>
            </tr>
            <?php if (!empty($surat['keperluan'])): ?>
            <tr>
                <td>Keperluan</td>
                <td>:</td>
                <td><?= nl2br(esc($surat['keperluan'])) ?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <td>Tanggal Pengajuan</td>
                <td>:</td>
                <td><?= date('d F Y', strtotime($surat['created_at'] ?? 'now')) ?></td>
            </tr>
        </table>

        <?php if ($status === 'selesai' || $status === 'disetujui'): ?>
        <p style="margin-top: 30px;">
            Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.
        </p>
        <?php elseif ($status === 'ditolak'): ?>
        <p style="margin-top: 30px; color: #991b1b;">
            <strong>CATATAN:</strong> Permohonan surat ini ditolak.
            <?php if (!empty($surat['pesan_admin'])): ?>
            <br>Alasan: <?= nl2br(esc($surat['pesan_admin'])) ?>
            <?php endif; ?>
        </p>
        <?php else: ?>
        <p style="margin-top: 30px; color: #92400e;">
            <em>Surat ini masih dalam proses verifikasi. Cetak final akan tersedia setelah disetujui.</em>
        </p>
        <?php endif; ?>
    </div>

    <!-- Footer with Signature -->
    <?php if ($status === 'selesai' || $status === 'disetujui'): ?>
    <div class="footer">
        <div class="signature">
            <p>Blanakan, <?= date('d F Y') ?></p>
            <p><strong>Kepala Desa Tanjung Baru</strong></p>
            <div class="signature-space"></div>
            <p><strong><u>H. Ahmad Subarjo, S.Sos</u></strong></p>
            <p>NIP. 196801011990031001</p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Script untuk auto-print (optional) -->
    <script>
        // Auto-focus untuk print
        window.onload = function() {
            // Uncomment baris di bawah jika ingin auto-print
            // window.print();
        };
        
        // Shortcut keyboard: Ctrl+P atau Cmd+P
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
</body>
</html>
