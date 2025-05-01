<?php
require 'db_connect.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate input
        if (!isset($_POST['name'], $_POST['surname'], $_POST['phone_number'], $_POST['country'], 
                  $_POST['city'], $_POST['address'], $_POST['email'], $_POST['password'])) {
            die("Error: Missing form data.");
        }

        // Get form data and sanitize
        $name = trim($_POST['name']);
        $surname = trim($_POST['surname']);
        $phone = trim($_POST['phone_number']);
        $country = trim($_POST['country']);
        $city = trim($_POST['city']);
        $address = trim($_POST['address']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Assign role based on email
        $role = ($email === 'admin@gmail.com') ? 'admin' : 'user';

        // Prepare SQL query (added role column)
        $stmt = $pdo->prepare("INSERT INTO users (name, surname, phone_number, country, city, address, email, password, role) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $surname, $phone, $country, $city, $address, $email, $password, $role]);

        // Debugging: Check if insertion was successful
        if ($stmt->rowCount() > 0) {
            echo "User registered successfully.\n";
            // per me na funksionu mire test_signup.php duhet me komentu header
            header("Location: home.php"); // Redirect on success
            exit();// nese deshton
        } else {
            die("Error: Data was not inserted.");
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage()); // Display database errors
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
                <input type="text" name="phone_number" placeholder="Phone Number" required />
                <input type="text" name="country" placeholder="Country" required />
                <input type="text" name="city" placeholder="City" required />
                <input type="text" name="address" placeholder="Address" required />
            </div>
         
            <div class="form-section" id="account-details">
                <h3>Account details</h3>
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit">Sign Up</button>
                <p style="text-align: center; margin-top: 10px; font-size: 13px; font-weight:100 ;">
    Already have an account? <a href="login.php" style="color: #9e2548; text-decoration: none;">Log in</a>
</p>
            </div>
        </form>
    </div>
</body>
</html>