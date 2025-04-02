<?php
session_start();

// Include database connection
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        // Check if user exists by email
        $stmt = $pdo->prepare("SELECT id, email, password FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];

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