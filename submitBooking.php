<?php
session_start();
include 'mysql_connect.php';

header('Content-Type: application/json');

$response = [];

function generateBookingNumber($conn) {
    // Atomic update to get the new booking number
    $conn->query("UPDATE booking_sequence SET last_booking_no = LAST_INSERT_ID(last_booking_no + 1)");
    $result = $conn->query("SELECT LAST_INSERT_ID() as last_booking_no");
    $row = $result->fetch_assoc();
    $newNumber = str_pad($row['last_booking_no'], 6, '0', STR_PAD_LEFT);
    return $newNumber;
}

function generateRequestNumber($conn) {
    // Atomic update to get the new request number
    $conn->query("UPDATE booking_sequence SET last_request_no = LAST_INSERT_ID(last_request_no + 1)");
    $result = $conn->query("SELECT LAST_INSERT_ID() as last_request_no");
    $row = $result->fetch_assoc();
    $newNumber = str_pad($row['last_request_no'], 6, '0', STR_PAD_LEFT);
    return $newNumber;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_type = $_POST['form_type'];

    if ($form_type === 'amenities') {
        $user_id = $_POST['greenwoods_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $address = $_POST['address'];
        $phone_number = $_POST['phone'];
        $email = $_POST['email'];
        $amenity = $_POST['amenity'];
        $date = $_POST['date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $purpose = $_POST['purpose'];
        
        // Get the additional fees and options
        $additional_fees_array = isset($_POST['additional_fees']) ? $_POST['additional_fees'] : [];
        $additionals = implode(", ", $additional_fees_array);
        $total_additional_fees = count($additional_fees_array) * 600;
    
        try {
            $booking_no = generateBookingNumber($conn);
    
            // Insert data into booking_amenities table
            $sql = "INSERT INTO booking_amenities (booking_no, user_id, first_name, last_name, address, phone_number, email, amenities, additionals, additional_fees, date, start_time, end_time, purpose) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssssssss", $booking_no, $user_id, $first_name, $last_name, $address, $phone_number, $email, $amenity, $additionals, $total_additional_fees, $date, $start_time, $end_time, $purpose);
    
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Reservation successful!';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error: ' . $stmt->error;
            }
    
            $stmt->close();
        } catch (Exception $e) {
            $response['status'] = 'error';
            $response['message'] = 'Error: ' . $e->getMessage();
        }
    } elseif ($form_type === 'services') {
        // Handle booking services form submission
        $user_id = $_POST['greenwoods_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $address = $_POST['address'];
        $phone_number = $_POST['phone'];
        $email = $_POST['email'];
        $date_of_request = $_POST['date_of_request'];
        $preferred_date = $_POST['preferred_date'];
        $preferred_time = $_POST['preferred_time'];
        $service_type = implode(", ", $_POST['service_type']);
        $description = $_POST['description'];
        
        $photo_proof = null;
        if (isset($_FILES['attachments']) && $_FILES['attachments']['error'] === UPLOAD_ERR_OK) {
            $photo_proof = file_get_contents($_FILES['attachments']['tmp_name']);
        }

        try {
            $request_no = generateRequestNumber($conn);

            // Insert data into booking_services table
            $sql = "INSERT INTO booking_services (request_no, user_id, first_name, last_name, address, phone_number, email, requestdate, preferreddate, preferredtime, service_type, issue_desc, photo_proof) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssssss", $request_no, $user_id, $first_name, $last_name, $address, $phone_number, $email, $date_of_request, $preferred_date, $preferred_time, $service_type, $description, $photo_proof);

            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Service request successful!';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error: ' . $stmt->error;
            }

            $stmt->close();
        } catch (Exception $e) {
            $response['status'] = 'error';
            $response['message'] = 'Error: ' . $e->getMessage();
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid form type.';
    }

    $conn->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
