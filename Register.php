<?php
include 'include.php';
session_start();

if (isset($_SESSION['error'])) {
    $error_message = $_SESSION['error'];
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    $success_message = $_SESSION['success'];
    unset($_SESSION['success']);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            session_regenerate_id();
            $_SESSION['user_id'] = $user['id'];
            header('Location: index.php'); 
            exit();
        } else {
            $_SESSION['error'] = "Username atau password salah.";
            header('Location: login.php'); 
            exit();
        }
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
            header('Location: login.php'); 
            exit();
        } else {
            $_SESSION['error'] = "Terjadi kesalahan: " . $stmt->error;
            header('Location: register.php'); 
            exit();
        }
        
    } elseif (isset($_POST['register'])) {
    
        $username = $_POST['reg_username'];
        $gmail = $_POST['reg_email'];
        $password = password_hash($_POST['reg_password'], PASSWORD_DEFAULT); 

    
        $sql = "INSERT INTO users (username, gmail, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $gmail, $password);

        
        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link
      rel="shortcut icon"
      href="./assets/images/logo/favicon.jpeg"
      type="image/x-icon"
    />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="assets/css/register.css" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <title>UniCart - login Website</title>
</head>
<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
 
                <form action="" method="POST" class="sign-in-form">
                    <h2 class="title">Sign In</h2>
                    <?php if (isset($error_message)): ?>
    <p class="error-message"><?= $error_message ?></p>
<?php endif; ?>

<?php if (isset($success_message)): ?>
    <p class="success-message"><?= $success_message ?></p>
<?php endif; ?>

                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" placeholder="Username" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required />
                    </div>
                    <input type="submit" name="login" value="Login" class="btn solid" />
                    <p class="social-text">Or Sign in with social platforms</p>
                    <div class="social-media">
                        <a href="facebook.com" class="social-icon">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="twitter.com" class="social-icon">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="google.com" class="social-icon">
                            <i class="fab fa-google"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </form>


                <form action="" method="POST" class="sign-up-form">
                    <h2 class="title">Sign Up</h2>
    <?php if (isset($error_message)): ?>
        <p class="error-message"><?= $error_message ?></p>
    <?php endif; ?>
    
    <?php if (isset($success_message)): ?>
        <p class="success-message"><?= $success_message ?></p>
    <?php endif; ?>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="reg_username" placeholder="Username" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="reg_email" placeholder="Email" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="reg_password" placeholder="Password" required />
                    </div>
                    <input type="submit" name="register" value="Sign Up" class="btn solid" />
                    <p class="social-text">Or Sign up with social platforms</p>
                    <div class="social-media">
                        <a href="#" class="social-icon">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-google"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>


        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>New here?</h3>
                    <p>Daftar Sekarang nikmati Gratis ongkir dan Cashback Untuk pengguna baru!</p>
                    <button class="btn transparent" id="sign-up-btn">Daftar</button>
                </div>
                <img src="./img/log.png" class="image" alt="">
            </div>
            <div class="panel right-panel">
                <div class="content">
                    <h3>One of us?</h3>
                    <p>Promo 10.10 Mega sale oktober ceria. Diskon dan Cashback hingga 1jt!</p>
                    <button class="btn transparent" id="sign-in-btn">Login</button>
                </div>
                <img src="./img/register.png" class="image" alt="">
            </div>
        </div>
    </div>

    <script src="./app.js"></script>
</body>
</html>
