<?php
session_start();
include('db.php');

// Check if the user is logged in and retrieve user info (including role)
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : null;
$user_role = '';  // Default role is empty

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

// Check if the user is a Delivery Rider
$isDeliveryRider = ($user_role == 'Delivery Rider');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css?v=<?php echo time(); ?>">
    <title>Delivery Rider Orders</title>
    <style>
        /* Add your styles here (same as previous example) */
    </style>
</head>
<body>

<div class="container">
    <section id="delivery-orders">
        <h2>Orders Accepted by You</h2>
        <div class="orders-list">
            <?php
            if ($isDeliveryRider) {
                // Fetch all orders accepted by the current delivery rider
                $sql = "SELECT `order_id`, `product_id`, `quantity`, `price`, `total`, `user_email`, `created_at`, `image_path`, `status`
                        FROM `orders` 
                        WHERE `status` = 'delivering' AND `emailofdeliveryrider` = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $user_email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {    
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='order'>
                                <p><strong>Order ID:</strong> " . htmlspecialchars($row['order_id']) . "</p>
                                <p><strong>Product ID:</strong> " . htmlspecialchars($row['product_id']) . "</p>
                                <p><strong>Quantity:</strong> " . htmlspecialchars($row['quantity']) . "</p>
                                <p><strong>Price:</strong> ₱" . number_format($row['price'], 2) . "</p>
                                <p><strong>Total:</strong> ₱" . number_format($row['total'], 2) . "</p>
                                <p><strong>Customer Email:</strong> " . htmlspecialchars($row['user_email']) . "</p>
                                <p><strong>Created At:</strong> " . htmlspecialchars($row['created_at']) . "</p>
                                <img src='" . htmlspecialchars($row['image_path']) . "' alt='Product Image'>
                                <form method='POST' action='uploadEvidence.php' enctype='multipart/form-data'>
                                    <input type='hidden' name='order_id' value='" . $row['order_id'] . "'>
                                    <label for='evidence_image'>Upload Evidence:</label>
                                    <input type='file' name='evidence_image' required>
                                    <button type='submit'>Send Evidence</button>
                                </form>
                              </div>";
                    }
                } else {
                    echo "<p class='no-orders-message'>No orders accepted by you yet.</p>";
                }

                $stmt->close();
            } else {
                echo "<p>You do not have permission to view this page.</p>";
            }
            ?>
        </div>
    </section>
</div>
</body>
</html>
