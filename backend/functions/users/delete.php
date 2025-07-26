<?php
// Start session
if (session_id() === '') {
    session_start();
}

// Include database connection file
include_once(__DIR__ . '/../../../dbconnect.php');

$id = $_GET['id']; // Get user ID from URL

// Check if ID is invalid
if (!isset($id) || !is_numeric($id)) {
    echo '<script>alert("Invalid user ID!"); window.location.href = "index.php";</script>';
    exit();
}

// --------------------------------------------------
// Delete user from DB
// --------------------------------------------------
$sqlDelete = "DELETE FROM users WHERE id = ?";
$stmtDelete = $conn->prepare($sqlDelete);
$stmtDelete->bind_param("i", $id); // "i": integer (for id)

if ($stmtDelete->execute()) {
    // Success: Redirect directly without a pop-up message
    echo '<script>window.location.href = "index.php";</script>';
} else {
    // Error: Show a basic browser alert
    echo '<script>alert("Error deleting user: ' . $stmtDelete->error . '");</script>';
}

$stmtDelete->close();
$conn->close();
?>
