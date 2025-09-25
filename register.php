<?php
session_start();
include 'koneksi.php';

$message = '';
$is_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; 
    $nama     = $_POST['nama'];
    $email    = $_POST['email'];

    // Cek apakah username sudah ada
    $sql_check = "SELECT * FROM pelanggan WHERE username = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $message = "Username sudah ada. Silakan pilih yang lain.";
    } else {
        // PERBAIKAN: Enkripsi password menggunakan password_hash()
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Perbarui query INSERT untuk menyertakan kolom nama dan email
        $sql_insert = "INSERT INTO pelanggan (username, password, nama, email, is_new_member) VALUES (?, ?, ?, ?, TRUE)";
        $stmt_insert = $conn->prepare($sql_insert);
        
        // Perbarui bind_param untuk menyertakan nama dan email (ssss = string, string, string, string)
        $stmt_insert->bind_param("ssss", $username, $hashed_password, $nama, $email);
        
        if ($stmt_insert->execute()) {
            $message = "Registrasi berhasil! Anda mendapatkan diskon 10% untuk transaksi pertama.";
            $is_success = true;
            header("refresh:3;url=login.php");
        } else {
            $message = "Terjadi kesalahan saat registrasi. Silakan coba lagi.";
        }
    }
    $stmt_check->close();
    if (isset($stmt_insert)) $stmt_insert->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Member</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        /* Gaya CSS lainnya tetap sama... */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: var(--body-color);
        }
        .login-form {
            background-color: var(--white-color);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 16px hsla(166, 85%, 8%, .2);
            width: 350px;
            text-align: center;
        }
        .login-form h2 {
            margin-bottom: 1.5rem;
            color: var(--title-color);
        }
        .login-input {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .login-button {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--first-color);
            color: var(--white-color);
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: var(--font-semi-bold);
        }
        .login-button:hover {
            background-color: var(--first-color-alt);
        }
        .login-link {
            display: block;
            margin-top: 1rem;
            color: var(--text-color);
        }
        .message-box {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.5rem;
            font-weight: var(--font-semi-bold);
        }
        .message-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2>Daftar Akun Baru</h2>
            <?php if (!empty($message)): ?>
                <div class="message-box <?php echo $is_success ? 'message-success' : 'message-error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            <form action="register.php" method="post">
                <input type="text" name="nama" placeholder="Nama Lengkap" class="login-input" required>
                <input type="email" name="email" placeholder="Email" class="login-input" required>
                <input type="text" name="username" placeholder="Username" class="login-input" required>
                <input type="password" name="password" placeholder="Password" class="login-input" required>
                <button type="submit" class="login-button">Daftar</button>
            </form>
            <a href="login.php" class="login-link">Sudah punya akun? Login di sini.</a>
        </div>
    </div>
</body>
</html>