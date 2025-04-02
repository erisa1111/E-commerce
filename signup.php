<?php
// Database connection and form processing at the top
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database configuration
    $host = 'localhost';
    $dbname = 'your_database';
    $username = 'your_username';
    $password = 'your_password';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Get form data
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $phone = $_POST['phone'];
        $country = $_POST['country'];
        $city = $_POST['city'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO users (name, surname, phone, country, city, address, email, password) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $surname, $phone, $country, $city, $address, $email, $password]);
        
        // Redirect on success
        header("Location: home.html");
        exit();
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/sign_up.css">
</head>
<body>
    <div class="container">
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form action="" method="POST" class="scroll-form">
            <div class="form-section" id="personal-details">
                <h1>Sign Up</h1>
                <h3>Personal details</h3>
                <input type="text" name="name" placeholder="Name" required />
                <input type="text" name="surname" placeholder="Surname" required />
                <input type="text" name="phone" placeholder="Phone Number" required />
                <input type="text" name="country" placeholder="Country" required />
                <input type="text" name="city" placeholder="City" required />
                <input type="text" name="address" placeholder="Address" required />
            </div>
         
            <div class="form-section" id="account-details">
                <h3>Account details</h3>
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit">Sign Up</button>
            </div>
        </form>
    </div>
</body>
</html>