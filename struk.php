<?php
session_start();

// Periksa apakah ada data pesanan yang dikirimkan melalui session
if (empty($_SESSION['receipt_data'])) {
    // Jika tidak ada data, arahkan kembali ke halaman utama
    header("Location: index.php");
    exit;
}

// Ambil data struk dari session
$receipt_data = $_SESSION['receipt_data'];
$id_pesanan = $receipt_data['id_pesanan'];
$nama = $receipt_data['nama'];
$email = $receipt_data['email'];
$telepon = $receipt_data['telepon'];
$alamat = $receipt_data['alamat'];
$receipt_items = $receipt_data['items'];
$grandTotal = $receipt_data['grand_total'];
$subtotal_produk = $receipt_data['subtotal_produk'];
$diskon = $receipt_data['diskon'];
$metode_pembayaran = $receipt_data['metode_pembayaran'] ?? 'Tidak diketahui';

// Hapus data struk dari session agar tidak bisa diakses lagi setelah refresh
unset($_SESSION['receipt_data']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">

    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .main-receipt {
            padding-top: 5rem;
            padding-bottom: 5rem;
        }
        .receipt-container {
            max-width: 768px;
            margin: auto;
            padding: 2rem;
            border-radius: 1.5rem;
            background: #f9f9f9;
            box-shadow: 0 4px 16px hsla(220, 32%, 8%, .1);
            font-family: 'Courier New', Courier, monospace;
            color: #333;
            border: 1px dashed #ccc;
        }
        .receipt-container h2 {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .receipt-header p {
            font-size: 0.8rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .receipt-info p {
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }
        .receipt-info strong {
            display: inline-block;
            width: 120px;
        }
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            margin-top: 1rem;
        }
        .receipt-table th, .receipt-table td {
            text-align: left;
            padding: 0.5rem 0;
            border: none;
        }
        .receipt-table tr.receipt-total td {
            font-weight: bold;
            font-size: 1rem;
            padding-top: 1rem;
            border-top: 1px dashed #ccc;
        }
        .receipt-table td:nth-child(2),
        .receipt-table td:nth-child(3),
        .receipt-table td:nth-child(4) {
            text-align: right;
        }
        .receipt-table th:nth-child(2),
        .receipt-table th:nth-child(3),
        .receipt-table th:nth-child(4) {
            text-align: right;
        }
        .receipt-action {
            text-align: center;
            margin-top: 2.5rem;
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        .receipt-discount {
            color: green;
        }
        .qr-code-container {
            text-align: center;
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .qr-code-container h3 {
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
        }
        .qr-code-container p {
            font-size: 0.8rem;
            margin-top: 0;
            margin-bottom: 1rem;
        }
        @media print {
            .receipt-action {
                display: none;
            }
            body {
                background: none;
                padding: 0;
                margin: 0;
            }
            .main-receipt {
                padding: 1rem;
            }
            .receipt-container {
                width: 100%;
                max-width: none;
                border: none;
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
    <title>Struk Pembayaran</title>
</head>
<body>
    <main class="main-receipt">
        <div class="receipt-container container">
            <div class="receipt-header">
                <h2>Struk Pesanan</h2>
                <p>--- TERIMA KASIH TELAH BERBELANJA ---</p>
            </div>
            <div class="receipt-info">
                <p><strong>Tanggal:</strong> <?php echo date("d-m-Y H:i:s"); ?></p>
                <p><strong>No. Pesanan:</strong> <?php echo htmlspecialchars($id_pesanan); ?></p>
                <p><strong>Nama:</strong> <?php echo htmlspecialchars($nama); ?></p>
                <p><strong>Alamat:</strong> <?php echo htmlspecialchars($alamat); ?></p>
                <p><strong>Metode Pembayaran:</strong> <?php echo ucwords(htmlspecialchars($metode_pembayaran)); ?></p>
            </div>
            
            <div class="receipt-details">
                <table class="receipt-table">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Jml</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($receipt_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nama']); ?></td>
                            <td><?php echo htmlspecialchars($item['jumlah']); ?></td>
                            <td><?php echo number_format($item['harga_satuan'], 0, ',', '.'); ?></td>
                            <td><?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <tr>
                            <td colspan="3">Subtotal Produk:</td>
                            <td style="text-align:right;">Rp <?php echo number_format($subtotal_produk, 0, ',', '.'); ?></td>
                        </tr>
                        <?php if ($diskon > 0): ?>
                        <tr>
                            <td colspan="3">Diskon Member (10%):</td>
                            <td class="receipt-discount" style="text-align:right;">- Rp <?php echo number_format($diskon, 0, ',', '.'); ?></td>
                        </tr>
                        <?php endif; ?>

                        <tr class="receipt-total">
                            <td colspan="3">Total Keseluruhan:</td>
                            <td>Rp <?php echo number_format($grandTotal, 0, ',', '.'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="receipt-action">
                <a href="index.php" class="button">Kembali ke Beranda</a>
                <button onclick="window.print()" class="button">Cetak Struk</button>
            </div>

            <?php if ($metode_pembayaran == 'transfer'): ?>
                <div class="qr-code-container">
                    <h3>QR Code Pembayaran</h3>
                    <p>Silakan pindai kode QR ini untuk menyelesaikan pembayaran:</p>
                    <img src="assets/img/pembayaran_qrcode.png" <?php echo $id_pesanan; ?>_<?php echo $grandTotal; ?>
                         alt="QR Code Pembayaran" style="width: 150px; height: 150px;">
                    <p style="font-size: 0.8rem; margin-top: 10px;">
                        **Pastikan total pembayaran sesuai: Rp <?php echo number_format($grandTotal, 0, ',', '.'); ?>**
                        <br>
                        (Ganti URL QR Code dengan data bank Anda)
                    </p>
                </div>
            <?php endif; ?>

        </div>
    </main>
</body>
</html>