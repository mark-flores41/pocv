<?php
session_start();
include('db.php'); // Include the database connection file

// Ensure that the user is an admin
if (!isset($_SESSION['user_email'])) {
    echo '<script>alert("Access denied. You must be an admin to view this page.");</script>';
    echo '<script>window.location.href = "login.php";</script>';
    exit;
}

// Handle the status update
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = 'delivered'; // Update the order to 'delivered'

    // Update the order status in the database
    $sql = "UPDATE orders SET status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $new_status, $order_id);
    
    if ($stmt->execute()) {
        echo '<script>alert("Order status updated to Delivered.");</script>';
    } else {
        echo '<script>alert("Error updating order status.");</script>';
    }
}

// Fetch orders grouped by status
$sql = "SELECT 
            o.order_id, 
            o.product_id, 
            o.quantity, 
            o.price, 
            o.total, 
            o.user_email, 
            o.created_at, 
            o.image_path, 
            o.status, 
            o.evidence_image,  -- Fetch evidence image
            u.email AS user_email
        FROM orders o
        LEFT JOIN user u ON o.user_email = u.email
        ORDER BY o.created_at DESC";

$result = $conn->query($sql);

// Separate orders into different statuses
$orders_by_status = [
    'pending' => [],
    'delivering' => [],
    'delivered' => []
];

while ($row = $result->fetch_assoc()) {
    $orders_by_status[$row['status']][] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Orders</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <style>
        /* Styling the page */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 32px;
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .order-section {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 30px;
        }

        .order-column {
            flex: 1;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .order-column h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .order-item {
            padding: 15px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .order-item .order-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .order-item .order-details span {
            font-size: 14px;
            color: #555;
        }

        .order-item .order-details .order-total {
            font-weight: bold;
            color: #e74c3c;
        }

        .order-item .order-image {
            margin-top: 10px;
            text-align: center;
        }

        .order-item .order-image img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }

        .order-item .done-btn {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .order-item .done-btn:hover {
            background-color: #218838;
        }

        .evidence-image {
            margin-top: 10px;
            text-align: center;
        }

        .evidence-image img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .order-section {
                flex-direction: column;
            }
            .order-column {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>View All Orders</h1>

        <div class="order-section">
            <!-- Pending Orders -->
            <div class="order-column">
                <h2>Pending Orders</h2>
                <?php if (!empty($orders_by_status['pending'])): ?>
                    <?php foreach ($orders_by_status['pending'] as $order): ?>
                        <div class="order-item">
                            <div class="order-details">
                                <span>Order ID: <?php echo $order['order_id']; ?></span><br>
                                <span>Date: <?php echo date('d-M-Y h:i A', strtotime($order['created_at'])); ?></span><br>
                            </div>
                            <div class="order-details">
                                <span>Product ID: <?php echo $order['product_id']; ?></span>
                                <span>Quantity: <?php echo $order['quantity']; ?></span>
                                <span class="order-total">Total: ₱<?php echo number_format($order['total'], 2); ?></span>
                            </div>
                            <div class="order-details">
                                <span>Price: ₱<?php echo number_format($order['price'], 2); ?></span>
                            </div>
                            <div class="order-image">
                                <img src="<?php echo $order['image_path']; ?>" alt="Product Image">
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No pending orders.</p>
                <?php endif; ?>
            </div>

            <!-- Delivering Orders -->
            <div class="order-column">
                <h2>Delivering Orders</h2>
                <?php if (!empty($orders_by_status['delivering'])): ?>
                    <?php foreach ($orders_by_status['delivering'] as $order): ?>
                        <div class="order-item">
                            <div class="order-details">
                                <span>Order ID: <?php echo $order['order_id']; ?></span>
                                <span>Date: <?php echo date('d-M-Y h:i A', strtotime($order['created_at'])); ?></span>
                            </div>
                            <div class="order-details">
                                <span>Product ID: <?php echo $order['product_id']; ?></span>
                                <span>Quantity: <?php echo $order['quantity']; ?></span>
                                <span class="order-total">Total: ₱<?php echo number_format($order['total'], 2); ?></span>
                            </div>
                            <div class="order-details">
                                <span>Price: ₱<?php echo number_format($order['price'], 2); ?></span>
                            </div>
                            <div class="order-image">
                                <img src="<?php echo $order['image_path']; ?>" alt="Product Image">
                            </div>
                            <!-- Done Button to update status -->
                            <form method="POST" action="">
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <button type="submit" name="update_status" class="done-btn">Mark as Delivered</button>
                            </form>
                            <!-- Evidence Image Section -->
                            <div class="evidence-image">
                                <?php if ($order['evidence_image']): ?>
                                    <img src="<?php echo $order['evidence_image']; ?>" alt="Evidence Image">
                                <?php else: ?>
                                    <p>No evidence uploaded yet.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No delivering orders.</p>
                <?php endif; ?>
            </div>

            <!-- Delivered Orders -->
            <div class="order-column">
                <h2>Delivered Orders</h2>
                <?php if (!empty($orders_by_status['delivered'])): ?>
                    <?php foreach ($orders_by_status['delivered'] as $order): ?>
                        <div class="order-item">
                            <div class="order-details">
                                <span>Order ID: <?php echo $order['order_id']; ?></span>
                                <span>Date: <?php echo date('d-M-Y h:i A', strtotime($order['created_at'])); ?></span>
                            </div>
                            <div class="order-details">
                                <span>Product ID: <?php echo $order['product_id']; ?></span>
                                <span>Quantity: <?php echo $order['quantity']; ?></span>
                                <span class="order-total">Total: ₱<?php echo number_format($order['total'], 2); ?></span>
                            </div>
                            <div class="order-details">
                                <span>Price: ₱<?php echo number_format($order['price'], 2); ?></span>
                            </div>
                            <div class="order-image">
                                <img src="<?php echo $order['image_path']; ?>" alt="Product Image">
                            </div>
                            <div class="evidence-image">
                                <?php if ($order['evidence_image']): ?>
                                    <img src="<?php echo $order['evidence_image']; ?>" alt="Evidence Image">
                                <?php else: ?>
                                    <p>No evidence uploaded.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No delivered orders.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
