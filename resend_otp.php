<?php
include 'mysql_connect.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
date_default_timezone_set('Asia/Manila');

$response = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = $input['email'];
    $user_type = $input['user_type'];

    if (empty($email) || empty($user_type)) {
        $response = [
            'status' => 'error',
            'message' => 'Invalid request. Email and user type are required.'
        ];
        echo json_encode($response);
        exit;
    }

    $otp = rand(100000, 999999);
    $otp_expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

    if ($user_type == 'resident') {
        $update_query = "UPDATE temp_resident SET otp = '$otp', otp_expiry = '$otp_expiry' WHERE email = '$email'";
    } elseif ($user_type == 'visitor') {
        $update_query = "UPDATE temp_visitor SET otp = '$otp', otp_expiry = '$otp_expiry' WHERE email = '$email'";
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Invalid user type.'
        ];
        echo json_encode($response);
        exit;
    }

    if (mysqli_query($conn, $update_query)) {
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
                'status' => 'success',
                'message' => 'A new OTP has been sent to your email.'
            ];
        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Failed to send OTP email. Mailer Error: ' . $mail->ErrorInfo
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Failed to update OTP. ' . mysqli_error($conn)
        ];
    }
    echo json_encode($response);
}

mysqli_close($conn);
?>
