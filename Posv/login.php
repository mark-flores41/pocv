<?php
session_start();  // Start the session at the top of the page
include('db.php');  // Include the database connection

// Check if the user is already logged in and redirect them if they are
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query the database to check if the user exists
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables for the logged-in user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];  // Store the email too if needed

            // Redirect to the homepage or dashboard
            header('Location: index.php');
            exit;
        } else {
            // Invalid password
            echo '<script>alert("Invalid password. Please try again.");</script>';
        }
    } else {
        // No user found
        echo '<script>alert("No user found with that email address.");</script>';
    }

    $stmt->close();
}

$conn->close();  // Close the database connection at the end of the script
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css?v<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <div class="blur-bg-overlay"></div>
    <div class="form-popup">
        <!-- Login Form -->
        <div class="form-box login" id="login-form">
            <div class="form-details">
                <h2>Welcome Back</h2>
                <p>Please log in using your personal information to stay connected with us.</p>
            </div>
            <div class="form-content">
                <h2>LOGIN</h2>
                <form action="login.php" method="POST">
                    <div class="input-field">
                        <input type="text" name="email" required>
                        <label>Email</label>
                    </div>
                    <div class="input-field">
                        <input type="password" name="password" id="passwordInput" required>
                        <label>Password</label>
                        <i class="fa-solid fa-eye" id="togglePassword"></i>
                    </div>
                    <a href="forgotpasswordreset.php" class="forgot-pass-link">Forgot password?</a>
                    <button type="submit">Log In</button>
                </form>
                <div class="bottom-link">
                    Don't have an account?
                    <a href="signup.php" >Signup</a>
                </div>
            </div>
        </div>
    </div>

    <script>
const passwordInput = document.getElementById('passwordInput');
const togglePassword = document.getElementById('togglePassword');

togglePassword.addEventListener('click', () => {
    // Toggle the type attribute of the input field
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);

    // Toggle the eye icon
    togglePassword.classList.toggle('fa-eye-slash');
});</script>
</body>
</html>
