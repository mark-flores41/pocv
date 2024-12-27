<?php
session_start();
include('db.php');

// Check if items and email are passed in the request
if (!isset($_POST['items']) || !isset($_POST['email'])) {
    echo "Invalid request!";
    exit;
}

$items = json_decode($_POST['items'], true);
$user_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_email']) && $_SESSION['user_email'] === $user_email;

// Set status based on user login
$order_status = $is_logged_in ? 'pending' : 'pending';  // For logged-in users, status is 'confirmed', otherwise 'pending'

if (empty($items) || empty($user_email)) {
    echo "Invalid data received!";
    exit;
}

$successCount = 0;

foreach ($items as $item) {
    // Validate and sanitize item details
    $product_id = intval($item['product_id']);
    $quantity = intval($item['quantity']);
    $price = floatval($item['price']);
    $image_path = isset($item['image_path']) ? $item['image_path'] : 'default-image.jpg'; // Default image path
    $total = $quantity * $price;
    $created_at = date("Y-m-d H:i:s");

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO orders (product_id, quantity, price, total, user_email, Status, created_at, image_path) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iidsssss", $product_id, $quantity, $price, $total, $user_email, $order_status, $created_at, $image_path);

    if ($stmt->execute()) {
        $successCount++;
    } else {
        // Log errors for debugging
        error_log("Database error: " . $stmt->error);
    }
}

$conn->close();

// Provide a consolidated response
echo "$successCount orders placed successfully!";
?>
