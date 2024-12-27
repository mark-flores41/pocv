<?php
session_start();
include('db.php');

// Check if the user is logged in
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : null;
if (!$user_email) {
    echo "You need to log in first.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if a file is uploaded
    if (isset($_FILES['evidence_image']) && $_FILES['evidence_image']['error'] == 0) {
        $order_id = $_POST['order_id'];
        $file_name = $_FILES['evidence_image']['name'];
        $file_tmp = $_FILES['evidence_image']['tmp_name'];
        $file_size = $_FILES['evidence_image']['size'];
        $file_type = $_FILES['evidence_image']['type'];
        
        // Set the upload directory
        $upload_dir = 'uploads/evidence/';
        
        // Generate a unique filename for the image to avoid overwriting
        $new_file_name = $order_id . '-' . time() . '-' . basename($file_name);
        $upload_path = $upload_dir . $new_file_name;

        // Validate the file type (you can expand this as needed)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file_type, $allowed_types)) {
            echo "Invalid file type. Please upload an image file.";
            exit;
        }

        // Move the uploaded file to the evidence directory
        if (move_uploaded_file($file_tmp, $upload_path)) {
            // Update the order in the database with the path of the uploaded image
            $sql = "UPDATE orders SET evidence_image = ? WHERE order_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $upload_path, $order_id);
            if ($stmt->execute()) {
                echo "Evidence uploaded successfully!";
                echo "<a href='deliveryriderorders.php'>done</a>";
            } else {
                echo "Failed to update the order with the evidence.";
            }
            $stmt->close();
        } else {
            echo "Failed to upload the evidence image.";
        }
    } else {
        echo "No image uploaded.";
    }
} else {
    echo "Invalid request.";
}
?>
