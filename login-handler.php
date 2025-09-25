<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Perbarui query untuk mengambil nama dan email
    $sql = "SELECT id_pelanggan, username, password, nama, email, is_new_member FROM pelanggan WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $member = $result->fetch_assoc();
        
        // PERBAIKAN: Gunakan password_verify() untuk memeriksa password yang di-hash
        if (password_verify($password, $member['password'])) {
            $_SESSION['is_member'] = true;
            $_SESSION['username'] = $member['username'];
            $_SESSION['id_pelanggan'] = $member['id_pelanggan'];
            
            // PERBAIKAN: Konversi nilai database ke boolean yang konsisten
            $_SESSION['is_new_member'] = ($member['is_new_member'] == 1) ? true : false;
            
            // Simpan nama dan email ke session
            $_SESSION['nama'] = $member['nama'];
            $_SESSION['email'] = $member['email'];

            header("Location: index.php");
            exit;
        } else {
            $_SESSION['login_error'] = "Username atau password salah.";
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['login_error'] = "Username atau password salah.";
        header("Location: login.php");
        exit;
    }
}
?>