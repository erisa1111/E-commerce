<?php
session_start();
require_once 'db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $pdo->prepare("SELECT name, surname, phone_number, email, country, city FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $country = $_POST['country'];
    $city = $_POST['city'];

    $updateStmt = $pdo->prepare("UPDATE users SET name = ?, surname = ?, phone_number = ?, email = ?, country = ?, city = ? WHERE id = ?");
    if ($updateStmt->execute([$name, $surname, $phone_number, $email, $country, $city, $user_id])) {
        $success = "Profile updated successfully!";
        // Refresh data after update
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $error = "Error updating profile. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - GlowHeaven</title>
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="navbar/navbar.css">
    <link rel="stylesheet" href="footer/footer.css">
    <script>
        function enableEdit() {
            document.querySelectorAll(".profile-input").forEach(input => input.removeAttribute("readonly"));
            document.getElementById("save-btn").style.display = "block";
            document.getElementById("edit-btn").style.display = "none";
        }
    </script>
</head>
<body>
    
<div id="navbar-container"></div>

<div class="profile-card">
  <a href="home.php"><i class="fa-solid fa-arrow-left"></i></a>
  <div class="info">
  <img src="/img/profile_picture.png" alt="">

<h2><?php echo htmlspecialchars($user['name']) . ' ' . htmlspecialchars($user['surname']); ?></h2>
  </div>



        <?php if (isset($success))
            echo "<p style='color: green; text-align:center;'>$success</p>"; ?>
        <?php if (isset($error))
            echo "<p style='color: red; text-align:center;'>$error</p>"; ?>

        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="profile-input"
                readonly required>

            <label>Surname:</label>
            <input type="text" name="surname" value="<?php echo htmlspecialchars($user['surname']); ?>"
                class="profile-input" readonly required>

            <label>Phone Number:</label>
            <input type="text" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>"
                class="profile-input" readonly>
                <label>Email:</label>
          <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="profile-input" readonly required>

            <label>Country:</label>
            <input type="text" name="country" value="<?php echo htmlspecialchars($user['country']); ?>"
                class="profile-input" readonly>

            <label>City:</label>
            <input type="text" name="city" value="<?php echo htmlspecialchars($user['city']); ?>" class="profile-input"
                readonly>

            <button type="button" id="edit-btn" onclick="enableEdit()">Edit</button>
            <button type="submit" name="update_profile" id="save-btn" style="display: none;">Save</button>
            <div class="bottom-space"></div>
        </form>

    </div>
   
    <div id="footer-container">
        <footer>
            <p>&copy; <?php echo date('Y'); ?> GlowHeaven. All rights reserved.</p>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch("navbar/navbar.php")
                .then(response => response.text())
                .then(data => {
                    document.getElementById('navbar-container').innerHTML = data;
                });

            fetch("footer/footer.html")
                .then(response => response.text())
                .then(data => {
                    document.getElementById('footer-container').innerHTML = data;
                });
        });
    </script>
</body>
</html>

