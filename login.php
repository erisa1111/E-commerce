<?php
// Start session at the very beginning
session_start();

// Database connection and form processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database configuration
    $host = 'localhost';
    $dbname = 'your_database';
    $username = 'your_username';
    $dbpassword = 'your_password'; // Renamed to avoid conflict with form password

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Get form data
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        // Check if user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            
            // Redirect to home page
            header("Location: home.php");
            exit();
        } else {
            $error = "Invalid username or password";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
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
                <input type="text" name="username" placeholder="Username or Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit">Log in</button>
            </form>
        </div>
        
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1 style="color: #9e2548;">Welcome Back!</h1>
                    <p style="color: #9e2548">Continue your free experience with Dado!</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>