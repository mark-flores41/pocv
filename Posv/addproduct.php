<?php
session_start();
include('db.php');

// Check if the user is logged in and has admin privileges
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : null;
$user_role = '';

// Fetch user role from the database
if ($user_email) {
    $sql = "SELECT role FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $stmt->bind_result($role);
    $stmt->fetch();
    $user_role = $role;
    $stmt->close();
}

// Restrict access if the user is not an admin
if ($user_role !== 'Admin') {
    echo "Access denied. Only administrators can add products.";
    exit;
}

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $image_path = $_POST['image_path'] ?? '';

    // Validate input
    if (empty($name) || empty($price) || empty($image_path)) {
        $message = "All fields are required.";
    } elseif (!is_numeric($price) || $price <= 0) {
        $message = "Price must be a positive number.";
    } else {
        // Insert product into the database
        $sql = "INSERT INTO products (name, price, image_path) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sds", $name, $price, $image_path);

        if ($stmt->execute()) {
            $message = "Product added successfully!";
        } else {
            $message = "Error adding product: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
}

h1, h2 {
    color: #444;
    text-align: center;
}

.container {
    width: 80%;
    margin: 0 auto;
    padding: 20px;
}

/* Header Styles */
header {
    background: #007bff;
    color: #fff;
    padding: 20px 0;
}

header .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

header h1 {
    margin: 0;
    font-size: 1.8em;
}

header nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
}

header nav ul li {
    margin-left: 20px;
}

header nav ul li a {
    color: #fff;
    text-decoration: none;
    font-weight: bold;
}

header nav ul li a:hover {
    text-decoration: underline;
}

.logout-btn {
    background-color: #dc3545;
    padding: 5px 10px;
    border-radius: 3px;
    color: #fff;
    text-decoration: none;
}

.logout-btn:hover {
    background-color: #c82333;
}

/* Form Styles */
form {
    background: #fff;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    margin: 20px auto;
}

form div {
    margin-bottom: 15px;
}

form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

form input[type="text"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 1em;
}

form button {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1em;
}

form button:hover {
    background-color: #218838;
}

/* Footer Styles (Optional) */
footer {
    background: #444;
    color: #fff;
    text-align: center;
    padding: 10px 0;
    margin-top: 20px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 90%;
    }

    header .container {
        flex-direction: column;
        text-align: center;
    }

    header nav ul {
        flex-direction: column;
        align-items: center;
    }

    header nav ul li {
        margin-left: 0;
        margin-bottom: 10px;
    }
}/* Add your styles here */
    </style>
    <script>
        // Display a popup message if $message is set
        window.onload = function() {
            <?php if (!empty($message)): ?>
                alert("<?php echo $message; ?>");
            <?php endif; ?>
        };
    </script>
</head>
<body>
    <header>
        <div class="container">
            <h1>IRUMA HARDWARE SHOP</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="adminvieworders.php">View Orders</a></li>
                    <li><a href="logout.php" class="logout-btn">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h2>Add New Product </h2>
        <form action="addproduct.php" method="POST">
            <div>
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="price">Price (₱):</label>
                <input type="text" id="price" name="price" required>
            </div>
            <div>
                <label for="image_path">Image Path:</label>
                <input type="text" id="image_path" name="image_path" placeholder="e.g., images/product.jpg" required>
            </div>
            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>
