<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Parkir - {{ $transaksi->kendaraan->plat_nomor ?? '-' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 30px 10px;
        }

        .struk {
            background: #fff;
            width: 300px;
            padding: 20px 18px;
            border-radius: 4px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.12);
        }

        .header {
            text-align: center;
            margin-bottom: 14px;
        }
        .header h2 {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .header p {
            font-size: 11px;
            color: #555;
            margin-top: 2px;
        }

        .divider {
            border: none;
            border-top: 1px dashed #999;
            margin: 10px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .row .label {
            color: #555;
            font-size: 12px;
            flex: 1;
        }
        .row .value {
            font-weight: bold;
            font-size: 12px;
            text-align: right;
            flex: 1;
        }

        .total-box {
            background: #f7f7f7;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px 12px;
            margin: 12px 0;
            text-align: center;
        }
        .total-box .label-total {
            font-size: 11px;
            color: #777;
            margin-bottom: 4px;
        }
        .total-box .amount {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            color: #777;
            margin-top: 12px;
        }

        .badge-status {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            background: #198754;
            color: #fff;
            letter-spacing: 0.5px;
        }

        .btn-print {
            display: block;
            width: 300px;
            margin: 16px auto 0;
            padding: 10px;
            background: #0d6efd;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            font-family: inherit;
            letter-spacing: 0.5px;
        }
        .btn-print:hover { background: #0b5ed7; }

        @media print {
            body { background: #fff; padding: 0; }
            .struk { box-shadow: none; }
            .btn-print { display: none; }
        }
    </style>
</head>
<body>

<div>
    <div class="struk">
        {{-- Header --}}
        <div class="header">
            <h2>&#x1F17F; Struk Parkir</h2>
            <p>Sistem Parkir Otomatis</p>
            <p>{{ now()->format('d M Y, H:i') }} WIB</p>
        </div>

        <hr class="divider">

        {{-- Info Kendaraan --}}
        <div class="row">
            <span class="label">No. Transaksi</span>
            <span class="value">#{{ str_pad($transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="row">
            <span class="label">Plat Nomor</span>
            <span class="value">{{ $transaksi->kendaraan->plat_nomor ?? '-' }}</span>
        </div>
        <div class="row">
            <span class="label">Warna</span>
            <span class="value">{{ $transaksi->kendaraan->warna ?? '-' }}</span>
        </div>
        <div class="row">
            <span class="label">Jenis</span>
            <span class="value">{{ $transaksi->tarif->jenis_kendaraan ?? '-' }}</span>
        </div>
        <div class="row">
            <span class="label">Area Parkir</span>
            <span class="value">{{ $transaksi->area->nama_area ?? '-' }}</span>
        </div>

        <hr class="divider">

        {{-- Waktu --}}
        <div class="row">
            <span class="label">Waktu Masuk</span>
            <span class="value">{{ $transaksi->waktu_masuk ? \Carbon\Carbon::parse($transaksi->waktu_masuk)->format('d/m/Y H:i') : '-' }}</span>
        </div>
        <div class="row">
            <span class="label">Waktu Keluar</span>
            <span class="value">{{ $transaksi->waktu_keluar ? \Carbon\Carbon::parse($transaksi->waktu_keluar)->format('d/m/Y H:i') : '-' }}</span>
        </div>
        <div class="row">
            <span class="label">Durasi</span>
            <span class="value">
                @if($transaksi->durasi > 0)
                    {{ intdiv($transaksi->durasi, 60) }} jam {{ $transaksi->durasi % 60 }} menit
                @else
                    -
                @endif
            </span>
        </div>

        <hr class="divider">

        {{-- Tarif --}}
        <div class="row">
            <span class="label">Tarif/jam</span>
            <span class="value">Rp {{ number_format($transaksi->tarif->tarif_per_jam ?? 0, 0, ',', '.') }}</span>
        </div>
        @php
            $jamBulat = $transaksi->durasi > 0 ? ceil($transaksi->durasi / 60) : 1;
            if ($jamBulat < 1) $jamBulat = 1;
        @endphp
        <div class="row">
            <span class="label">Jumlah Jam</span>
            <span class="value">{{ $jamBulat }} jam</span>
        </div>

        {{-- Total --}}
        <div class="total-box">
            <div class="label-total">TOTAL BAYAR</div>
            <div class="amount">Rp {{ number_format($transaksi->biaya_total, 0, ',', '.') }}</div>
        </div>

        <hr class="divider">

        {{-- Status & Footer --}}
        <div style="text-align:center; margin-bottom:8px;">
            <span class="badge-status">{{ strtoupper($transaksi->status) }}</span>
        </div>

        <div class="footer">
            <p>Terima kasih telah menggunakan</p>
            <p>layanan parkir kami.</p>
            <p style="margin-top:6px;">— Simpan struk ini sebagai bukti —</p>
        </div>
    </div>

    <button class="btn-print" onclick="window.print()">
        &#128438; Cetak Struk
    </button>
</div>
<script>
    window.print();

    // setelah print ditutup → kembali ke halaman transaksi
    window.onafterprint = function() {
        window.location.href = "{{ route('transaksi.index') }}";
    };

    // fallback kalau onafterprint tidak jalan
    setTimeout(() => {
        window.location.href = "{{ route('transaksi.index') }}";
    }, 3000);
</script>
</body>
</html>