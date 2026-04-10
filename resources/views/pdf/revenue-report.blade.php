<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Revenue Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #3A345B;
        }
        .header p {
            margin: 5px 0;
            color: #777;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }
        th {
            background-color: #f4f4f4;
            color: #3A345B;
            font-weight: bold;
            text-align: center;
        }
        .event-header {
            background-color: #e9ecef;
            font-weight: bold;
            text-align: left;
            font-size: 14px;
        }
        .text-left {
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .summary-box {
            float: right;
            border: 1px solid #ddd;
            padding: 15px;
            background: #f9f9f9;
            width: 300px;
        }
        .summary-row {
            display: block;
            margin-bottom: 8px;
        }
        .summary-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .summary-value {
            display: inline-block;
            text-align: right;
            width: 120px;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>TIXORA - Revenue Report</h1>
        <p>Tanggal Export: {{ date('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="20%">Jenis Tiket</th>
                <th width="15%">Harga Satuan</th>
                <th width="10%">Terjual</th>
                <th width="20%">Total Penjualan Kotor</th>
                <th width="15%">Pendapatan Admin (90%)</th>
                <th width="20%">Pendapatan Organizer (10%)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $eventName => $tickets)
                <tr>
                    <td colspan="6" class="event-header">{{ $eventName }}</td>
                </tr>
                @foreach($tickets as $ticket)
                    <tr>
                        <td class="text-left">{{ $ticket['jenis_tiket'] }}</td>
                        <td>Rp {{ number_format($ticket['harga'], 0, ',', '.') }}</td>
                        <td class="text-center">{{ number_format($ticket['total_sold'], 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($ticket['gross_sales'], 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($ticket['admin_share'], 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($ticket['organizer_share'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data penjualan tiket.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="clearfix">
        <div class="summary-box">
            <div class="summary-row" style="font-size: 14px; margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">
                <strong>Ringkasan Total Semua Event</strong>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Penjualan Kotor:</span>
                <span class="summary-value">Rp {{ number_format($totalGrossAll, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Jatah Admin:</span>
                <span class="summary-value" style="color: #4CAF50;">Rp {{ number_format($totalAdminAll, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Jatah Organizer:</span>
                <span class="summary-value">Rp {{ number_format($totalOrganizerAll, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

</body>
</html>
