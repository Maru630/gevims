<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'mysql_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to submit an inquiry.']);
    exit();
}

// Check if acknowledgment is given
$acknowledgment = isset($_POST['acknowledgment']) ? 1 : 0;
if ($acknowledgment === 0) {
    echo json_encode(['status' => 'error', 'message' => 'You must acknowledge the terms before submitting the inquiry.']);
    exit();
}

// Fetch form data
$greenwoods_id = $_SESSION['user_id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$address = $_POST['address'];
$phone_number = $_POST['phone'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$details = $_POST['details'];

// Check if the subject already exists
$check_sql = "SELECT COUNT(*) FROM inquiries WHERE inq_sub = ?";
$stmt_check = $conn->prepare($check_sql);
$stmt_check->bind_param("s", $subject);
$stmt_check->execute();
$stmt_check->bind_result($count);
$stmt_check->fetch();
$stmt_check->close();

if ($count > 0) {
    echo json_encode(['status' => 'error', 'message' => 'An inquiry with this subject already exists. Please use a different subject.']);
    exit();
}

// Handle file upload if any
$attachment = null;
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $attachment = file_get_contents($_FILES['attachment']['tmp_name']);
}

// Prepare the SQL query to insert the inquiry
$sql = "INSERT INTO inquiries (first_name, last_name, address, phone_number, email, inq_sub, inq_det, attachment, acknowledgment) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Bind the parameters
$stmt->bind_param("ssssssssi", $first_name, $last_name, $address, $phone_number, $email, $subject, $details, $attachment, $acknowledgment);

// Execute the statement and handle the result
if ($stmt->execute()) {
    // Success response
    echo json_encode(['status' => 'success', 'message' => 'Your inquiry has been submitted successfully.']);
} else {
    // Error response
    echo json_encode(['status' => 'error', 'message' => 'Failed to submit your inquiry. Please try again.']);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
