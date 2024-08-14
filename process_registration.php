<?php
include 'mysql_connect.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
date_default_timezone_set('Asia/Manila');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_type = $_POST['user_type'];
    $response = [];

    if ($user_type == 'resident' || $user_type == 'visitor') {
        // Common fields and email check for both resident and visitor
        $email = $_POST['email'];
        $common_email_check_query = "SELECT email FROM residents WHERE email = '$email' UNION SELECT email FROM visitor WHERE email = '$email'";
        $common_email_check_result = mysqli_query($conn, $common_email_check_query);
        if (!$common_email_check_result) {
            error_log("Email check query failed: " . mysqli_error($conn));
            $response = [
                'title' => 'Error',
                'message' => 'An unexpected error occurred during email validation. Please try again later.',
                'status' => 'error'
            ];
            echo json_encode($response);
            exit;
        }
        if (mysqli_num_rows($common_email_check_result) > 0) {
            $response = [
                'title' => 'Error',
                'message' => 'The email address is already registered. Please use a different email address.',
                'status' => 'error'
            ];
            echo json_encode($response);
            exit;
        }
    }

    if ($user_type == 'resident') {
        // Resident registration process with OTP
        $required_fields = ['greenwoods_id', 'firstname', 'lastname', 'email', 'house_no', 'block_no', 'lot_no', 'street', 'phase', 'area_no', 'city', 'zip_code', 'password'];
        $missing_fields = [];

        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $missing_fields[] = $field;
            }
        }

        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] != UPLOAD_ERR_OK) {
            $missing_fields[] = 'photo';
        }

        if (!empty($missing_fields)) {
            $response = [
                'title' => 'Error',
                'message' => 'The following fields are missing: ' . implode(', ', $missing_fields),
                'status' => 'error'
            ];
            echo json_encode($response);
            exit;
        }

        $email = $_POST['email'];
        $password = password_hash($_POST['confirm_password'], PASSWORD_BCRYPT);
        $creation_date = date("Y-m-d");
        $otp_expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

        $resident_id = $_POST['greenwoods_id'];

        $email_check_query = "SELECT email FROM residents WHERE email = '$email'";
        $email_check_result = mysqli_query($conn, $email_check_query);
        if (!$email_check_result) {
            error_log("Email check query failed: " . mysqli_error($conn));
            $response = [
                'title' => 'Error',
                'message' => 'An unexpected error occurred during email validation. Please try again later.',
                'status' => 'error'
            ];
            echo json_encode($response);
            exit;
        }
        if (mysqli_num_rows($email_check_result) > 0) {
            $response = [
                'title' => 'Error',
                'message' => 'The email address is already registered.',
                'status' => 'error'
            ];
            echo json_encode($response);
            exit;
        }

        $id_check_query = "SELECT greenwoods_id FROM residents WHERE greenwoods_id = '$resident_id'";
        $id_check_result = mysqli_query($conn, $id_check_query);
        if (!$id_check_result) {
            error_log("ID check query failed: " . mysqli_error($conn));
            $response = [
                'title' => 'Error',
                'message' => 'An unexpected error occurred during ID validation. Please try again later.',
                'status' => 'error'
            ];
            echo json_encode($response);
            exit;
        }
        if (mysqli_num_rows($id_check_result) > 0) {
            $response = [
                'title' => 'Error',
                'message' => 'The Greenwoods ID is already registered.',
                'status' => 'error'
            ];
            echo json_encode($response);
            exit;
        }

        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $house_number = $_POST['house_no'];
        $block_number = $_POST['block_no'];
        $lot_number = $_POST['lot_no'];
        $street = $_POST['street'];
        $phase = $_POST['phase'];
        $area_number = $_POST['area_no'];
        $city = $_POST['city'];
        $zip_code = $_POST['zip_code'];

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
            $document_photo = addslashes(file_get_contents($_FILES['photo']['tmp_name']));
        } else {
            $response = [
                'title' => 'Error',
                'message' => 'No photo uploaded or upload error.',
                'status' => 'error'
            ];
            echo json_encode($response);
            exit;
        }

        $otp = rand(100000, 999999);

        // Insert into temp_resident table
        $sql_resident = "INSERT INTO temp_resident (greenwoods_id, firstname, lastname, email, house_number, block_number, lot_number, street, phase, area_number, city, zip_code, password, document_photo, otp, otp_expiry, dateofcreation) 
                         VALUES ('$resident_id', '$firstname', '$lastname', '$email', '$house_number', '$block_number', '$lot_number', '$street', '$phase', '$area_number', '$city', '$zip_code', '$password', '$document_photo', '$otp', '$otp_expiry', '$creation_date')";

        if (mysqli_query($conn, $sql_resident)) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'marstech27@gmail.com';
                $mail->Password = 'dtjrrpesquajjonu';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('no-reply@greenwoods.com', 'Greenwoods');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Code';
                $mail->Body = "Your OTP code is $otp. It is valid for 5 minutes.";

                $mail->send();
                $response = [
                    'title' => 'Registration Successful',
                    'message' => 'An OTP has been sent to your email. Please verify your account.',
                    'status' => 'success',
                    'redirect' => 'verify-otp.php?email=' . urlencode($email) . '&user_type=resident'
                ];
                echo json_encode($response);
                exit;
            } catch (Exception $e) {
                error_log('Mailer Error: ' . $mail->ErrorInfo);
                $response = [
                    'title' => 'Error',
                    'message' => 'Failed to send OTP email. Mailer Error: ' . $mail->ErrorInfo,
                    'status' => 'error'
                ];
                echo json_encode($response);
                exit;
            }
        } else {
            error_log("Insert query failed: " . mysqli_error($conn));
            $response = [
                'title' => 'Error',
                'message' => mysqli_error($conn),
                'status' => 'error'
            ];
            echo json_encode($response);
            exit;
        }

    } elseif ($user_type == 'visitor') {
        // Visitor registration process with OTP
        $required_fields = ['firstname', 'lastname', 'email', 'address', 'password'];
        $missing_fields = [];

        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $missing_fields[] = $field;
            }
        }

        if (!empty($missing_fields)) {
            $response = [
                'title' => 'Error',
                'message' => 'The following fields are missing: ' . implode(', ', $missing_fields),
                'status' => 'error'
            ];
            echo json_encode($response);
            exit;
        }

        $email = $_POST['email'];
        $password = password_hash($_POST['confirm_password'], PASSWORD_BCRYPT);
        $creation_date = date("Y-m-d H:i:s");
        $otp_expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $address = $_POST['address'];

        $otp = rand(100000, 999999);

        $sql_visitor = "INSERT INTO temp_visitor (firstname, lastname, email, address, password, otp, otp_expiry, dateofcreation) 
                        VALUES ('$firstname', '$lastname', '$email', '$address', '$password', '$otp', '$otp_expiry', '$creation_date')";

        if (mysqli_query($conn, $sql_visitor)) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'marstech27@gmail.com';
                $mail->Password = 'dtjrrpesquajjonu';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('no-reply@greenwoods.com', 'Greenwoods');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Code';
                $mail->Body = "Your OTP code is $otp. It is valid for 5 minutes.";

                $mail->send();
                $response = [
                    'title' => 'Registration Successful',
                    'message' => 'An OTP has been sent to your email. Please verify your account.',
                    'status' => 'success',
                    'redirect' => 'verify-otp.php?email=' . urlencode($email) . '&user_type=visitor'
                ];
                echo json_encode($response);
                exit;
            } catch (Exception $e) {
                error_log('Mailer Error: ' . $mail->ErrorInfo);
                $response = [
                    'title' => 'Error',
                    'message' => 'Failed to send OTP email. Mailer Error: ' . $mail->ErrorInfo,
                    'status' => 'error'
                ];
                echo json_encode($response);
                exit;
            }
        } else {
            error_log("Insert query failed: " . mysqli_error($conn));
            $response = [
                'title' => 'Error',
                'message' => mysqli_error($conn),
                'status' => 'error'
            ];
            echo json_encode($response);
            exit;
        }
    }
    mysqli_close($conn);
}
?>
