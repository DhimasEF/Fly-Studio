<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice <?= $transaction->id_transaction ?></title>
    <style>
        :root {
            --first-color: #323946;
            --second-color: #0ef;
            --white-color: #EDEDED;
            --yellow-color: #918cff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            font-size: 10px;
            color: var(--white-color);
            background-color: var(--first-color);
            margin: 0;
            padding: 40px;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/png;base64,<?= $logo_base64 ?>');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 60%;
            opacity: 0.05; /* brightness reduction */
            z-index: 0;
        }

        * {
            position: relative;
            z-index: 1;
        }

        h2, h3 {
            color: var(--second-color);
            margin-bottom: 10px;
        }

        .section {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .label {
            color: var(--yellow-color);
            font-weight: bold;
        }

        p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid var(--second-color);
            padding: 10px;
            text-align: left;
            color: var(--white-color);
        }

        thead {
            background-color: rgba(0, 255, 255, 0.08);
        }

        .qr {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            border: 2px dashed var(--yellow-color);
            border-radius: 10px;
            width: 240px;
            margin-left: auto;
            margin-right: auto;
            background: rgba(0, 0, 0, 0.1);
        }

        .qr p {
            color: var(--yellow-color);
            font-weight: bold;
            margin-bottom: 10px;
        }

        img {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<h2>Invoice Transaksi</h2>

<div class="section">
    <p><span class="label">ID Transaksi:</span> <?= $transaction->id_transaction ?></p>
    <p><span class="label">Orderer:</span> <?= $transaction->orderer_name ?></p>
    <p><span class="label">Worker:</span> <?= $transaction->worker_name ?></p>
    <p><span class="label">Status Order:</span> <?= ucwords(str_replace('_', ' ', $transaction->order_status)) ?></p>
    <p><span class="label">Password Order:</span> <?= $transaction->password ?></p>
    <p><span class="label">File Order:</span> <?= $transaction->order_file_url ?></p>
</div>

<h3>Detail Pembayaran</h3>
<div class="section">
    <table>
        <tr>
            <th>Total Harga</th>
            <td>Rp <?= number_format($transaction->total_price, 0, ',', '.') ?></td>
        </tr>
        <tr>
            <th>Total Dibayar</th>
            <td>Rp <?= number_format($transaction->total_paid, 0, ',', '.') ?></td>
        </tr>
        <tr>
            <th>Status Pembayaran</th>
            <td><?= $is_paid ? 'Lunas' : 'Belum Lunas' ?></td>
        </tr>
    </table>
</div>

<?php if (!empty($payments)): ?>
    <h3>Riwayat Pembayaran</h3>
    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>ID Pembayaran</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td><?= $payment->id_payment ?></td>
                        <td>Rp <?= number_format($payment->amount, 0, ',', '.') ?></td>
                        <td><?= ucfirst($payment->payment_status) ?></td>
                        <td><?= date('d M Y, H:i', strtotime($payment->payment_date)) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<div class="qr">
    <p>Scan QR untuk info penting</p>
    <?php if (!empty($qr_base64)): ?>
        <img src="data:image/png;base64,<?= $qr_base64 ?>" width="160" alt="QR Code">
    <?php endif; ?>
</div>

</body>
</html>
