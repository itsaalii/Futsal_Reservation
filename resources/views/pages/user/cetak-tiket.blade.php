<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket / Resi Reservasi - {{ $reservasi->id }}</title>
    <!-- Google Font: IBM Plex Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #F2F5F7;
            margin: 0;
            padding: 24px;
            color: #18232F;
        }
        .ticket-container {
            max-width: 560px;
            margin: 0 auto;
            background: #fff;
            padding: 32px;
            border-radius: 16px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #CBD5E1;
            padding-bottom: 20px;
            margin-bottom: 24px;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            color: #18232F;
            letter-spacing: 1px;
        }
        .header p {
            margin: 4px 0 0;
            font-size: 13px;
            color: #64748B;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 9999px;
            background-color: #E7F4F2;
            color: #0F766E;
            font-weight: 700;
            font-size: 12px;
            margin-top: 12px;
            border: 1px solid #97CFC7;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .details-table th, .details-table td {
            text-align: left;
            padding: 10px 0;
            border-bottom: 1px solid #F1F5F9;
        }
        .details-table th {
            color: #64748B;
            width: 42%;
            font-size: 13px;
            font-weight: 500;
        }
        .details-table td {
            font-weight: 700;
            font-size: 14px;
            color: #18232F;
        }
        .total-row th, .total-row td {
            border-top: 2px solid #18232F;
            border-bottom: none;
            padding-top: 16px;
            font-size: 16px;
        }
        .total-row td {
            color: #0F766E;
            font-size: 18px;
            font-weight: 800;
        }
        .footer {
            text-align: center;
            margin-top: 24px;
            font-size: 11px;
            color: #94A3B8;
            line-height: 1.5;
        }
        .btn-print {
            display: block;
            width: 200px;
            margin: 24px auto 0;
            padding: 12px 20px;
            background-color: #0F766E;
            color: #fff;
            text-align: center;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-print:hover {
            background-color: #0B5F58;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .ticket-container {
                box-shadow: none;
                border: 1px solid #E2E8F0;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="ticket-container">
        <div class="header">
            <h1>JAYA FUTSAL</h1>
            <p>Bukti Resmi Reservasi Lapangan</p>
            <div class="status-badge">✓ LUNAS / TERKONFIRMASI</div>
        </div>

        <table class="details-table">
            <tr>
                <th>No ID Reservasi</th>
                <td>#RSV-{{ str_pad($reservasi->id, 5, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <th>Nama Pemesan</th>
                <td>{{ $reservasi->user->nama ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Lapangan</th>
                <td>{{ $reservasi->lapangan ? $reservasi->lapangan->nama : 'Lapangan Utama' }}</td>
            </tr>
            <tr>
                <th>Tanggal Main</th>
                <td>{{ \Carbon\Carbon::parse($reservasi->tanggal)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <th>Waktu Sesi</th>
                <td>{{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }} WIB</td>
            </tr>
            <tr>
                <th>Waktu Transaksi</th>
                <td>{{ $reservasi->created_at->format('d M Y, H:i') }} WIB</td>
            </tr>
            @if($reservasi->discount_amount > 0)
            <tr>
                <th>Diskon Member (15%)</th>
                <td style="color: #E11D48;">-Rp{{ number_format($reservasi->discount_amount, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <th>Total Pembayaran</th>
                <td>Rp{{ number_format($reservasi->total_harga, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="footer">
            <p>Terima kasih telah mempercayakan Jaya Futsal. Harap tunjukkan bukti ini kepada petugas lapangan saat kickoff dimulai.</p>
            <p>&copy; {{ date('Y') }} Jaya Futsal. All rights reserved.</p>
        </div>
    </div>

    <button class="btn-print" onclick="window.print()">Cetak / Simpan PDF</button>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>