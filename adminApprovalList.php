<?php
include 'mysql_connect.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

function fetchPendingRequest($conn) {
    $sql = "SELECT * FROM registration_resident WHERE status = 'Pending'";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$pendingRequests = fetchPendingRequest($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="./img/greenwoods_logo.png">
    <title>Admin Approval List - Greenwoods Executive Village</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="CSS/homepage.css">
    <style>
        .logout-button, .back-button {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            position: absolute;
            top: 10px;
            z-index: 1000;
        }
        .logout-button {
            right: 10px;
        }
        .logout-button:hover, .back-button:hover {
            background-color: #d32f2f;
        }
        .back-button {
            left: 10px;
        }
        .details-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 14px;
            border-radius: 3px;
        }
        .details-button:hover {
            background-color: #45a049;
        }
        .container {
            margin-top: 100px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body data-bs-spy="scroll" data-bs-target=".navbar">

<form action="" method="post">
    <input type="hidden" name="logout" value="1">
    <button type="submit" class="logout-button">Logout</button>
</form>

<form action="adminDashboard.php" method="get">
    <button type="submit" class="back-button">Back</button>
</form>

<div class="container text-center">
    <img src="./img/greenwoods_logo.png" alt="Greenwoods Logo" style="width: 100px; margin-bottom: 20px;">
    <h1 class="display-4 fw-semibold">Admin Approval List</h1>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>LIST OF USERS</th>
                    <th>STATUS</th>
                    <th>INFO</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingRequests as $request): ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['firstname']); ?> <?php echo htmlspecialchars($request['lastname']); ?></td>
                    <td><?php echo htmlspecialchars($request['status']); ?></td>
                    <td>
                        <form action="adminApprovalDetails.php" method="get" style="margin: 0;">
                            <input type="hidden" name="id" value="<?php echo $request['greenwoods_id']; ?>">
                            <button type="submit" class="details-button">DETAILS</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function logout() {
    Swal.fire({
        title: 'Logging Out',
        text: 'Your account is being logged out...',
        icon: 'info',
        timer: 2000,
        showConfirmButton: false,
        willClose: () => {
            window.location.href = 'logout.php';
        }
    });
}
</script>
</body>
</html>
