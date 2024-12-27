<?php
session_start();
include('db.php');

// Check if the user is logged in and retrieve user info (including role)
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

// Check if the user is a Delivery Rider
$isDeliveryRider = ($user_role == 'Delivery Rider');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css?v=<?php echo time(); ?>">
    <style>
        .order {
        border: 2px solid #f39c12; /* Orange border to indicate pending */
        background-color: #fff7e6; /* Light yellow background for visibility */
        border-radius: 8px;
        padding: 16px;
        margin: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    /* Hover effect for orders */
    .order:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
    }

    /* Order headings */
    .order p {
        margin: 8px 0;
        font-size: 14px;
        color: #333;
    }

    /* Strong text styles in orders */
    .order p strong {
        color: #e67e22; /* Slightly darker orange for emphasis */
    }

    /* Image styling within an order */
    .order img {
        max-width: 100%;
        height: auto;
        border-radius: 4px;
        margin: 8px 0;
        border: 1px solid #ddd;
    }

    /* Accept Order Button */
    .order button {
        background-color: #27ae60; /* Green background */
        color: #fff;
        padding: 10px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    /* Hover effect for the button */
    .order button:hover {
        background-color: #219150; /* Darker green on hover */
    }

    /* Message for empty pending orders */
    .no-orders-message {
        text-align: center;
        font-size: 16px;
        color: #7f8c8d; /* Gray color */
        padding: 20px;
        background-color: #f7f7f7;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin: 16px 0;
    }
    #login-popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

#login-popup .popup-content {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.popup-buttons {
    margin-top: 20px;
}

.popup-buttons a,
.popup-buttons button {
    background-color: #27ae60; /* Green background */
    color: #fff;
    padding: 10px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    margin: 5px;
    text-decoration: none;
}

.popup-buttons button:hover,
.popup-buttons a:hover {
    background-color: #219150; /* Darker green on hover */
}

/* Add your styles here */
    </style>
</head>
<body>
<header>
    <div class="container">
        <h1>IRUMA HARDWARE SHOP</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li>
                    <?php 
                    if ($user_role == 'Admin') {
                        echo '<a href="addproduct.php">Add Products</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        echo '<a href="adminvieworders.php">View User Orders</a>';

                    } elseif ($isDeliveryRider) {
                        echo '<a href="deliveryriderorders.php">Delivery Orders</a>';
                    } else {
                        echo '<a href="orders.php">My Orders</a>';
                    }
                    ?>
                </li>
                <li><a href="updateinfo.php">Update Info</a></li>
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
    <section id="products">
        <h2 class="h2"><?php echo $isDeliveryRider ? "Pending Orders to Deliver" : "Available Products"; ?></h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php
            if (!$isDeliveryRider && $user_role != 'Admin') {
                // Fetch all products for non-admin users
                $sql = "SELECT * FROM products";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="product">
                                <img src="' . htmlspecialchars($row["image_path"]) . '" alt="' . htmlspecialchars($row["name"]) . '">
                                <h3>' . htmlspecialchars($row["name"]) . '</h3>
                                <p>₱' . number_format($row["price"], 2) . '</p>
                                <button onclick="addToCart(\'' . htmlspecialchars($row["name"]) . '\', ' . $row["price"] . ', ' . $row["product_id"] . ', \'' . htmlspecialchars($row["image_path"]) . '\')">Add to Cart</button>
                            </div>';
                    }
                } else {
                    echo "No products available.";
                }
            } elseif ($user_role == 'Admin') {
                $sql = "SELECT * FROM products";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="product">
                                <img src="' . htmlspecialchars($row["image_path"]) . '" alt="' . htmlspecialchars($row["name"]) . '">
                                <h3>' . htmlspecialchars($row["name"]) . '</h3>
                                <p>₱' . number_format($row["price"], 2) . '</p>
                                <button onclick="removeProduct(' . $row["product_id"] . ')">Remove Product</button>

                            </div>';
                    }
            }
        }
            ?>
        </div>
        <div id="login-popup">
    <div class="popup-content">
        <h2>Please Log In or Sign Up</h2>
        <p>You need to log in or sign up to add items to your cart.</p>
        <div class="popup-buttons">
            <button onclick="closePopup()">Close</button>
            <a href="login.php" class="login-btn">Log In</a>
            <a href="signup.php" class="signup-btn">Sign Up</a>
        </div>
    </div>
</div>
    </section>

    <?php if (!$isDeliveryRider && $user_role != 'Admin') { ?>
    <aside id="cart">
        <h2>Your Cart</h2>
        <ul id="cart-items"></ul>
        <button id="buy-button" onclick="checkout()">Buy</button>
    </aside>
    <?php } ?>
</div>

<script>
    const isLoggedIn = <?php echo json_encode(isset($_SESSION['user_email']) && !empty($_SESSION['user_email'])); ?>;
    const isAdmin = <?php echo json_encode($user_role === 'Admin'); ?>;

    let Items = [];

    // Add to cart function with admin check
    function addToCart(name, price, product_id, image_path) {
        if (!isLoggedIn) {
            alert('You must be logged in to add items to your cart.');
            return;
        }
        if (isAdmin) {
            alert('Admins cannot add products to the cart.');
            return;
        }
        const index = Items.findIndex(item => item.name === name);
        if (index !== -1) {
            Items[index].quantity += 1;
        } else {
            const item = { name, price, quantity: 1, product_id, image_path };
            Items.push(item);
        }
        updateCartDisplay();
    }

    //const isLoggedIn = <?php echo json_encode(isset($_SESSION['user_email']) && !empty($_SESSION['user_email'])); ?>;
    console.log("Is user logged in: ", isLoggedIn);
            // Retrieve PHP login status and pass it to JavaScript
    // Debugging log


            // Show the popup if the user is not logged in
            function showLoginPopup() {
        console.log("Showing login popup...");
        const popup = document.getElementById('login-popup');
        if (popup) {
            popup.style.display = 'flex';
        } else {
            console.error("Popup element not found!");
        }
    }
            // Close the popup
            function closePopup() {
                const popup = document.getElementById('login-popup');
                popup.style.display = 'none';
            }

            // Accept order (change status from pending to delivering)
            function acceptOrder(orderId) {
                fetch('updateOrderStatus.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ orderId: orderId, Status: 'delivering' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Order status updated to delivering.');
                        location.reload(); // Reload page to reflect changes
                    } else {
                        alert('Failed to update order status.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('There was an error updating the order status.');
                });
            }

            // Add to cart function with login check
            function addToCart(name, price, product_id, image_path) {
        console.log("Add to Cart Triggered");
        if (!isLoggedIn) {
            console.log("User is not logged in!");
            showLoginPopup();
            return;
        }

                const index = Items.findIndex(item => item.name === name);
                if (index !== -1) {
                    Items[index].quantity += 1;
                } else {
                    const item = { name, price, quantity: 1, product_id, image_path };
                    Items.push(item);
                }
                updateCartDisplay();
            }

            // Update cart display
            function updateCartDisplay() {
                const cartElement = document.getElementById('cart-items');
                cartElement.innerHTML = '';
                Items.forEach((item, index) => {
                    const li = document.createElement('li');
                    li.className = 'cart-item';
                    li.innerHTML = ` 
                        <span>${item.name} - ₱${item.price.toFixed(2)} x 
                        <div class="quantity">
                            <button onclick="updateQuantity(${index}, ${item.quantity - 1})">-</button>
                            <input type="number" value="${item.quantity}" min="1" max="10" onchange="updateQuantity(${index}, this.value)">
                            <button onclick="updateQuantity(${index}, ${item.quantity + 1})">+</button>
                        </div>
                        </span>
                        <button onclick="deleteFromCart(${index})">Delete</button>
                    `;
                    cartElement.appendChild(li);
                });
            }

            // Update quantity of item in cart
            function updateQuantity(index, quantity) {
                if (quantity < 1) return; // Avoid negative quantities
                Items[index].quantity = parseInt(quantity);
                updateCartDisplay();
            }

            // Delete item from cart
            function deleteFromCart(index) {
                Items.splice(index, 1);
                updateCartDisplay();
            }

            // Checkout function to send order data
            function checkout() {
                if (!isLoggedIn) {
                    alert('You must be logged in to place an order.');
                    return;
                }

                let totalPrice = 0;
                const cartData = Items.map(item => ({
                    product_id: item.product_id,
                    quantity: item.quantity,
                    price: item.price,
                    image_path: item.image_path
                }));

                Items.forEach(item => {
                    totalPrice += item.price * item.quantity;
                });

                const formData = new FormData();
                formData.append('items', JSON.stringify(cartData));
                formData.append('totalPrice', totalPrice);

                const userEmail = "<?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?>";
                formData.append('email', userEmail);

                const orderStatus = isLoggedIn ? 'pending' : 'pending';
                formData.append('Status', orderStatus); // Add order status to the form data

                fetch('checkout.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    Items = [];
                    updateCartDisplay();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('There was an error placing your order.');
                });
            }

            // Close popup if clicked outside
            window.addEventListener('click', function(event) {
                const popup = document.getElementById('login-popup');
                if (event.target === popup) {
                    closePopup();
                }
            }); //Other JavaScript functions remain the same
            function removeProduct(productId) {
    if (!confirm("Are you sure you want to remove this product?")) {
        return;
    }

    fetch('removeProduct.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Product removed successfully.');
            location.reload(); // Reload the page to update the product list
        } else {
            alert('Failed to remove product.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('There was an error removing the product.');
    });
}
</script>
</body>
</html>
