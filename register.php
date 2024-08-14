<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="./img/greenwoods_logo.png">
    <title>Register</title>
    <style>
        .input-pass {
            position: relative;
            margin-bottom: 15px;
        }

        .eye-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            width: 1.5rem;
            display: flex;
            align-items: center;
        }

        .eye-icon img {
            width: 100%;
            height: auto;
        }

        .error-msg {
            color: red;
            margin-top: 5px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .modal-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .modal-body {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .modal-footer {
            display: flex;
            justify-content: center;
        }

        .btn {
            background-color: #428a03;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #45a049;
        }
    </style>
    <link rel="stylesheet" href="css/forms.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="lheader">
    <a href="homepage.php">
        <img src="./img/greenwoods_logo.png" alt="logo" style="width: 150px; height: auto;">
    </a>
    <h3>Greenwoods Executive Village</h3>
</div>
<div class="form-selection">
    <label for="user-type">Register As:</label>
    <select id="user-type" onchange="showForm()">
        <option value="resident">Resident</option>
        <option value="visitor">Visitor</option>
    </select>
</div>
<div id="resident-form" class="form-container" style="display: flex;">
    <form action="" id="signup-resident" method="post" onsubmit="return validatePassword('password', 'confirm_password', 'password-validation-message');">
        <h3>Resident Registration Form</h3>
        <input type="text" name="greenwoods_id" required placeholder="Greenwoods ID">
        <div class="form-row">
            <input type="text" name="firstname" required placeholder="First Name">
            <input type="text" name="lastname" required placeholder="Last Name">
        </div>
        <div class="form-row1">
            <input type="text" name="house_no" required placeholder="House No.">
            <input type="text" name="lot_no" required placeholder="Lot No.">
            <input type="text" name="block_no" required placeholder="Block No.">
        </div>
        <input type="text" name="street" required placeholder="Street">
        <input type="text" name="phase" required placeholder="Phase">
        <div class="form-row1">
            <input type="text" name="area_no" required placeholder="Area No.">
            <select name="city" id="city" onchange="updateZipCodes()" required>
                <option value="">Select City</option>
                <option value="Pasig City">Pasig City</option>
                <option value="Cainta, Rizal">Cainta, Rizal</option>
                <option value="Taytay, Rizal">Taytay, Rizal</option>
            </select>
            <select name="zip_code" id="zip_code" required>
                <option value="">Select Zip Code</option>
            </select>
        </div>
        <input type="email" name="email" required placeholder="Email Address">
        <div class="input-pass">
            <input type="password" name="password" id="password" required placeholder="Password">
            <div class="eye-icon" onclick="togglePasswordVisibility('password', 'eye-icon-img-resident')">
                <img id="eye-icon-img-resident" src="img/eye-close.png" alt="Eye Icon">
            </div>
        </div>
        <div class="input-pass">
            <input type="password" name="confirm_password" id="confirm_password" required placeholder="Confirm Password">
            <div class="eye-icon" onclick="togglePasswordVisibility('confirm_password', 'eye-icon-img-confirm')">
                <img id="eye-icon-img-confirm" src="img/eye-close.png" alt="Eye Icon">
            </div>
        </div>
        <div id="password-validation-message"></div>
        <label for="photo">Upload Membership Document:</label>
        <input type="file" name="photo" required placeholder="Upload Photo">
        <input type="submit" name="submit" value="Register Now" class="form-btn">
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>
</div>

<div id="visitor-form" class="form-container">
    <form action="" id="signup-visitor" method="post" onsubmit="return validatePassword('password-visitor', 'confirm_password-visitor', 'password-validation-message');">
        <h3>Visitor Registration Form</h3>
        <div class="form-row">
            <input type="text" name="firstname" required placeholder="First Name">
            <input type="text" name="lastname" required placeholder="Last Name">
        </div>
        <input type="email" name="email" required placeholder="Email Address">
        <input type="text" name="address" required placeholder="Address">
        <div class="input-pass">
            <input type="password" name="password" id="password-visitor" required placeholder="Password">
            <div class="eye-icon" onclick="togglePasswordVisibility('password-visitor', 'eye-icon-img-visitor')">
                <img id="eye-icon-img-visitor" src="img/eye-close.png" alt="Eye Icon">
            </div>
        </div>
        <div class="input-pass">
            <input type="password" name="confirm_password" id="confirm_password-visitor" required placeholder="Confirm Password">
            <div class="eye-icon" onclick="togglePasswordVisibility('confirm_password-visitor', 'eye-icon-img-confirm-visitor')">
                <img id="eye-icon-img-confirm-visitor" src="img/eye-close.png" alt="Eye Icon">
            </div>
        </div>
        <div id="password-validation-message"></div>
        <input type="submit" name="submit" value="Register Now" class="form-btn">
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>
</div>

<div id="successModal" class="modal">
    <div class="modal-content">
        <img src="img/checkmark.png" alt="Checkmark Image" style="width: 100px; height: auto; margin-bottom: 10px;">
        <div class="modal-header">Success!</div>
        <div class="modal-body">Your account has been created successfully!</div>
        <div class="modal-footer">
            <button class="btn" onclick="redirectToLogin()">OK</button>
        </div>
    </div>
</div>
<div class="copyright">
    <strong>© 2024 MarsTech. </strong>All rights reserved.
</div>
<script>
    function showForm() {
        var userType = document.getElementById('user-type').value;
        var residentForm = document.getElementById('resident-form');
        var visitorForm = document.getElementById('visitor-form');
        if (userType === 'resident') {
            residentForm.style.display = 'flex';
            visitorForm.style.display = 'none';
        } else if (userType === 'visitor') {
            residentForm.style.display = 'none';
            visitorForm.style.display = 'flex';
        } else {
            residentForm.style.display = 'none';
            visitorForm.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        showForm();
    });

    function updateZipCodes() {
        const cityZipCodes = {
            "Pasig City": ["1600", "1601", "1602", "1603", "1604", "1605", "1606", "1607", "1608", "1609", "1610", "1611", "1612"],
            "Cainta, Rizal": ["1900"],
            "Taytay, Rizal": ["1920"]
        };

        const citySelect = document.getElementById('city');
        const zipCodeSelect = document.getElementById('zip_code');
        const selectedCity = citySelect.value;
        const zipCodes = cityZipCodes[selectedCity] || [];

        zipCodeSelect.innerHTML = '<option value="">Select Zip Code</option>';

        zipCodes.forEach(zip => {
            const option = document.createElement('option');
            option.value = zip;
            option.textContent = zip;
            zipCodeSelect.appendChild(option);
        });
    }

    function togglePasswordVisibility(passwordFieldId, iconId) {
        const passwordField = document.getElementById(passwordFieldId);
        const icon = document.getElementById(iconId);
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        icon.src = type === 'password' ? 'img/eye-close.png' : 'img/eye-open.png';
    }

    function validatePassword(passwordFieldId, confirmPasswordFieldId, validationMessageId) {
        let password = document.getElementById(passwordFieldId).value;
        let confirmPassword = document.getElementById(confirmPasswordFieldId).value;
        let validationMessage = document.getElementById(validationMessageId);

        if (password !== confirmPassword) {
            validationMessage.innerHTML = '<span style="color: red;">Passwords do not match.</span>';
            return false;
        }

        let regx = /(?=.*[0-9])(?=.*[!@#$%^&*_])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9!@#$%^&*_]{8,}$/;
        if (!regx.test(password)) {
            validationMessage.innerHTML = '<span style="color: red;">Password must contain at least 8 characters, including one numeric digit and one special character.</span>';
            return false;
        }

        validationMessage.innerHTML = '';
        return true;
    }

    function showModal() {
        const modal = document.getElementById("successModal");
        modal.style.display = "block";
    }

    function redirectToLogin() {
        window.location.href = 'login.php';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('#signup-resident, #signup-visitor');
        forms.forEach(form => {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                if (!validatePassword(form.querySelector('[name="password"]').id, form.querySelector('[name="confirm_password"]').id, 'password-validation-message')) {
                    return;
                }

                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we process your registration.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                var formData = new FormData(this);
                formData.append('user_type', this.id === 'signup-resident' ? 'resident' : 'visitor');

                $.ajax({
                    url: 'process_registration.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        Swal.close();
                        console.log(response); // Log the response for debugging
                        try {
                            var res = JSON.parse(response);
                            if (res.status === 'error' && res.message.includes('email address is already registered')) {
                                form.querySelector('[name="email"]').value = '';
                            }
                            Swal.fire({
                                title: res.title,
                                text: res.message,
                                icon: res.status,
                                confirmButtonText: 'OK'
                            }).then(function () {
                                if (res.redirect) {
                                    window.location.href = res.redirect;
                                }
                            });
                        } catch (error) {
                            console.error('Error parsing JSON response:', error);
                            console.log('Response:', response); // Additional logging
                            Swal.fire({
                                title: 'Error',
                                text: 'An unexpected error occurred. Please try again later.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.close();
                        console.error('AJAX Error:', error);
                        console.log('XHR:', xhr); // Additional logging
                        console.log('Status:', status); // Additional logging
                        Swal.fire({
                            title: 'Error',
                            text: 'An unexpected error occurred. Please try again later.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    });

    document.getElementById('password').addEventListener('input', function () {
        document.getElementById('password-validation-message').innerHTML = '';
    });

    document.getElementById('confirm_password').addEventListener('input', function () {
        document.getElementById('password-validation-message').innerHTML = '';
    });

    document.getElementById('password-visitor').addEventListener('input', function () {
        document.getElementById('password-validation-message').innerHTML = '';
    });

    document.getElementById('confirm_password-visitor').addEventListener('input', function () {
        document.getElementById('password-validation-message').innerHTML = '';
    });
</script>
</body>
</html>
