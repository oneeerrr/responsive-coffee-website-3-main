<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Member</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
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
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2>Login Member</h2>
            <?php
            session_start();
            if (isset($_SESSION['login_error'])) {
                echo "<p style='color:red;'>" . $_SESSION['login_error'] . "</p>";
                unset($_SESSION['login_error']);
            }
            ?>
            <form action="login-handler.php" method="post">
                <input type="text" name="username" placeholder="Username" class="login-input" required>
                <input type="password" name="password" placeholder="Password" class="login-input" required>
                <button type="submit" class="login-button">Login</button>
            </form>
            <a href="register.php" class="login-link">Belum punya akun? Daftar di sini.</a>
    </div>
</body>
</html>