<?php
session_start();
include('db.php'); // Include the database connection file

// Function to generate a random alphanumeric token
function generateRandomToken($length = 16) {
    return bin2hex(random_bytes($length / 2)); // Generates a random string of specified length
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $municipality = trim($_POST['municipality']);
    $barangay = trim($_POST['barangay']);
    $sitioorzone = trim($_POST['sitioorzone']);
    $contact = trim($_POST['Contact']);
    $role = $_POST['role']; // Get the role (Admin, Guest, or Delivery Rider)
    $policy = isset($_POST['policy']) ? true : false;

    // Validate contact number
    if (!preg_match('/^[0-9]{10,15}$/', $contact)) {
        echo '<script>alert("Invalid contact number format. Please enter a valid number.");</script>';
        echo '<script>window.location.href = "signup.php";</script>';
        exit;
    }

    // Validate password strength
    $passwordPattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    if (!preg_match($passwordPattern, $password)) {
        echo '<script>alert("Password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a number, and a special character.");</script>';
        echo '<script>window.location.href = "signup.php";</script>';
        exit;
    }

    // Check if email already exists in the database
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<script>alert("The email you entered is already in use. Please use a different email.");</script>';
        echo '<script>window.location.href = "signup.php";</script>';
        exit;
    } else {
        if ($policy) {
            if (!empty($email) && !empty($password) && !empty($municipality) && !empty($barangay) && !empty($sitioorzone) && !empty($contact) && !empty($role)) {
                // Hash the password for security
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Generate a random token for resetting password
                $reset_token = generateRandomToken();

                // Insert user data along with the reset token
                $sql = "INSERT INTO user (email, password, municipality, barangay, sitioorzone, contact, role, reset_token) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssss", $email, $hashed_password, $municipality, $barangay, $sitioorzone, $contact, $role, $reset_token);

                if ($stmt->execute()) {
                    // Display success message with reset token
                    echo '<script>
                        alert("Registration successful!\\n\\nWelcome to Our Platform!\\n\\nThank you for signing up! Your account has been created successfully.\\n\\nIf you ever forget your password, you can use the following code to reset it:\\n\\nReset Token: ' . $reset_token . '\\n\\nKeep this token safe.");
                        window.location.href = "login.php";
                    </script>';
                } else {
                    echo '<script>alert("Error: ' . $stmt->error . '");</script>';
                }

                $stmt->close();
            } else {
                echo '<script>alert("All fields are required. Please fill in all fields.");</script>';
            }
        } else {
            echo '<script>alert("You must agree to the terms and conditions.");</script>';
        }
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
    <title>Signup</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body>
    <div class="blur-bg-overlay"></div>
    <div class="form-popup">
        <div class="form-box signup" id="signup-form">
            <div class="form-details">
                <h2>Create Account</h2>
                <p>To become a part of our community, please sign up using your personal information.</p>
            </div>
            <div class="form-content">
                <h2>SIGNUP</h2>
                <form action="signup.php" method="POST">
                    <div class="input-field">
                        <input type="email" name="email" required>
                        <label>ENTER EMAIL</label>
                    </div>
                    <div class="input-field">
                        <input type="password" name="password" id="passwordInput" required>
                        <label>CREATE PASSWORD</label>
                        <i class="fa-solid fa-eye" id="togglePassword"></i>
                    </div>
                    <div class="input-field">
                        <input type="text" name="municipality" required>
                        <label>ENTER MUNICIPALITY</label>
                    </div>
                    <div class="input-field">
                        <input type="text" name="barangay" required>
                        <label>ENTER BARANGAY</label>
                    </div>
                    <div class="input-field">
                        <input type="text" name="sitioorzone" required>
                        <label>ENTER SITIO/ZONE</label>
                    </div>
                    <div class="input-field">
                        <input type="text" name="Contact" required>
                        <label>ENTER CONTACT NUMBER</label>
                    </div>
                    <div class="input-field">
                        <label for="role">Choose Role:</label>
                        <select name="role" required>
                            <option value="Guest">Guest</option>
                            <option value="Admin">Admin</option>
                            <option value="Delivery Rider">Delivery Rider</option>
                        </select>
                    </div>  
                    <div class="policy-text">
                        <input type="checkbox" id="policy" name="policy" required>
                        <label for="policy">
                            I agree to the
                            <a href="#" class="option">Terms & Conditions</a>
                        </label>
                    </div>
                    <button type="submit">Sign Up</button>
                </form>
                <div class="bottom-link">
                    Already have an account?
                    <a href="login.php" id="login-link">Login</a>
                </div>
            </div>
        </div>
    </div>
    <script>
    // JavaScript for password visibility toggle
    const passwordInput = document.getElementById('passwordInput');
    const togglePassword = document.getElementById('togglePassword');

    togglePassword.addEventListener('click', () => {
        // Toggle the type attribute of the input field
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle the eye icon
        togglePassword.classList.toggle('fa-eye-slash');
    });
</script>
</body>
</html>
