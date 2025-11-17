<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            border: 2px solid #ddd;
            border-radius: 10px;
            overflow: hidden; /* Penting agar radiusnya terlihat */
        }
        .header {
            background-color: #f4f4f4;
            padding: 20px;
            border-bottom: 2px solid #ddd;
            text-align: center;
        }
        .header img {
            max-width: 150px;
        }
        .content {
            padding: 30px;
        }
        .content h1 {
            color: #0d6efd; /* Biru */
            font-size: 24px;
            margin-top: 0;
        }
        .qr-section {
            text-align: center;
            padding-top: 20px;
            border-top: 1px dashed #ccc;
            margin-top: 30px;
        }
        .qr-section img {
            width: 150px;
            height: 150px;
        }
        .info-table {
            width: 100%;
            margin-top: 20px;
        }
        .info-table td {
            padding: 10px;
            vertical-align: top;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 120px;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
        }
    </style>
</head>
    <body>
        <div class="container">
            <div class="header">
                <h2>E-TIKET RESMI</h2>
            </div>
            <div class="content">
                <h1><?= esc($eventName) ?></h1>
                
                <table class="info-table">
                    <tr>
                        <td>DIBELI OLEH</td>
                        <td>: <?= esc($buyerName) ?></td>
                    </tr>
                    <tr>
                        <td>TANGGAL</td>
                        <td>: <?= esc($eventDate) ?></td>
                    </tr>
                    <tr>
                        <td>TEMPAT</td>
                        <td>: <?= esc($venue) ?></td>
                    </tr>
                    <tr>
                        <td>TIKET</td>
                        <td>: <?= esc($ticketType) ?> (<?= esc($quantity) ?>x)</td>
                    </tr>
                    <tr>
                        <td>NOMOR PESANAN</td>
                        <td>: #<?= esc($orderId) ?></td>
                    </tr>
                </table>

                <div class="qr-section">
                    <p>Pindai kode QR ini di pintu masuk</p>
                    
                    <img src="data:image/png;base64,<?= $qrCodeBase64 ?>" alt="QR Code">
                    
                    <p style="font-size: 12px; color: #555;"><?= esc($ticketCode) ?></p>
                </div>
            </div>
            <div class="footer">
                Terima kasih telah melakukan pembelian di Ticketly.
            </div>
        </div>
    </body>
    </html>
