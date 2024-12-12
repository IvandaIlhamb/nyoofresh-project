<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <h1>Rekap Dropping</h1>
    <p>Dibuat Tanggal: {{ now()->isoformat('D MMMM Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Tanggal Produk Masuk</th>
                <th>Produk</th>
                <th>Nama Supplier</th>
                <th>Jumlah Suplai</th>
                <th>Terjual</th>
                <th>Kembali</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hasilPenjualan as $hasil)
                <tr>
                    <td>{{ $hasil->suplai->tanggal ? \Carbon\Carbon::parse($hasil->suplai->tanggal)->isoformat('D MMMM Y') : '-' }}</td>
                    <td>{{ $hasil->suplai->produk->nama_produk }}</td>
                    <td>{{ $hasil->suplai->nama_supplier }}</td>
                    <td>{{ $hasil->suplai->jumlah_suplai }}</td>
                    <td>{{ $hasil->terjual }}</td>
                    <td>{{ $hasil->kembali }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Hasil penjualan ini dibuat secara otomatis oleh sistem. {{ now()->isoformat('D MMMM Y, HH:mm:ss') }}</p>
    </div>
</body>
</html>
