<?php
session_start();
require 'db_connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        // Fetch user data including role
        $stmt = $pdo->prepare("SELECT id, email, password, role FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Store user details in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role']; // Store the role

            if (isset($_COOKIE['persistent_cart'])) {
                $cartData = json_decode($_COOKIE['persistent_cart'], true);
                if ($cartData !== null) { // Valid JSON
                    $_SESSION['cart'] = $cartData;
                    // Clear the cookie after loading
                    setcookie('persistent_cart', '', time() - 3600, '/');
                }
            }

            // Redirect to home page
            header("Location: home.php");
            
            
            exit();
        } else {
            $error = "Invalid email or password";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . htmlspecialchars($e->getMessage());
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/log_in.css">
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-in-container">
            <?php if (isset($error)): ?>
                <div class="error-message" style="color: red; margin-bottom: 15px;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form action="" method="POST">
                <h1>Log in</h1>
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit">Log in</button>
                    <!-- Link to the signup page if the user doesn't have an account -->
                    <p style="text-align: center; margin-top: 10px;">
    Don't have an account? <a href="signup.php" style="color: #9e2548; text-decoration: none;">Sign up</a>
</p>

            </form>

         
        </div>
        
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1 style="color: #9e2548;">Welcome Back!</h1>
                    <p style="color: #9e2548">Continue your experience with GlowHeaven!</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>