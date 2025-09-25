<?php
session_start();

// Koneksi database
$conn = new mysqli("localhost", "root", "", "toko_online");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek jika keranjang kosong
if (empty($_SESSION['keranjang'])) {
    echo "<script>alert('Keranjang belanja kosong!'); window.location='index.php';</script>";
    exit;
}

// Ambil data pelanggan dari form
$nama = $_POST['nama'] ?? '';
$email = $_POST['email'] ?? '';
$telepon = $_POST['telepon'] ?? '';
$alamat = $_POST['alamat'] ?? '';
$metode_pembayaran = $_POST['metode_pembayaran'] ?? '';
$id_pelanggan = null;

// Tambahkan validasi dasar
if (empty($nama) || empty($email) || empty($telepon) || empty($alamat) || empty($metode_pembayaran)) {
    echo "<script>alert('Harap lengkapi semua data pelanggan dan pilih metode pembayaran!'); window.location='index.php';</script>";
    exit;
}

$diskon = 0;

if (isset($_SESSION['is_member']) && $_SESSION['is_member'] === true) {
    // Jika user adalah member yang sudah login, ambil id dari session
    $id_pelanggan = $_SESSION['id_pelanggan'];
    
    // Update data telepon dan alamat member di tabel pelanggan
    $sql_update_member_info = "UPDATE pelanggan SET telepon = ?, alamat = ? WHERE id_pelanggan = ?";
    $stmt_update_info = $conn->prepare($sql_update_member_info);
    $stmt_update_info->bind_param("ssi", $telepon, $alamat, $id_pelanggan);
    $stmt_update_info->execute();
    $stmt_update_info->close();
    
    // Member mendapatkan diskon 10% untuk setiap transaksi
    $grandTotal = 0;
    foreach ($_SESSION['keranjang'] as $item) {
        $grandTotal += $item['harga'] * $item['qty'];
    }
    $diskon = $grandTotal * 0.10;

} else {
    // Jika user adalah GUEST (tamu)
    // Cek apakah email sudah ada di database pelanggan
    $sql_check_guest = "SELECT id_pelanggan FROM pelanggan WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check_guest);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows > 0) {
        // Jika email sudah ada, ambil ID-nya dan update info tamu
        $pelanggan_data = $result_check->fetch_assoc();
        $id_pelanggan = $pelanggan_data['id_pelanggan'];
    } else {
        // Jika email belum ada, tambahkan sebagai pelanggan baru (tamu)
        // Tambahkan nilai string kosong untuk kolom username dan password
        $sql_insert_guest = "INSERT INTO pelanggan (nama, email, telepon, alamat, username, password, is_new_member) VALUES (?, ?, ?, ?, '', '', 0)";
        $stmt_insert = $conn->prepare($sql_insert_guest);
        $stmt_insert->bind_param("ssss", $nama, $email, $telepon, $alamat);
        $stmt_insert->execute();
        $id_pelanggan = $conn->insert_id;
        $stmt_insert->close();
    }
    $stmt_check->close();
}

// Hitung total harga keseluruhan pesanan
$grandTotal = 0;
foreach ($_SESSION['keranjang'] as $item) {
    $grandTotal += $item['harga'] * $item['qty'];
}
$grandTotalFinal = $grandTotal - $diskon;

// Masukkan data ke tabel 'pesanan'
$sql_pesanan = "INSERT INTO pesanan (id_pelanggan, tanggal, total, diskon) 
                VALUES (?, NOW(), ?, ?)";
$stmt = $conn->prepare($sql_pesanan);
$stmt->bind_param("idd", $id_pelanggan, $grandTotalFinal, $diskon);
$stmt->execute();
$id_pesanan = $conn->insert_id;
$stmt->close();

// PERBAIKAN: Masukkan data pembayaran ke tabel 'pembayaran'
$sql_pembayaran = "INSERT INTO pembayaran (id_pesanan, tanggal_bayar, metode) VALUES (?, NOW(), ?)";
$stmt_pembayaran = $conn->prepare($sql_pembayaran);
$stmt_pembayaran->bind_param("is", $id_pesanan, $metode_pembayaran);
$stmt_pembayaran->execute();
$stmt_pembayaran->close();

// Siapkan data untuk struk
$receipt_items = [];

// Pastikan proses penyimpanan detail pesanan hanya berjalan jika pesanan utama berhasil
if ($id_pesanan) {
    // Siapkan query untuk tabel 'detail_pesanan'
    $sql_detail = "INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah, harga) 
                   VALUES (?, ?, ?, ?)";
    $stmt_detail = $conn->prepare($sql_detail);
    
    foreach ($_SESSION['keranjang'] as $id_produk => $item) {
        $subtotal = $item['harga'] * $item['qty'];
        
        $stmt_detail->bind_param("iisi", $id_pesanan, $id_produk, $item['qty'], $item['harga']);
        $stmt_detail->execute();

        $receipt_items[] = [
            'nama' => $item['nama'],
            'jumlah' => $item['qty'],
            'harga_satuan' => $item['harga'],
            'subtotal' => $subtotal
        ];
    }
    $stmt_detail->close();
}

$receipt_data = [
    'id_pesanan' => $id_pesanan,
    'nama' => $nama,
    'email' => $email,
    'telepon' => $telepon,
    'alamat' => $alamat,
    'items' => $receipt_items,
    'subtotal_produk' => $grandTotal,
    'diskon' => $diskon,
    'grand_total' => $grandTotalFinal,
    'metode_pembayaran' => $metode_pembayaran // <-- Tambahkan metode pembayaran ke data struk
];
$_SESSION['receipt_data'] = $receipt_data;

unset($_SESSION['keranjang']);
$conn->close();

header("Location: struk.php");
exit;
?>