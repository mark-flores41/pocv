<?php
session_start();
include('db.php');

// Check if the user is logged in
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : null;

if (!$user_email) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch current user details
$sql = "SELECT `id`, `email`, `municipality`, `barangay`, `sitioorzone`, `contact`, `role`, `reset_token` FROM `user` WHERE `email` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "User not found.";
    exit();
}

// Handle form submission
$updated = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_email = $_POST['email'];
    $municipality = $_POST['municipality'];
    $barangay = $_POST['barangay'];
    $sitioorzone = $_POST['sitioorzone'];
    $contact = $_POST['contact'];

    // Update user details excluding the reset_token
    $update_sql = "UPDATE `user` 
                   SET `email` = ?, `municipality` = ?, `barangay` = ?, `sitioorzone` = ?, `contact` = ? 
                   WHERE `id` = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssssi", $new_email, $municipality, $barangay, $sitioorzone, $contact, $user['id']);

    if ($stmt->execute()) {
        $_SESSION['user_email'] = $new_email; // Update session email
        $updated = true; // Set flag to true after update
        header("Refresh:2; url=index.php"); // Refresh the page
    } else {
        echo "<p>Error updating information. Please try again.</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v<?php echo time(); ?>">
    <title>Update User Information</title>
    <style>
header {
    position: fixed;
    top: 0;
    background: linear-gradient(to right, #11998e, #38ef7d);
    color: #ffffff;
    padding: 15px 0;
    text-align: center;
    margin-bottom: 20px;
    width: 100%;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    z-index: 10;
}

header h1 {
    font-size: 2rem;
    margin: 0;
}

header nav ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
    display: flex;
    justify-content: center;
    gap: 20px;
}

header nav ul li {
    display: inline;
}

header nav ul li a {
    color: #ffffff;
    text-decoration: none;
    font-size: 1rem;
    transition: color 0.3s ease;
}

header nav ul li a:hover {
    color: #f3f4f6;
}
.containers{
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}
    </style>
</head>
<body>

<!-- Header with Navigation -->
<header>
    <div class="containers">
        <h1>IRUMA HARDWARE SHOP</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php">Products</a></li>
                <?php if (isset($_SESSION['user_email'])) { ?>
                    <li><a href="logout.php" class="logout-btn">Logout</a></li>
                <?php } else { ?>
                    <li><a href="login.php" class="login-btn">Login</a></li>
                <?php } ?>
            </ul>
        </nav>
    </div>
</header>

<!-- Main content of the page -->
<div class="blur-bg-overlay"></div>
<div class="form-popups">
    <!-- Login Form -->
    <div class="form-box login" id="login-form">
        <div class="form-details">
            <h2>Update Your Information</h2>
        </div>

        <?php if ($updated): ?>
            <div class="form-content">
                <p>Your information has been updated successfully!</p>
            </div>
        <?php else: ?>
            <div class="form-content">
                <form method="POST">
                    <div class="input-field">  
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        <label>Email</label>
                    </div>
                    <div class="input-field">               
                        <input type="text" name="municipality" value="<?php echo htmlspecialchars($user['municipality']); ?>" required>
                        <label>Municipality</label>
                    </div>
                    <div class="input-field">             
                        <input type="text" name="barangay" value="<?php echo htmlspecialchars($user['barangay']); ?>" required>
                        <label>Barangay</label>
                    </div>
                    <div class="input-field">            
                        <input type="text" name="sitioorzone" value="<?php echo htmlspecialchars($user['sitioorzone']); ?>" required>
                        <label>Sitio/Zone</label>
                    </div>
                    <div class="input-field">             
                        <input type="text" name="contact" value="<?php echo htmlspecialchars($user['contact']); ?>" required>
                        <label>Contact</label>
                    </div>
                    <div class="input-field">    
                        <input type="text" value="<?php echo htmlspecialchars($user['role']); ?>" disabled>
                        <label>Role</label>
                    </div>
                    <!-- Reset token, displayed but not updatable -->
                    <div class="input-field">    
                        <input type="text" value="<?php echo htmlspecialchars($user['reset_token']); ?>" disabled>
                        <label>Reset Token</label>
                    </div>
                    <button type="submit">Update Information</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
