<?php
// Include the database connection file
include 'mysql_connect.php';

// Function to handle redirection with a message
function redirectWithMessage($message) {
    header("Location: adminAnnouncements.php?message=$message");
    exit();
}

// Check if the form was submitted for creation or editing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Determine if this is an edit or create operation
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Handle the Published Date
    $publishDate = $_POST['publishDate'];
    if (empty($publishDate)) {
        $publishDate = date('Y-m-d'); // Use today's date if no date is provided
    }

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
            exit;
        }
    }

    if ($id) {
        // Editing an existing announcement
        if ($attachment) {
            $stmt = $conn->prepare("UPDATE announcements SET ann_title = ?, ann_content = ?, published_date = ?, attachment = ? WHERE ann_id = ?");
            $stmt->bind_param("ssssi", $title, $content, $publishDate, $attachment, $id);
        } else {
            $stmt = $conn->prepare("UPDATE announcements SET ann_title = ?, ann_content = ?, published_date = ? WHERE ann_id = ?");
            $stmt->bind_param("sssi", $title, $content, $publishDate, $id);
        }

        if ($stmt->execute()) {
            redirectWithMessage("edited");
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        // Creating a new announcement
        $stmt = $conn->prepare("INSERT INTO announcements (ann_title, ann_content, published_date, attachment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $content, $publishDate, $attachment);

        if ($stmt->execute()) {
            redirectWithMessage("created");
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    // Handle deletion
    $id = $_GET['id'];

    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM announcements WHERE ann_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        redirectWithMessage("deleted");
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
