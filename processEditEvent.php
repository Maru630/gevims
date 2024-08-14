<?php
// Include the database connection file
include 'mysql_connect.php';

// Check if the form was submitted for editing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $event_id = $_POST['event_id']; // Hidden field for the ID of the event being edited
    $event_name = $_POST['eventName'];
    $event_purpose = $_POST['what'];
    $event_venue = $_POST['where'];
    $event_day = $_POST['when'];
    $event_desc = $_POST['description'];
    
    // Combine the time inputs into one
    $startHour = $_POST['startHour'];
    $startMinute = $_POST['startMinute'];
    $startPeriod = $_POST['startPeriod'];

    $endHour = $_POST['endHour'];
    $endMinute = $_POST['endMinute'];
    $endPeriod = $_POST['endPeriod'];

    // Convert to 24-hour format
    $startHour = ($startPeriod == 'PM' && $startHour != 12) ? $startHour + 12 : $startHour;
    $startHour = ($startPeriod == 'AM' && $startHour == 12) ? 0 : $startHour;
    $event_stime = sprintf('%02d:%02d:00', $startHour, $startMinute);

    $endHour = ($endPeriod == 'PM' && $endHour != 12) ? $endHour + 12 : $endHour;
    $endHour = ($endPeriod == 'AM' && $endHour == 12) ? 0 : $endHour;
    $event_etime = sprintf('%02d:%02d:00', $endHour, $endMinute);

    $publish_date = $_POST['publishDate'];

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
        $stmt = $conn->prepare("UPDATE events SET event_name = ?, event_purpose = ?, event_venue = ?, event_day = ?, event_stime = ?, event_etime = ?, event_desc = ?, publish_date = ?, attachment = ? WHERE event_id = ?");
        $stmt->bind_param("sssssssssi", $event_name, $event_purpose, $event_venue, $event_day, $event_stime, $event_etime, $event_desc, $publish_date, $attachment, $event_id);
    } else {
        $stmt = $conn->prepare("UPDATE events SET event_name = ?, event_purpose = ?, event_venue = ?, event_day = ?, event_stime = ?, event_etime = ?, event_desc = ?, publish_date = ? WHERE event_id = ?");
        $stmt->bind_param("ssssssssi", $event_name, $event_purpose, $event_venue, $event_day, $event_stime, $event_etime, $event_desc, $publish_date, $event_id);
    }

    if ($stmt->execute()) {
        // Redirect to the events page with a success message
        header("Location: adminEvents.php?message=edited");
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
