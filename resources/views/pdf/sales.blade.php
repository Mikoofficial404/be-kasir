<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 2em;
            letter-spacing: 2px;
        }
        .header .subtitle {
            font-size: 1.1em;
            color: #555;
            margin-bottom: 10px;
        }
        .divider {
            border-bottom: 2px solid #333;
            margin-bottom: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background: #f2f2f2;
            color: #222;
            font-weight: bold;
        }
        th, td {
            border: 1px solid #000;
            padding: 10px 8px;
            text-align: left;
        }
        tbody tr:nth-child(even) {
            background: #fafafa;
        }
        tbody tr:nth-child(odd) {
            background: #fff;
        }
        .footer {
            margin-top: 30px;
            font-size: 0.95em;
            color: #666;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- Ganti src logo jika ada logo perusahaan -->
        <!-- <img src="logo.png" alt="Logo" height="60"> -->
        <h1>Sales Report</h1>
        <div class="subtitle">Laporan Penjualan</div>
    </div>
    <div class="divider"></div>
    <table>
        <thead>
            <tr>
                <th>Sales Date</th>
                <th>Total Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
            <tr>
                <td>{{ $sale->sales_date }}</td>
                <td>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                <td>{{ $sale->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        Printed at: {{ date('d M Y H:i') }}
    </div>
</body>
</html>
