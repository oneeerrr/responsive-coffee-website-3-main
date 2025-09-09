<?php
// Enable error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Set header JSON
header('Content-Type: application/json');

// Debug: Log semua data yang diterima
error_log('=== KERANJANG HANDLER DEBUG ===');
error_log('POST data: ' . print_r($_POST, true));
error_log('SESSION data: ' . print_r($_SESSION, true));

try {
    // Cek apakah request method adalah POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method tidak diizinkan. Gunakan POST.');
    }

    // Cek apakah action ada
    if (!isset($_POST['action'])) {
        throw new Exception('Parameter action tidak ditemukan.');
    }

    $action = $_POST['action'];
    error_log('Action: ' . $action);

    // Fungsi untuk menambah produk ke keranjang
    if ($action == 'add_to_cart') {
        // Validasi parameter
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            throw new Exception('Parameter ID produk tidak ditemukan atau kosong.');
        }
        
        if (!isset($_POST['nama']) || empty($_POST['nama'])) {
            throw new Exception('Parameter nama produk tidak ditemukan atau kosong.');
        }
        
        if (!isset($_POST['harga']) || !is_numeric($_POST['harga'])) {
            throw new Exception('Parameter harga produk tidak valid.');
        }

        $id = trim($_POST['id']);
        $nama = trim($_POST['nama']);
        $harga = floatval($_POST['harga']);
        $qty = isset($_POST['qty']) && is_numeric($_POST['qty']) ? intval($_POST['qty']) : 1;
        
        // Validasi nilai
        if ($harga <= 0) {
            throw new Exception('Harga produk tidak valid (harus > 0).');
        }
        
        if ($qty <= 0) {
            throw new Exception('Jumlah produk tidak valid (harus > 0).');
        }

        error_log("Adding to cart: ID=$id, Name=$nama, Price=$harga, Qty=$qty");
        
        // Inisialisasi keranjang jika belum ada
        if (!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = array();
            error_log('Keranjang initialized');
        }
        
        // Jika produk sudah ada di keranjang, tambah quantity
        if (isset($_SESSION['keranjang'][$id])) {
            $_SESSION['keranjang'][$id]['qty'] += $qty;
            error_log("Product exists, updated qty to: " . $_SESSION['keranjang'][$id]['qty']);
        } else {
            // Jika produk belum ada, tambah produk baru
            $_SESSION['keranjang'][$id] = array(
                'nama' => $nama,
                'harga' => $harga,
                'qty' => $qty
            );
            error_log("New product added to cart");
        }
        
        // Hitung total item di keranjang
        $total_items = 0;
        foreach ($_SESSION['keranjang'] as $item) {
            $total_items += $item['qty'];
        }
        
        error_log("Total items in cart: $total_items");
        
        echo json_encode(array(
            'status' => 'success',
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'total_items' => $total_items,
            'debug' => array(
                'id' => $id,
                'nama' => $nama,
                'harga' => $harga,
                'qty' => $qty
            )
        ));
        exit;
    }

    // Fungsi untuk menghapus produk dari keranjang
    if ($action == 'remove_from_cart') {
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            throw new Exception('Parameter ID produk tidak ditemukan.');
        }

        $id = trim($_POST['id']);
        
        if (isset($_SESSION['keranjang'][$id])) {
            unset($_SESSION['keranjang'][$id]);
            
            $total_items = !empty($_SESSION['keranjang']) ? 
                          array_sum(array_column($_SESSION['keranjang'], 'qty')) : 0;
            
            echo json_encode(array(
                'status' => 'success',
                'message' => 'Produk berhasil dihapus dari keranjang',
                'total_items' => $total_items
            ));
        } else {
            throw new Exception('Produk tidak ditemukan dalam keranjang.');
        }
        exit;
    }

    // Fungsi untuk mengosongkan keranjang
    if ($action == 'clear_cart') {
        $_SESSION['keranjang'] = array();
        
        echo json_encode(array(
            'status' => 'success',
            'message' => 'Keranjang berhasil dikosongkan',
            'total_items' => 0
        ));
        exit;
    }

    // Jika action tidak dikenali
    throw new Exception('Action tidak dikenali: ' . $action);

} catch (Exception $e) {
    error_log('Error in keranjang-handler: ' . $e->getMessage());
    
    echo json_encode(array(
        'status' => 'error',
        'message' => $e->getMessage(),
        'debug' => array(
            'post_data' => $_POST,
            'session_data' => isset($_SESSION['keranjang']) ? $_SESSION['keranjang'] : 'not set'
        )
    ));
    exit;
} catch (Error $e) {
    error_log('Fatal error in keranjang-handler: ' . $e->getMessage());
    
    echo json_encode(array(
        'status' => 'error',
        'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
    ));
    exit;
}
?>