<?php
// Include the database connection file
include 'mysql_connect.php';

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id'])) {
    $ann_id = $_GET['id'];

    // Prepare the delete statement
    $sql = "DELETE FROM announcements WHERE ann_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ann_id);

    if ($stmt->execute()) {
        // Redirect to the announcements page after successful deletion
        header("Location: adminAnnouncements.php?message=deleted");
        exit;
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No announcement ID specified.";
    exit;
}

$conn->close();
?>
