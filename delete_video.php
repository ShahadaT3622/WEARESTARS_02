<?php
// Include your database configuration file
require 'db.php';

// Check if the ID is set in the URL
if (isset($_GET['id'])) {
    // Get the ID from the URL
    $id = $_GET['id'];

    // Prepare the SQL statement to delete the video
    $stmt = $conn->prepare("DELETE FROM videos WHERE id = ?");
    $stmt->bind_param("i", $id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the manage videos page with a success message
        header("Location: manage_videos.php?msg=Video+deleted+successfully");
    } else {
        // Redirect to the manage videos page with an error message
        header("Location: manage_videos.php?msg=Error+deleting+video");
    }

    // Close the statement
    $stmt->close();
} else {
    // Redirect to the manage videos page if no ID is set
    header("Location: manage_videos.php");
}

// Close the database connection
$conn->close();
?>
