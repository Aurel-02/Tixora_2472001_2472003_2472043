<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Ticket Tixora - {{ $eventName }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .email-wrapper {
            background-color: #3A345B;
            width: 100%;
            padding: 40px 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .email-header {
            background-color: #1A153A;
            padding: 30px;
            text-align: center;
            color: #F3C8DD;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .email-body {
            padding: 40px 30px;
            color: #333333;
            text-align: center;
        }
        .greeting {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #4B1535;
        }
        .event-title {
            font-size: 28px;
            font-weight: 800;
            color: #D183A9;
            margin-bottom: 30px;
        }
        .barcode-section {
            background-color: #f9f9f9;
            border: 2px dashed #D183A9;
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            display: inline-block;
        }
        .barcode-img {
            max-width: 100%;
            height: auto;
        }
        .ticket-code {
            margin-top: 15px;
            font-size: 18px;
            font-family: monospace;
            font-weight: bold;
            letter-spacing: 3px;
            color: #71557A;
        }
        .instructions {
            font-size: 14px;
            line-height: 1.6;
            color: #666;
            margin-top: 30px;
        }
        .email-footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="email-header">
                <h1>TIXORA E-TICKET</h1>
            </div>
            
            <div class="email-body">
                <div class="greeting">Transaksi Berhasil!</div>
                <p>Terima kasih telah melakukan pembelian di platform kami. Berikut adalah e-ticket untuk partisipasi Anda pada event:</p>
                
                <div class="event-title">{{ $eventName }}</div>
                
                <div class="barcode-section">
                    <!-- Menggunakan public API QRServer untuk men-generate Barcode (khususnya QR Code) -->
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($randomCode) }}" alt="Ticket QR Code" class="barcode-img">
                    <div class="ticket-code">{{ $randomCode }}</div>
                </div>
                
                <div class="instructions">
                    <p><strong>Harap simpan email ini.</strong></p>
                    <p>Tunjukkan QR Code di atas pada bagian loket Check in saat acara berlangsung untuk ditukarkan dengan tiket fisik atau akses masuk.</p>
                </div>
            </div>
            
            <div class="email-footer">
                &copy; {{ date('Y') }} Tixora. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
