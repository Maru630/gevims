<?php
session_start();
include 'mysql_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    echo "User not found!";
    exit;
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
$user_data = [];

if ($user_type == 'resident') {
    $sql = "SELECT * FROM residents WHERE greenwoods_id = ?";
} else {
    $sql = "SELECT * FROM visitors WHERE visitor_id = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
} else {
    echo "User not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="./img/greenwoods_logo.png">
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="CSS/homepage.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body data-bs-spy="scroll" data-bs-target=".navbar">

<div id="navbar-placeholder"></div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    fetch('navbar.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('navbar-placeholder').innerHTML = data;
            setupNotificationDropdown();
        });
        
    function setupNotificationDropdown() {
        const notificationButton = document.getElementById('notificationDropdown');
        const notificationMenu = document.getElementById('notificationMenu');
        const notificationCount = document.getElementById('notificationCount');
        const notificationFilter = document.getElementById('notificationFilter');
        const notificationList = document.getElementById('notificationList');
        
        // Placeholder for AJAX request to fetch notifications
        const notifications = [
            { type: 'amenities', message: 'Your reservation for the clubhouse on August 13, 2024 is confirmed.' },
            { type: 'service', message: 'Your plumbing service request is in progress.' },
            { type: 'dues', message: 'Please settle your association dues by July 31, 2024.' }
        ];

        // Function to display notifications
        function displayNotifications(notifications) {
            notificationList.innerHTML = ''; // Clear previous content
            notifications.forEach(notification => {
                const listItem = document.createElement('li');
                listItem.className = 'dropdown-item';
                listItem.innerText = notification.message;
                notificationList.appendChild(listItem);
            });
            if (notifications.length === 0) {
                const noNotificationItem = document.createElement('li');
                noNotificationItem.className = 'dropdown-item text-center';
                noNotificationItem.innerText = 'No new notifications';
                notificationList.appendChild(noNotificationItem);
            } else {
                notificationCount.innerText = notifications.length;
            }
        }

        // Initialize with fetched notifications
        displayNotifications(notifications);

        // Filter notifications
        notificationFilter.addEventListener('change', function() {
            const selectedFilter = this.value;
            const filteredNotifications = selectedFilter === 'all' ? notifications : notifications.filter(notification => notification.type === selectedFilter);
            displayNotifications(filteredNotifications);
        });
    }
});
</script>

<section id="profile" class="section-padding border-top">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center" data-aos="fade-down" data-aos-delay="150">
                <div class="section-title">
                    <h1 class="display-4 fw-semibold">Profile</h1>
                    <div class="line"></div>
                    <p style="font-size: 1.2rem; max-width: 900px; margin: 0 auto;">Manage your profile information</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-down" data-aos-delay="150">
                <div class="form-container theme-shadow p-lg-5 p-4">
                    <form id="profile-form" method="post">
                        <input type="hidden" name="user_type" value="<?= $user_type ?>">
                        <div class="mb-3">
                            <label for="greenwoods_id" class="form-label">Greenwoods ID</label>
                            <input type="text" class="form-control" name="greenwoods_id" required value="<?= htmlspecialchars($user_data['greenwoods_id']) ?>" readonly>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="firstname" class="form-label">First Name</label>
                                <input type="text" class="form-control" name="firstname" required value="<?= htmlspecialchars($user_data['firstname']) ?>" readonly>
                            </div>
                            <div class="col">
                                <label for="lastname" class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="lastname" required value="<?= htmlspecialchars($user_data['lastname']) ?>" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="house_no" class="form-label">House No.</label>
                                <input type="text" class="form-control" name="house_no" required value="<?= htmlspecialchars($user_data['house_number']) ?>" readonly>
                            </div>
                            <div class="col">
                                <label for="lot_no" class="form-label">Lot No.</label>
                                <input type="text" class="form-control" name="lot_no" required value="<?= htmlspecialchars($user_data['lot_number']) ?>" readonly>
                            </div>
                            <div class="col">
                                <label for="block_no" class="form-label">Block No.</label>
                                <input type="text" class="form-control" name="block_no" required value="<?= htmlspecialchars($user_data['block_number']) ?>" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="street" class="form-label">Street</label>
                            <input type="text" class="form-control" name="street" required value="<?= htmlspecialchars($user_data['street']) ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="phase" class="form-label">Phase</label>
                            <input type="text" class="form-control" name="phase" required value="<?= htmlspecialchars($user_data['phase']) ?>" readonly>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="area_no" class="form-label">Area No.</label>
                                <input type="text" class="form-control" name="area_no" required value="<?= htmlspecialchars($user_data['area_number']) ?>" readonly>
                            </div>
                            <div class="col">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" name="city" required value="<?= htmlspecialchars($user_data['city']) ?>" readonly>
                                <select class="form-control d-none" name="city_dropdown" id="city">
                                    <option value="">Select City</option>
                                    <option value="Pasig City" <?= ($user_data['city'] == 'Pasig City') ? 'selected' : '' ?>>Pasig City</option>
                                    <option value="Cainta, Rizal" <?= ($user_data['city'] == 'Cainta, Rizal') ? 'selected' : '' ?>>Cainta, Rizal</option>
                                    <option value="Taytay, Rizal" <?= ($user_data['city'] == 'Taytay, Rizal') ? 'selected' : '' ?>>Taytay, Rizal</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="zip_code" class="form-label">Zip Code</label>
                                <input type="text" class="form-control" name="zip_code" required value="<?= htmlspecialchars($user_data['zip_code']) ?>" readonly>
                                <select class="form-control d-none" name="zip_code_dropdown" id="zip_code">
                                    <option value="">Select Zip Code</option>
                                    <!-- Zip code options will be populated by JavaScript -->
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="email" id="email" required value="<?= htmlspecialchars($user_data['email']) ?>" readonly>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-primary" id="edit-profile-btn">Edit Profile</button>
                            <button type="submit" class="btn btn-success d-none" id="save-profile-btn">Save Profile</button>
                            <button type="button" class="btn btn-secondary d-none" id="cancel-edit-btn">Cancel</button>
                            <button type="button" class="btn btn-secondary" id="back-btn">Back</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="bg-white">
    <div class="footer-top">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-3 col-sm-6">
                    <a href="#"><img src="./img/greenwoods_logo.png" alt=""></a>
                    <div class="line"></div>
                    <p>We look forward to welcoming you to our community soon!</p>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <h5 class="mb-0 text-white">CONTACT</h5>
                    <div class="line"></div>
                    <ul>
                        <li>Tulip St. Phase 2, Greenwoods Executive Village, San Andres, Cainta, Rizal 1900</li>
                        <li>8470-4057 / 7276-1723</li>
                        <li>gevhaioffice.gev2022@gmail.com</li>
                    </ul>
                </div>
                <div class="col-lg-3 col-sm-6">
                </div>
                <div class="col-lg-3 col-sm-6">
                    <h5 class="mb-0 text-white">QUICK LINKS</h5>
                    <div class="line"></div>
                    <ul>
                        <li><a href="bookAmenities.php">Amenities</a></li>
                        <li><a href="bookService.php">Services</a></li>
                        <li><a href="payments.php">Payments</a></li>
                        <li><a href="announcement.php">Bulletin</a></li>
                        <li><a href="inquiries.php">Make an Inquiry</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <strong>© 2024 MarsTech. </strong>All rights reserved.
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script src="./js/main.js"></script>
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

document.addEventListener('DOMContentLoaded', function () {
    AOS.init();
    
    const form = document.getElementById('profile-form');
    const editBtn = document.getElementById('edit-profile-btn');
    const saveBtn = document.getElementById('save-profile-btn');
    const cancelBtn = document.getElementById('cancel-edit-btn');
    const backBtn = document.getElementById('back-btn');
    const cityInput = document.querySelector('input[name="city"]');
    const cityDropdown = document.querySelector('select[name="city_dropdown"]');
    const zipCodeInput = document.querySelector('input[name="zip_code"]');
    const zipCodeDropdown = document.querySelector('select[name="zip_code_dropdown"]');

    const cityZipCodes = {
        "Pasig City": ["1600", "1601", "1602", "1603", "1604", "1605", "1606", "1607", "1608", "1609", "1610", "1611", "1612"],
        "Cainta, Rizal": ["1900"],
        "Taytay, Rizal": ["1920"]
    };

    function updateZipCodes(city) {
        const zipCodes = cityZipCodes[city] || [];
        zipCodeDropdown.innerHTML = '<option value="">Select Zip Code</option>';
        zipCodes.forEach(zip => {
            const option = document.createElement('option');
            option.value = zip;
            option.textContent = zip;
            zipCodeDropdown.appendChild(option);
        });
        zipCodeDropdown.value = zipCodeInput.value;
    }

    editBtn.addEventListener('click', function () {
        form.querySelectorAll('input').forEach(input => {
            input.removeAttribute('readonly');
        });
        cityInput.classList.add('d-none');
        cityDropdown.classList.remove('d-none');
        zipCodeInput.classList.add('d-none');
        zipCodeDropdown.classList.remove('d-none');
        editBtn.style.display = 'none';
        saveBtn.classList.remove('d-none');
        cancelBtn.classList.remove('d-none');
    });

    cancelBtn.addEventListener('click', function () {
        form.querySelectorAll('input').forEach(input => {
            input.setAttribute('readonly', true);
        });
        cityInput.classList.remove('d-none');
        cityDropdown.classList.add('d-none');
        zipCodeInput.classList.remove('d-none');
        zipCodeDropdown.classList.add('d-none');
        editBtn.style.display = 'inline-block';
        saveBtn.classList.add('d-none');
        cancelBtn.classList.add('d-none');
        location.reload();
    });

    backBtn.addEventListener('click', function () {
        window.history.back();
    });

    cityDropdown.addEventListener('change', function () {
        updateZipCodes(this.value);
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        Swal.fire({
            title: 'Saving...',
            text: 'Please wait while we save your changes.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const formData = new FormData(form);

        fetch('process_profile_update.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();
            Swal.fire({
                title: data.title,
                text: data.message,
                icon: data.status,
                confirmButtonText: 'OK'
            }).then(() => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            });
        })
        .catch(error => {
            Swal.close();
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'An unexpected error occurred. Please try again later.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    });

    // Initialize zip code dropdown based on current city
    updateZipCodes(cityDropdown.value);
});
</script>

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
