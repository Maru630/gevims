<?php
session_start();
include 'mysql_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $user_type = $_SESSION['user_type'];
    $fields = [];

    foreach ($_POST as $key => $value) {
        $fields[$key] = $value;
    }

    if ($user_type === 'resident') {
        $sql = "UPDATE residents SET firstname = ?, lastname = ?, house_number = ?, lot_number = ?, block_number = ?, street = ?, phase = ?, area_number = ?, city = ?, zip_code = ?, email = ? WHERE greenwoods_id = ?";
    } else {
        $sql = "UPDATE visitors SET firstname = ?, lastname = ?, email = ?, address = ? WHERE visitor_id = ?";
    }

    $stmt = $conn->prepare($sql);

    if ($user_type === 'resident') {
        $stmt->bind_param(
            "ssssssssssss",
            $fields['firstname'],
            $fields['lastname'],
            $fields['house_no'],
            $fields['lot_no'],
            $fields['block_no'],
            $fields['street'],
            $fields['phase'],
            $fields['area_no'],
            $fields['city_dropdown'],
            $fields['zip_code_dropdown'],
            $fields['email'],
            $user_id
        );
    } else {
        $stmt->bind_param(
            "ssssi",
            $fields['firstname'],
            $fields['lastname'],
            $fields['email'],
            $fields['address'],
            $user_id
        );
    }

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'title' => 'Success',
            'message' => 'Profile updated successfully!',
            'redirect' => 'profile.php'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'title' => 'Error',
            'message' => 'Failed to update profile. Please try again later.'
        ]);
    }

    $stmt->close();
    $conn->close();
}
?>
