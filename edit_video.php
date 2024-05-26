<?php
// Include your database configuration file
require 'db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Prepare the SQL statement to update the video
    $stmt = $conn->prepare("UPDATE videos SET title = ?, description = ?, price = ? WHERE id = ?");
    $stmt->bind_param("ssdi", $title, $description, $price, $id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the manage videos page with a success message
        header("Location: manage_videos.php?msg=Video+updated+successfully");
    } else {
        // Redirect to the manage videos page with an error message
        header("Location: manage_videos.php?msg=Error+updating+video");
    }

    // Close the statement
    $stmt->close();
} else {
    // Check if the ID is set in the URL
    if (isset($_GET['id'])) {
        // Get the ID from the URL
        $id = $_GET['id'];

        // Prepare the SQL statement to select the video
        $stmt = $conn->prepare("SELECT * FROM videos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch the video data
        if ($result->num_rows > 0) {
            $video = $result->fetch_assoc();
        } else {
            // Redirect to the manage videos page if no video is found
            header("Location: manage_videos.php");
        }

        // Close the statement
        $stmt->close();
    } else {
        // Redirect to the manage videos page if no ID is set
        header("Location: manage_videos.php");
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Video</title>
</head>
<body>
    <h1>Edit Video</h1>
    <form method="post" action="edit_video.php">
        <input type="hidden" name="id" value="<?php echo $video['id']; ?>">
        <label for="title">Video Title:</label>
        <input type="text" name="title" id="title" value="<?php echo $video['title']; ?>" required><br><br>
        <label for="description">Description:</label>
        <textarea name="description" id="description" required><?php echo $video['description']; ?></textarea><br><br>
        <label for="price">Price:</label>
        <input type="text" name="price" id="price" value="<?php echo $video['price']; ?>" required><br><br>
        <button type="submit">Update Video</button>
    </form>
</body>
</html>
