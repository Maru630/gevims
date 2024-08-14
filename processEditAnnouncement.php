<?php
// Include the database connection file
include 'mysql_connect.php';

// Check if the form was submitted for editing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $ann_id = $_POST['ann_id']; // Hidden field for the ID of the announcement being edited
    $title = $_POST['title'];
    $content = $_POST['content'];
    $publishDate = $_POST['publishDate'];

    // Handle file upload
    $attachment = null;
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES["attachment"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        // Check if the directory exists, if not, create it
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Move the uploaded file to the server's directory
        if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $targetFilePath)) {
            $attachment = $targetFilePath;
        } else {
            echo "Error uploading file.";
            exit();
        }
    }

    // Prepare and execute the update statement
    if ($attachment) {
        $stmt = $conn->prepare("UPDATE announcements SET ann_title = ?, ann_content = ?, published_date = ?, attachment = ? WHERE ann_id = ?");
        $stmt->bind_param("ssssi", $title, $content, $publishDate, $attachment, $ann_id);
    } else {
        $stmt = $conn->prepare("UPDATE announcements SET ann_title = ?, ann_content = ?, published_date = ? WHERE ann_id = ?");
        $stmt->bind_param("sssi", $title, $content, $publishDate, $ann_id);
    }

    if ($stmt->execute()) {
        // Redirect to the announcements page with a success message
        header("Location: adminAnnouncements.php?message=edited");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
