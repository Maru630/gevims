<?php
include 'mysql_connect.php';

header('Content-Type: application/json');

session_start(); // Start the session at the beginning

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    function check_user_credentials($conn, $table, $email, $password) {
        $sql = "SELECT * FROM $table WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                echo json_encode([
                    'status' => 'error',
                    'title' => 'Incorrect Password',
                    'message' => 'The password you entered is incorrect.'
                ]);
                exit;
            }
        }
        return null;
    }

    $user = check_user_credentials($conn, 'residents', $email, $password);
    if ($user) {
        $sql_status = "SELECT status FROM registration_resident WHERE greenwoods_id = ?";
        $stmt_status = $conn->prepare($sql_status);
        $stmt_status->bind_param("s", $user['greenwoods_id']);
        $stmt_status->execute();
        $result_status = $stmt_status->get_result();

        if ($result_status->num_rows > 0) {
            $status_row = $result_status->fetch_assoc();
            if (strcasecmp($status_row['status'], 'Approved') !== 0) {
                echo json_encode([
                    'status' => 'error',
                    'title' => 'Pending Approval',
                    'message' => 'Your registration is still pending approval.'
                ]);
                exit;
            }
        }

        // Set session variables after successful verification
        $_SESSION['user_id'] = $user['greenwoods_id'];
        $_SESSION['user_type'] = 'resident';
        echo json_encode([
            'status' => 'success',
            'title' => 'Success',
            'message' => 'Logged in successfully!',
            'redirect' => 'homepage.php'
        ]);
        exit;
    }

    $user = check_user_credentials($conn, 'visitor', $email, $password);
    if ($user) {
        // Set session variables after successful verification
        $_SESSION['user_id'] = $user['visitor_id'];
        $_SESSION['user_type'] = 'visitor';
        echo json_encode([
            'status' => 'success',
            'title' => 'Success',
            'message' => 'Logged in successfully!',
            'redirect' => 'homepage_vis.php'
        ]);
        exit;
    }

    if ($email === 'junefgwoods@gmail.com' && $password === 'gwoodsadmin') {
        // Set session variables after successful verification
        $_SESSION['user_id'] = 'admin';
        $_SESSION['user_type'] = 'admin';
        echo json_encode([
            'status' => 'success',
            'title' => 'Success',
            'message' => 'Logged in successfully!',
            'redirect' => 'adminDashboard.php'
        ]);
        exit();
    }

    echo json_encode([
        'status' => 'error',
        'title' => 'No User Found',
        'message' => 'No user found with that email.'
    ]);
    exit;
}
?>
