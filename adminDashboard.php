<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/png" href="./img/greenwoods_logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            background-color: #28a745; /* Brighter green background color */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .header a img {
            width: 150px;
            height: auto;
        }

        .header h3 {
            margin: 0;
            color: #555;
        }

        .back_div {
            text-align: right;
            padding: 10px 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .back_div .btn {
            color: #fff;
            background-color: #dc3545;
        }

        .container {
            padding: 20px;
            width: 100%;
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .display {
            text-align: center;
            width: 100%;
        }

        .display .title {
            font-size: 2rem;
            color: #fff; /* White color for title */
            margin-bottom: 20px;
        }

        .display .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            justify-items: center;
        }

        .display a {
            display: inline-block;
            text-decoration: none;
            color: #333;
            background-color: #fff; /* White background for images */
            width: 180px; /* Adjusted width for the background */
            height: 200px; /* Adjusted height for the background */
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .display a img {
            width: 100px;
            height: 100px;
        }

        .display .desc {
            margin-top: 10px;
            font-size: 1.1rem;
            color: #333; /* Dark color for description */
        }

        .copyright {
            text-align: center;
            padding: 10px 0;
            background-color: #fff;
            width: 100%;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
            position: fixed;
            bottom: 0;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="header">
        <a>
            <img src="img/greenwoods_logo.png" alt="logo">
        </a>
        <h3>Greenwoods Executive Village</h3>
    </div>

    <div class="back_div">
        <button class="btn btn-danger" onclick="logout()">Logout</button>
    </div>

    <div class="container">
        <div class="display">
            <label class="title">ADMIN DASHBOARD</label><br>
            <div class="grid-container">
                <a href="adminApprovalList.php">
                    <img src="img/approve.png" alt="Clickable Image" class="approvalList">
                    <label class="desc">APPROVAL LIST</label>
                </a>

                <a href="">
                    <img src="img/user_management.png" alt="Clickable Image" class="userManagement">
                    <label class="desc">USER MANAGEMENT</label>
                </a>

                <a href="">
                    <img src="img/user_info.png" alt="Clickable Image" class="userInformation">
                    <label class="desc">USER INFORMATION</label>
                </a>

                <a href="">
                    <img src="img/amenity.png" alt="Clickable Image" class="amenity_booking">
                    <label class="desc">AMENITY BOOKING</label>
                </a>

                <a href="adminServiceManagement.php">
                    <img src="img/domestic_request.png" alt="Clickable Image" class="domestic_req">
                    <label class="desc">DOMESTIC REQUEST</label>
                </a>

                <a href="">
                    <img src="img/feedback.png" alt="Clickable Image" class="feedbacks">
                    <label class="desc">FEEDBACKS</label>
                </a>

                <a href="adminAnnouncements.php">
                    <img src="img/Announcements.png" alt="Announcement Image" class="feedbacks"><br>
                    <label class="desc">ANNOUNCEMENTS</label>
                </a>

                <a href="adminEvents.php">
                    <img src="img/Events.png" alt="Event Image" class="feedbacks"><br>
                    <label class="desc">EVENTS</label>
                </a>
            </div>
        </div>
    </div>

    <div class="copyright">
        <strong>© 2024 MarsTech. </strong>All rights reserved.
    </div>

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
