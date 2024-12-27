<?php
session_start();
include('db.php');

header('Content-Type: application/json');

// Ensure the request is POST and contains valid data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $orderId = isset($input['orderId']) ? intval($input['orderId']) : null;
    $newStatus = isset($input['Status']) ? $input['Status'] : null;

    if ($orderId && $newStatus) {
        // Check if the user is logged in as a Delivery Rider
        $user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : null;

        if ($user_email) {
            $stmt = $conn->prepare("SELECT role FROM user WHERE email = ?");
            $stmt->bind_param("s", $user_email);
            $stmt->execute();
            $stmt->bind_result($role);
            $stmt->fetch();
            $stmt->close();

            if ($role === 'Delivery Rider') {
                // Update the order status and assign the rider's email to the order
                $updateStmt = $conn->prepare("UPDATE orders SET status = ?, emailofdeliveryrider = ? WHERE order_id = ?");
                $updateStmt->bind_param("ssi", $newStatus, $user_email, $orderId);

                if ($updateStmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Order status updated and Delivery Rider assigned.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update order status or assign Delivery Rider.']);
                }

                $updateStmt->close();
            } else {
                echo json_encode(['success' => false, 'message' => 'Unauthorized user role.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
