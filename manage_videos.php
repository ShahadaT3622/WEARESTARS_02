<?php
// Include your database configuration file
require 'db.php';

// Handle video upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $file_path = 'uploads/' . basename($_FILES['file']['name']);

    if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
        $stmt = $conn->prepare("INSERT INTO videos (title, description, price, file_path) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $title, $description, $price, $file_path);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch existing videos
$result = $conn->query("SELECT * FROM videos");

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Videos</title>
</head>
<body>
    <h1>Upload New Video</h1>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Video Title" required><br><br>
        <textarea name="description" placeholder="Description" required></textarea><br><br>
        <input type="text" name="price" placeholder="Price" required><br><br>
        <input type="file" name="file" required><br><br>
        <button type="submit">Upload</button>
    </form>

    <h2>Existing Videos</h2>
    <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li>
                    <strong><?php echo $row['title']; ?></strong><br>
                    <?php echo $row['description']; ?><br>
                    Price: $<?php echo $row['price']; ?><br>
                    <a href="edit_video.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                    <a href="delete_video.php?id=<?php echo $row['id']; ?>">Delete</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No videos found.</p>
    <?php endif; ?>
</body>
</html>
