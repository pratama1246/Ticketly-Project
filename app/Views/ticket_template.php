<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - <?= esc($eventName) ?></title>
    <style>
        /* Reset & Base Styles */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6; /* Light Gray Background */
            color: #333333;
            -webkit-font-smoothing: antialiased;
        }
        
        /* Main Container - Card Style */
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f3f4f6;
            padding-bottom: 40px;
        }

        .ticket-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Shadow halus */
            border: 1px solid #e5e7eb;
        }

        /* Header Section */
        .header {
            background-color: #0d6efd; /* Primary Blue */
            padding: 30px 40px;
            text-align: center;
            color: #ffffff;
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        /* Content Section */
        .content {
            padding: 40px;
        }

        .event-title {
            color: #1a1a1a;
            font-size: 22px;
            font-weight: 800;
            margin-top: 0;
            margin-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
        }

        /* Table Styling */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 8px 0;
            vertical-align: top;
            font-size: 15px;
        }

        .label {
            color: #6c757d; /* Gray text for labels */
            font-weight: 600;
            width: 140px;
        }

        .value {
            color: #212529; /* Dark text for values */
            font-weight: 500;
        }

        /* Highlight Seat Number */
        .seat-row td {
            padding-top: 15px;
            padding-bottom: 15px;
            border-top: 1px dashed #e5e7eb;
            border-bottom: 1px dashed #e5e7eb;
        }
        
        .seat-highlight {
            color: #0d6efd;
            font-weight: 800;
            font-size: 18px;
        }

        /* QR Code Section */
        .qr-section {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .qr-section img {
            width: 160px;
            height: 160px;
            margin-bottom: 10px;
            mix-blend-mode: multiply; /* Biar background putih QR nyatu */
        }

        .ticket-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            color: #495057;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 0;
        }

        .scan-hint {
            font-size: 12px;
            color: #adb5bd;
            margin-bottom: 15px;
        }

        /* Footer */
        .footer {
            background-color: #343a40; /* Dark footer */
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }
        
        .footer a {
            color: #0d6efd;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <br>
        <div class="ticket-container">
            <div class="header">
                <h2>E-TIKET RESMI</h2>
                <p>Order ID #<?= esc($orderId) ?></p>
            </div>

            <div class="content">
                <h1 class="event-title"><?= esc($eventName) ?></h1>
                
                <table class="info-table">
                    <tr>
                        <td class="label">PEMBELI</td>
                        <td class="value">: <?= esc($buyerName) ?></td>
                    </tr>
                    <tr>
                        <td class="label">TANGGAL</td>
                        <td class="value">: <?= date('d F Y', strtotime($eventDate)) ?></td> </tr>
                    <tr>
                        <td class="label">WAKTU</td>
                        <td class="value">: <?= date('H:i', strtotime($eventDate)) ?> WIB</td>
                    </tr>
                    <tr>
                        <td class="label">LOKASI</td>
                        <td class="value">: <?= esc($venue) ?></td>
                    </tr>
                    <tr>
                        <td class="label">KATEGORI</td>
                        <td class="value">: <span style="background: #e7f1ff; color: #0d6efd; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;"><?= esc($ticketType) ?></span></td>
                    </tr>
                    
                    <tr class="seat-row">
                        <td class="label" style="vertical-align: middle;">NOMOR KURSI</td>
                        <td class="value seat-highlight">: <?= esc($seatNumber) ?></td> 
                    </tr>
                    </table>

                <div class="qr-section">
                    <p class="scan-hint">Tunjukkan QR Code ini kepada petugas saat masuk</p>
                    
                    <img src="data:image/png;base64,<?= $qrCodeBase64 ?>" alt="QR Code">
                    
                    <p class="ticket-code"><?= esc($ticketCode) ?></p>
                </div>
            </div>

            <div class="footer">
                <p>&copy; <?= date('Y') ?> Ticketly System. All rights reserved.</p>
                <p style="color: #6c757d; margin-top: 5px;">Harap simpan tiket ini dengan aman. Jangan bagikan QR Code kepada orang lain.</p>
            </div>
        </div>
        <br>
    </div>
</body>
</html>