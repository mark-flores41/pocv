<?php
session_start();
include('db.php'); // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $reset_token = trim($_POST['reset_token']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid email format.");</script>';
        exit;
    }

    // Validate password strength
    $passwordPattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    if (!preg_match($passwordPattern, $new_password)) {
        echo '<script>alert("Password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a number, and a special character.");</script>';
        exit;
    }

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        echo '<script>alert("Passwords do not match.");</script>';
        exit;
    }

    // Query the database to check if the email and reset token are valid
    $sql = "SELECT * FROM user WHERE email = ? AND reset_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $reset_token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the user's password and clear the reset token
        $update_sql = "UPDATE user SET password = ?, reset_token = NULL WHERE email = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $hashed_password, $email);

        if ($update_stmt->execute()) {
            echo '<script>
                alert("Password reset successful. You can now log in with your new password.");
                window.location.href = "login.php";
            </script>';
        } else {
            echo '<script>alert("Error updating password. Please try again later.");</script>';
        }

        $update_stmt->close();
    } else {
        echo '<script>alert("Invalid email or reset token.");</script>';
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="blur-bg-overlay"></div>
    <div class="form-popup">
        <div class="form-box reset" id="reset-form">
            <div class="form-details">
                <h2>Reset Password</h2>
                <p>Enter your email, reset token, and new password to reset your account password.</p>
            </div>
            <div class="form-content">
                <h2>RESET PASSWORD</h2>
                <form action="forgotpasswordreset.php" method="POST">
                    <div class="input-field">
                        <input type="email" name="email" required>
                        <label>Email</label>
                    </div>
                    <div class="input-field">
                        <input type="text" name="reset_token" required>
                        <label>Reset Token</label>
                    </div>
                    <div class="input-field">
                        <input type="password" name="new_password" required>
                        <label>New Password</label>
                    </div>
                    <div class="input-field">
                        <input type="password" name="confirm_password" required>
                        <label>Confirm New Password</label>
                    </div>
                    <button type="submit">Reset Password</button>
                </form>
                <div class="bottom-link">
                    Remember your password?
                    <a href="login.php">Login</a>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
