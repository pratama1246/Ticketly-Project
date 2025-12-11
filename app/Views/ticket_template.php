<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; background-color: #f3f4f6; color: #333; margin: 0; padding: 0; }
        .wrapper { width: 100%; padding-bottom: 20px; }
        
        
        .ticket-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 12px;
            overflow: hidden;
            page-break-inside: avoid; 
        }
        
        .page-break { page-break-after: always; }

        .header { background-color: #0d6efd; padding: 20px; text-align: center; color: #fff; }
        .header h2 { margin: 0; font-size: 20px; text-transform: uppercase; letter-spacing: 2px; }
        .content { padding: 30px; }
        .event-title { font-size: 20px; margin: 0 0 20px; border-bottom: 2px solid #eee; padding-bottom: 15px; }
        
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 8px 0; vertical-align: top; font-size: 14px; }
        .label { color: #777; font-weight: bold; width: 130px; }
        .value { color: #000; font-weight: 500; }
        
        .seat-row td { border-top: 1px dashed #ddd; border-bottom: 1px dashed #ddd; padding: 15px 0; }
        .seat-highlight { color: #0d6efd; font-size: 18px; font-weight: bold; }
        
        .qr-section { text-align: center; background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #eee; }
        .qr-section img { width: 140px; height: 140px; mix-blend-mode: multiply; }
        .ticket-code { font-family: 'Courier New', monospace; letter-spacing: 2px; font-weight: bold; margin-top: 10px; }
        
        .footer { background: #333; color: #fff; text-align: center; padding: 10px; font-size: 11px; }
    </style>
</head>
<body>
    
    <?php foreach ($tickets as $index => $ticket): ?>
        
        <div class="wrapper">
            <div class="ticket-container">
                <div class="header">
                    <h2>E-TIKET RESMI</h2>
                    <p style="margin:5px 0 0; font-size:12px;">Order ID #<?= esc($order['trx_id']) ?></p>
                </div>

                <div class="content">
                    <h1 class="event-title"><?= esc($event['name']) ?></h1>
                    
                    <table class="info-table">
                        <tr>
                            <td class="label">PEMBELI</td>
                            <td class="value">: <?= esc($order['first_name'] . ' ' . $order['last_name']) ?></td>
                        </tr>
                        <tr>
                            <td class="label">TANGGAL</td>
                            <td class="value">: <?= date('d F Y', strtotime($event['event_date'])) ?></td>
                        </tr>
                        <tr>
                            <td class="label">WAKTU</td>
                            <td class="value">: <?= date('H:i', strtotime($event['event_date'])) ?> WIB</td>
                        </tr>
                        <tr>
                            <td class="label">LOKASI</td>
                            <td class="value">: <?= esc($event['venue']) ?></td>
                        </tr>
                        <tr>
                            <td class="label">KATEGORI</td>
                            <td class="value">: 
                                <span style="background:#e7f1ff; color:#0d6efd; padding:2px 8px; border-radius:4px; font-weight:bold;">
                                    <?= esc($ticket['type']) ?> </span>
                            </td>
                        </tr>
                        
                        <tr class="seat-row">
                            <td class="label" style="vertical-align: middle;">NOMOR KURSI</td>
                            <td class="value seat-highlight">: <?= esc($ticket['seat']) ?></td> 
                        </tr>
                    </table>

                    <div class="qr-section">
                        <p style="margin:0 0 10px; font-size:12px; color:#888;">Scan di pintu masuk</p>
                        <img src="data:image/png;base64,<?= $ticket['qr'] ?>" alt="QR Code">
                        <p class="ticket-code"><?= esc($ticket['code']) ?></p>
                    </div>
                </div>

                <div class="footer">
                    &copy; <?= date('Y') ?> Ticketly System. Tiket <?= $index + 1 ?> dari <?= count($tickets) ?>
                </div>
            </div>
        </div>

        <?php if ($index < count($tickets) - 1): ?>
            <div class="page-break"></div>
        <?php endif; ?>

    <?php endforeach; ?>
    </body>
</html>