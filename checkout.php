<?php
session_start();

// Koneksi database
$conn = new mysqli("localhost", "root", "", "toko_online");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data pelanggan dari form
$nama    = $_POST['nama'];
$email   = $_POST['email'];
$telepon = $_POST['telepon'];
$alamat  = $_POST['alamat'];

// Simpan ke tabel pelanggan
$sql_pelanggan = "INSERT INTO pelanggan (nama, email, telepon, alamat) 
                  VALUES ('$nama', '$email', '$telepon', '$alamat')";
$conn->query($sql_pelanggan);

// Ambil id pelanggan baru
$id_pelanggan = $conn->insert_id;

// Simpan pesanan ke tabel pesanan
if (!empty($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $id_produk => $item) {
        $jumlah = $item['qty'];
        $harga  = $item['harga'];
        $total  = $jumlah * $harga;

        $sql_pesanan = "INSERT INTO pesanan (id_pelanggan, id_produk, jumlah, harga, total, tanggal) 
                        VALUES ('$id_pelanggan', '$id_produk', '$jumlah', '$harga', '$total', NOW())";
        $conn->query($sql_pesanan);
    }
}

// Kosongkan keranjang
unset($_SESSION['keranjang']);

// Redirect atau tampilkan pesan
echo "<script>alert('Pesanan berhasil diproses!'); window.location='index.php';</script>";

$conn->close();
?>