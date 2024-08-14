<?php
session_start();
include 'mysql_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Get data from the POST request
$reservation_id = $_POST['id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$address = $_POST['address'];
$phone_number = $_POST['phone_number'];
$email = $_POST['email'];
$amenity = $_POST['amenity'];
$date = $_POST['date'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];
$purpose = $_POST['purpose'];

// Prepare the SQL statement to update the reservation
$sql = "UPDATE booking_amenities SET first_name = ?, last_name = ?, address = ?, phone_number = ?, email = ?, amenity = ?, date = ?, start_time = ?, end_time = ?, purpose = ? WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssssii", $first_name, $last_name, $address, $phone_number, $email, $amenity, $date, $start_time, $end_time, $purpose, $reservation_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update reservation.']);
}

$stmt->close();
$conn->close();
?>
