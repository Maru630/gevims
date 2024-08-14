<?php
require 'vendor/autoload.php'; 
include 'mysql_connect.php'; 

if (!isset($_GET['id'])) {
    die('ID not provided');
}

$registrationId = intval($_GET['id']);
$sql = "SELECT * FROM registration_resident WHERE greenwoods_id = $registrationId";
$result = mysqli_query($conn, $sql);
$registrationDetails = mysqli_fetch_assoc($result);

if (!$registrationDetails) {
    die('Request not found');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];

    // Escape fields
    $registrationDetails = array_map(function($field) use ($conn) {
        return mysqli_real_escape_string($conn, $field);
    }, $registrationDetails);

    if ($status == 'approved') {
        mysqli_begin_transaction($conn);

        $firstname = $registrationDetails['firstname'];
		$lastname = $registrationDetails['lastname'];
        $email = $registrationDetails['email'];
        $password = $registrationDetails['password'];
        $document_photo = $registrationDetails['document_photo'];

        $house_number = $registrationDetails['house_number'];
        $lot_number = $registrationDetails['lot_number'];
        $block_number = $registrationDetails['block_number'];
        $street = $registrationDetails['street'];
        $phase = $registrationDetails['phase'];
        $area_number = $registrationDetails['area_number'];
        $city = $registrationDetails['city'];
        $zip_code = $registrationDetails['zip_code'];
		$creation_date = date("Y-m-d");
		$update_date = date("Y-m-d");

        $sqlInsertResident = "INSERT INTO residents (greenwoods_id, firstname, lastname, email, house_number, block_number, lot_number, street, phase, area_number, city, zip_code, password, document_photo, dateofcreation, dateofupdate) 
                              VALUES ('$registrationId', '$firstname', '$lastname', '$email', '$house_number', '$block_number', '$lot_number', '$street', '$phase', '$area_number', '$city', '$zip_code', '$password', '$document_photo', '$creation_date', '$update_date')";
        if (!mysqli_query($conn, $sqlInsertResident)) {
            mysqli_rollback($conn);
            die('Error inserting into residents table: ' . mysqli_error($conn));
        }

        $sqlUpdateStatus = "UPDATE registration_resident SET status = '$status' WHERE greenwoods_id = $registrationId";
        if (!mysqli_query($conn, $sqlUpdateStatus)) {
            mysqli_rollback($conn);
            die('Error updating registration status: ' . mysqli_error($conn));
        }

        sendEmail($email, "Registration Approved", "Your registration has been approved. You can now log in.");

        mysqli_commit($conn);
    } elseif ($status == 'denied') {
        $sqlUpdateStatus = "UPDATE registration_resident SET status = '$status' WHERE greenwoods_id = $registrationId";
        if (!mysqli_query($conn, $sqlUpdateStatus)) {
            die('Error updating registration status: ' . mysqli_error($conn));
        }

        sendEmail($registrationDetails['email'], "Registration Denied", "Your registration has been denied. Please try again or upload another document.");

        $sqlDeleteRequest = "DELETE FROM registration_resident WHERE greenwoods_id = $registrationId";
        if (!mysqli_query($conn, $sqlDeleteRequest)) {
            die('Error deleting registration request: ' . mysqli_error($conn));
        }
    }

    header('Location: adminApprovalList.php');
    exit;
}

function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer\PHPMailer\PHPMailer();

    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'marstech27@gmail.com';
    $mail->Password = 'dtjrrpesquajjonu';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('marstech27@gmail.com', 'MarsTech');
    $mail->addAddress($to);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = nl2br($message);

    if (!$mail->send()) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    } else {
        echo "Message has been sent successfully.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Approval Details</title>
    <link rel="stylesheet" href="adminApprovalDetails.css">
</head>
<body>
    <div class="container">
        <div class="display">
            <h2>GREENWOODS ID: <?php echo htmlspecialchars($registrationDetails['greenwoods_id']); ?></h2>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($registrationDetails['document_photo']); ?>" alt="Document Photo" class="uploaded_photo">
            <div class="info">
                <p><strong>FIRST NAME:</strong> <?php echo htmlspecialchars($registrationDetails['firstname']); ?></p>
				<p><strong>LAST NAME:</strong> <?php echo htmlspecialchars($registrationDetails['lastname']); ?></p>
                <p><strong>EMAIL:</strong> <?php echo htmlspecialchars($registrationDetails['email']); ?></p>
                <p><strong>DATE OF CREATION:</strong> <?php echo htmlspecialchars($registrationDetails['dateofcreation']); ?></p>
                <p><strong>HOUSE #:</strong> <?php echo htmlspecialchars($registrationDetails['house_number']); ?></p>
                <p><strong>LOT #:</strong> <?php echo htmlspecialchars($registrationDetails['lot_number']); ?></p>
                <p><strong>BLK #:</strong> <?php echo htmlspecialchars($registrationDetails['block_number']); ?></p>
                <p><strong>STREET:</strong> <?php echo htmlspecialchars($registrationDetails['street']); ?></p>
                <p><strong>PHASE:</strong> <?php echo htmlspecialchars($registrationDetails['phase']); ?></p>
                <p><strong>AREA #:</strong> <?php echo htmlspecialchars($registrationDetails['area_number']); ?></p>
                <p><strong>CITY:</strong> <?php echo htmlspecialchars($registrationDetails['city']); ?></p>
                <p><strong>ZIP CODE:</strong> <?php echo htmlspecialchars($registrationDetails['zip_code']); ?></p>
            </div>
            <div class="button-container">
                <form method="post" style="display: inline;">
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="btnApprove">APPROVE</button>
                </form>
                <form method="post" style="display: inline;">
                    <input type="hidden" name="status" value="denied">
                    <button type="submit" class="btnDecline">DECLINE</button>
                </form>
                <a href="adminApprovalList.php" class="btnBack">BACK</a>
            </div>
        </div>
    </div>
</body>
</html>
