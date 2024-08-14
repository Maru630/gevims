<?php
session_start();
require 'vendor/autoload.php';
require 'mysql_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $emailExists = false;
    $userType = '';
    $userId = '';

    $stmt = $conn->prepare("SELECT greenwoods_id FROM residents WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $emailExists = true;
        $userType = 'resident';
        $userId = $user['greenwoods_id'];
    }

    if (!$emailExists) {
        $stmt = $conn->prepare("SELECT visitor_id FROM visitor WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $emailExists = true;
            $userType = 'visitor';
            $userId = $user['visitor_id'];
        }
    }

    if ($emailExists) {
        $_SESSION['email'] = $email;
        $_SESSION['user_type'] = $userType;
        $_SESSION['user_id'] = $userId;

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'marstech27@gmail.com';
        $mail->Password = 'dtjrrpesquajjonu';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('marstech27@gmail.com', 'MarsTech');
        $mail->addAddress($email);

        $mail->isHTML(true);

        $mail->Subject = 'Password Reset Request';
        $mail->Body    = 'Click the link to reset your password: <a href="http://localhost/Greenwoods%20Executive%20Village%20Information%20Management%20System/reset_password.php">Reset Password</a>';

        if (!$mail->send()) {
            $response = [
                'title' => 'Error',
                'text' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo,
                'icon' => 'error'
            ];
        } else {
            $response = [
                'title' => 'Success',
                'text' => 'Reset password link has been sent to your email.',
                'icon' => 'success'
            ];
        }
    } else {
        $response = [
            'title' => 'Error',
            'text' => 'Email does not exist in our records.',
            'icon' => 'error'
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="image/png" href="https://i.ibb.co/R95FRzf/greenwoods-logo.png">
   <title>Forgot Password</title>
   <style>
        .error-msg {
            color: red;
            margin-top: 5px;
        }
    </style>
   <link rel="stylesheet" href="css/forms.css">
</head>
<body>
<div class="lheader">
    <a href="index.php">
    <img src="https://i.ibb.co/R95FRzf/greenwoods-logo.png" alt="logo" style="width: 150px; height: auto; ">
</a>
    <h3>Greenwoods Executive Village</h3>
</div>

<div class="form-container">

   <form action="" method="post">
      <h3>Forgot Password</h3>
	  <p>Please enter your email address to reset your password.</p>
	  <br>
 
      <input type="email" name="email" required placeholder="Enter your email">
      
      <input type="submit" name="submit" value="Submit" class="form-btn">
      <p><a href="login.php">Return to Login</a></p>
   </form>
</div>
<div class="copyright">
<strong>© 2024 MarsTech. </strong>All rights reserved.
</div>
<script>
    document.getElementById('forgot-pass-form').addEventListener('submit', function(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we process your request.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        var formData = new FormData(this);

        fetch('', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {
            Swal.close();
            Swal.fire({
                title: data.title,
                text: data.text,
                icon: data.icon,
                confirmButtonText: 'OK'
            }).then(() => {
                if (data.icon === 'success') {
                    window.location.href = 'forgot_pass.php';
                }
            });
        }).catch(error => {
            Swal.close();
            Swal.fire({
                title: 'Error',
                text: 'An unexpected error occurred. Please try again later.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    });
</script>
</body>
</html>
