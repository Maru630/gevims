<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://i.ibb.co/R95FRzf/greenwoods-logo.png">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="CSS/forms.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" type="image/png" href="./img/greenwoods_logo.png">
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
        .otp-inputs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            gap: 10px;
        }
        .otp-inputs input {
            width: 40px;
            height: 40px;
            text-align: center;
            font-size: 24px;
        }
        .timer, .resend {
            margin-top: 20px;
        }
        .resend {
            color: blue;
            cursor: pointer;
            text-decoration: underline;
        }
        .resend.disabled {
            color: grey;
            cursor: not-allowed;
            text-decoration: none;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        .success {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="lheader">
    <a href="homepage.php">
    <img src="./img/greenwoods_logo.png" alt="logo" style="width: 150px; height: auto; ">
</a>
    <h3>Greenwoods Executive Village</h3>
</div>

<div class="form-container">
    <form id="otp-form">
        <h3>OTP Verification</h3>
        <p>Enter the 6-digit code sent to your email.</p>
        <?php
        $email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
        $user_type = isset($_GET['user_type']) ? htmlspecialchars($_GET['user_type']) : '';
        ?>
        <input type="hidden" name="email" value="<?php echo $email; ?>">
        <input type="hidden" name="user_type" value="<?php echo $user_type; ?>">
        <div class="otp-inputs">
            <input type="text" maxlength="1" class="otp" oninput="validateAndMoveToNext(this, 'otp2')">
            <input type="text" maxlength="1" id="otp2" class="otp" oninput="validateAndMoveToNext(this, 'otp3')">
            <input type="text" maxlength="1" id="otp3" class="otp" oninput="validateAndMoveToNext(this, 'otp4')">
            <input type="text" maxlength="1" id="otp4" class="otp" oninput="validateAndMoveToNext(this, 'otp5')">
            <input type="text" maxlength="1" id="otp5" class="otp" oninput="validateAndMoveToNext(this, 'otp6')">
            <input type="text" maxlength="1" id="otp6" class="otp">
        </div>
        <input type="submit" value="Verify OTP" class="form-btn">
        <div id="message" class="error"></div>
        <div class="timer" id="timer">01:00</div>
        <div class="resend disabled" id="resend" onclick="resendOTP()">Resend OTP</div>
    </form>
</div>

<div class="copyright">
    <strong>© 2024 MarsTech. </strong>All rights reserved.
</div>

<script>
    let countdown;
    let timerDisplay = document.getElementById('timer');
    let resendButton = document.getElementById('resend');

    function startTimer(duration) {
        let timer = duration, minutes, seconds;
        countdown = setInterval(() => {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            timerDisplay.textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                clearInterval(countdown);
                resendButton.classList.remove('disabled');
            }
        }, 1000);
    }

    function validateAndMoveToNext(current, nextFieldId) {
        const value = current.value.replace(/[^0-9]/g, '');
        current.value = value;
        if (value.length === 1) {
            document.getElementById(nextFieldId).focus();
        }
    }

    function clearOTPFields() {
        const otpInputs = document.querySelectorAll('.otp');
        otpInputs.forEach(input => {
            input.value = '';
        });
        document.querySelector('.otp').focus();
    }

    $('#otp-form').on('submit', function(event) {
        event.preventDefault();
        const otpInputs = document.querySelectorAll('.otp');
        let otp = '';
        otpInputs.forEach(input => {
            otp += input.value;
        });

        const formData = new FormData(this);
        formData.append('otp', otp);

        $.ajax({
            url: 'process-otp.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                try {
                    var res = JSON.parse(response);
                    Swal.fire({
                        title: res.title,
                        text: res.message,
                        icon: res.status,
                        confirmButtonText: 'OK'
                    }).then(function() {
                        if (res.redirect) {
                            window.location.href = res.redirect;
                        } else {
                            clearOTPFields();
                        }
                    });
                } catch (e) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Invalid JSON response: ' + response,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        clearOTPFields();
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    title: 'Error',
                    text: 'AJAX request failed: ' + textStatus + ' - ' + errorThrown,
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(function() {
                    clearOTPFields();
                });
            }
        });
    });

    function resendOTP() {
        if (resendButton.classList.contains('disabled')) return;

        Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we resend your OTP.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        resendButton.classList.add('disabled');
        clearInterval(countdown);
        startTimer(60);

        const email = $('input[name="email"]').val();
        const user_type = $('input[name="user_type"]').val();

        fetch('resend_otp.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email: email, user_type: user_type })
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();
            Swal.fire({
                title: data.status === 'success' ? 'Success' : 'Error',
                text: data.message,
                icon: data.status,
                confirmButtonText: 'OK'
            });
        })
        .catch(error => {
            Swal.close();
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Failed to resend OTP. Please try again later.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    }

    startTimer(60);
</script>
</body>
</html>
