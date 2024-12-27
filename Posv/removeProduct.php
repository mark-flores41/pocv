<?php
session_start();
include('db.php');

// Check if the user is an Admin
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : null;
$user_role = ''; // Default role is empty

if ($user_email) {
    // Get the role of the logged-in user
    $sql = "SELECT role FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $stmt->bind_result($role);
    $stmt->fetch();
    $user_role = $role;
    $stmt->close();
}

if ($user_role != 'Admin') {
    echo json_encode(['success' => false, 'message' => 'You are not authorized to remove products.']);
    exit;
}

// Remove product logic
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['product_id'])) {
    $product_id = $data['product_id'];

    // Prepare SQL query to delete the product
    $sql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete product.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Product ID is missing.']);
}
?>
