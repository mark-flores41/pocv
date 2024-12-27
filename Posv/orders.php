<?php
session_start();
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    echo '<script>alert("Please log in to view your orders.");</script>';
    echo '<script>window.location.href = "login.php";</script>';
    exit;
}

// Get the logged-in user's email
$user_email = $_SESSION['user_email'];

// Query to get all orders for the user, grouped by month, including the status
$sql = "SELECT 
            DATE_FORMAT(o.created_at, '%M %Y') AS order_month,
            o.order_id,
            o.product_id,
            o.quantity,
            o.price,
            o.total,
            o.user_email,
            o.emailofdeliveryrider,
            o.created_at,
            o.image_path,
            o.status,
            o.evidence_path,
            o.evidence_image,
            u1.municipality AS guest_municipality,
            u1.barangay AS guest_barangay,
            u1.sitioorzone AS guest_sitioorzone,
            u2.contact AS rider_contact
        FROM orders o
        LEFT JOIN user u1 ON o.user_email = u1.email
        LEFT JOIN user u2 ON o.emailofdeliveryrider = u2.email
        WHERE o.user_email = ? 
        ORDER BY o.created_at DESC";


$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

$orders_by_month = [];

// Group orders by month
while ($row = $result->fetch_assoc()) {
    $month = $row['order_month'];
    if (!isset($orders_by_month[$month])) {
        $orders_by_month[$month] = [];
    }
    $orders_by_month[$month][] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <style>
      body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    max-width:1200px;
    margin: 40px auto;
    margin-top:160px;
    padding:    20px;
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

h2 {
    font-size: 24px;
    color: #333;
    margin-bottom: 15px;
}

.month-section {
    padding: 15px;
    border: 1px solid #ddd;
    margin-bottom: 20px;
    border-radius: 5px;
    background-color: #fafafa;
}

.order-item {
    padding: 15px;
    border: 1px solid #ddd;
    margin-bottom: 10px;
    border-radius: 8px;
    background-color: #fff;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.order-item:last-child {
    margin-bottom: 0;
}
    
.order-details {
    width: 100%;
    display: flex;
    flex-wrap: wrap; /* Wrap items to the next line if necessary */
    justify-content: space-between; /* Evenly distribute space between items */
    gap: 10px; /* Add spacing between items */
    margin-bottom: 8px; /* Bottom margin for spacing */
    align-items: flex-start; /* Align items at the top */
}

.order-details span {
    font-size: 14px;
    color: #555;
    text-align: left;
}

.order-details .order-total {
    font-weight: bold;
    color: #e74c3c;
}

.order-details img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
}

.order-details .order-image {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-left: 15px;
}

.order-item:hover {
    background-color: #f1f1f1;
    cursor: pointer;
}

@media (max-width: 768px) {
    .order-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .order-details {
        width: 100%;
        margin-bottom: 10px;
    }

    .order-details .order-total {
        margin-top: 5px;
    }

    .order-details img {
        width: 60px;
        height: 60px;
    }

    .container {
        padding: 10px;
        padding-top:50px;
        margin-top:170px;  
    }
}
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
<header>
        <div class="containers">
                <h1>IRUMA HARDWARE SHOP</h1>
                <nav>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="#">Products</a></li>
                        <li><a href="#">Contact</a></li>
                        <?php if (isset($_SESSION['user_email'])) { ?>
                            <li><a href="logout.php" class="logout-btn">Logout</a></li>
                        <?php } else { ?>
                            <li><a href="login.php" class="login-btn">Login</a></li>
                        <?php } ?>
                    
                    </ul>
                </nav>
            </div>
        </header>
    <div class="container">
        <h1>My Orders</h1>
        <?php if (count($orders_by_month) > 0): ?>
            <?php foreach ($orders_by_month as $month => $orders): ?>
                <div class="month-section">
                    <h2><?php echo $month; ?></h2>
                    <?php foreach ($orders as $order): ?>
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
        <span>Status: <br><?php echo ucfirst($order['status']); ?></span>
    </div>
    <div class="order-details">
        <span>Municipality: <br><?php echo $order['guest_municipality']; ?></span>
        <span>Barangay: <br><?php echo $order['guest_barangay']; ?></span>
        <span>Sitio/Zone: <br><?php echo $order['guest_sitioorzone']; ?></span>
    </div>
    <div class="order-details">
        <span>Rider Email: <br><?php echo $order['emailofdeliveryrider'] ? $order['emailofdeliveryrider'] : 'Not Assigned'; ?></span>
        <span>Rider Contact: <br><?php echo $order['rider_contact'] ? $order['rider_contact'] : 'Not Assigned'; ?></span>
    </div>
    <div class="order-image">
        <img src="<?php echo $order['image_path']; ?>" alt="Product Image" style="width: 100px;">
    </div>
</div>


                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No orders found for your account.</p>
        <?php endif; ?>
    </div>
</body>
</html>
