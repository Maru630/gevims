<?php
include 'mysql_connect.php';

date_default_timezone_set('Asia/Manila');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $otp = $_POST['otp'];
    $user_type = $_POST['user_type'];

    // Log received data for debugging
    error_log("Received email: $email, OTP: $otp, User type: $user_type");

    if ($user_type == 'visitor') {
        // Check if the OTP is correct and not expired for visitor
        $otp_query = "SELECT * FROM temp_visitor WHERE email = '$email' AND otp = '$otp' AND otp_expiry > NOW()";
    } elseif ($user_type == 'resident') {
        // Check if the OTP is correct and not expired for resident
        $otp_query = "SELECT * FROM temp_resident WHERE email = '$email' AND otp = '$otp' AND otp_expiry > NOW()";
    } else {
        error_log("Invalid user type: $user_type"); // Log invalid user type for debugging
        $response = [
            'title' => 'Error',
            'message' => 'Invalid user type.',
            'status' => 'error'
        ];
        echo json_encode($response);
        exit;
    }

    $otp_result = mysqli_query($conn, $otp_query);

    if ($otp_result) {
        if (mysqli_num_rows($otp_result) > 0) {
            // OTP is correct and not expired
            $row = mysqli_fetch_assoc($otp_result);

            // Debugging information
            error_log("OTP Query Success: " . print_r($row, true));

            if ($user_type == 'visitor') {
                $visitor_id = $row['visitor_id'];
                $firstname = $row['firstname'];
                $lastname = $row['lastname'];
                $address = $row['address'];
                $password = $row['password'];
                $creation_date = date("Y-m-d");
                $update_date = date("Y-m-d");

                // Insert the visitor data into the visitor table
                $sql_insert_visitor = "INSERT INTO visitor (visitor_id, firstname, lastname, email, address, password, dateofcreation, dateofupdate) 
                                       VALUES ('$visitor_id', '$firstname', '$lastname', '$email', '$address', '$password', '$creation_date', '$update_date')";

                if (mysqli_query($conn, $sql_insert_visitor)) {
                    // Delete the temporary visitor record
                    $sql_delete_temp = "DELETE FROM temp_visitor WHERE email = '$email'";
                    mysqli_query($conn, $sql_delete_temp);

                    $response = [
                        'title' => 'Verification Successful',
                        'message' => 'Your account has been verified. You can now log in.',
                        'status' => 'success',
                        'redirect' => 'login.php'
                    ];
                } else {
                    $response = [
                        'title' => 'Error',
                        'message' => 'Failed to insert visitor data: ' . mysqli_error($conn),
                        'status' => 'error'
                    ];
                    error_log('Database error: ' . mysqli_error($conn));
                }
            } elseif ($user_type == 'resident') {
                $greenwoods_id = $row['greenwoods_id'];
                $firstname = $row['firstname'];
                $lastname = $row['lastname'];
                $house_number = $row['house_number'];
                $block_number = $row['block_number'];
                $lot_number = $row['lot_number'];
                $street = $row['street'];
                $phase = $row['phase'];
                $area_number = $row['area_number'];
                $city = $row['city'];
                $zip_code = $row['zip_code'];
                $password = $row['password'];
                $document_photo = $row['document_photo'];
                $creation_date = date("Y-m-d");
                $update_date = date("Y-m-d");

                // Simplified SQL insert
                $sql_insert_resident = "INSERT INTO registration_resident (greenwoods_id, firstname, lastname, email, house_number, block_number, lot_number, street, phase, area_number, city, zip_code, password, document_photo, status, dateofcreation, dateofupdate) 
                                        VALUES ('$greenwoods_id', '$firstname', '$lastname', '$email', '$house_number', '$block_number', '$lot_number', '$street', '$phase', '$area_number', '$city', '$zip_code', '$password', ?, 'Pending', '$creation_date', '$update_date')";

                // Prepare the statement
                $stmt = mysqli_prepare($conn, $sql_insert_resident);
                mysqli_stmt_bind_param($stmt, "b", $document_photo);

                // Send long data for 'document_photo'
                mysqli_stmt_send_long_data($stmt, 0, $document_photo);

                // Execute the statement
                if (mysqli_stmt_execute($stmt)) {
                    // Delete the temporary resident record
                    $sql_delete_temp = "DELETE FROM temp_resident WHERE email = '$email'";
                    mysqli_query($conn, $sql_delete_temp);

                    $response = [
                        'title' => 'Verification Successful',
                        'message' => 'Wait for your account to be verified by the administrator. You will receive an email confirmation once your account is verified.',
                        'status' => 'success',
                        'redirect' => 'login.php'
                    ];
                } else {
                    $response = [
                        'title' => 'Error',
                        'message' => 'Failed to insert resident data: ' . mysqli_stmt_error($stmt),
                        'status' => 'error'
                    ];
                    error_log('Database error: ' . mysqli_stmt_error($stmt));
                }
                mysqli_stmt_close($stmt);
            }
        } else {
            // OTP is invalid or expired
            error_log("Invalid or expired OTP for email: $email, OTP: $otp");

            $response = [
                'title' => 'Error',
                'message' => 'Invalid or expired OTP.',
                'status' => 'error'
            ];
        }
    } else {
        // Query failed
        error_log("Query failed: " . mysqli_error($conn));

        $response = [
            'title' => 'Error',
            'message' => 'Query failed: ' . mysqli_error($conn),
            'status' => 'error'
        ];
    }

    echo json_encode($response);
    mysqli_close($conn);
}
?>