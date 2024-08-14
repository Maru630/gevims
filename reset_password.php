<?php
session_start();
require 'mysql_connect.php';

if (isset($_POST['reset_password'])) {
    $password = $_POST["new_password"];
    $validationErrors = validatePassword($password);
    if (empty($validationErrors)) {
        if ($_POST['new_password'] === $_POST['confirm_new_password']) {
            $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
            $email = $_SESSION['email'];
            $userType = $_SESSION['user_type'];
            $userId = $_SESSION['user_id'];

            if ($userType == 'resident') {
                $stmt1 = $conn->prepare("UPDATE residents SET password = ? WHERE email = ?");
                $stmt1->bind_param('ss', $new_password, $email);
                $stmt1->execute();

                $stmt2 = $conn->prepare("UPDATE registration_resident SET password = ? WHERE greenwoods_id = ?");
                $stmt2->bind_param('ss', $new_password, $userId);
                $stmt2->execute();
            } else {
                $stmt1 = $conn->prepare("UPDATE visitor SET password = ? WHERE email = ?");
                $stmt1->bind_param('ss', $new_password, $email);
                $stmt1->execute();

                $stmt2 = $conn->prepare("UPDATE registration_visitor SET password = ? WHERE visitor_id = ?");
                $stmt2->bind_param('ss', $new_password, $userId);
                $stmt2->execute();
            }

            if ($stmt1->affected_rows > 0 && $stmt2->affected_rows > 0) {
                session_unset();
                session_destroy();
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Success',
                                text: 'Password has been reset successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location.href = 'login.php';
                            });
                        });
                      </script>";
            } else {
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Error',
                                text: 'An error occurred while resetting the password.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location.href = 'reset_password.php';
                            });
                        });
                      </script>";
            }
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Passwords do not match.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location.href = 'reset_password.php';
                        });
                    });
                  </script>";
        }
    } 
}

function validatePassword($password)
{
    $errors = [];
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    if (!preg_match('/[A-Z]/', $password)) {
       $errors[] = "Password must contain at least one uppercase letter.";
    }

    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must contain at least one lowercase letter.";
    }

    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must contain at least one digit.";
    }

    if (!preg_match('/[\W]/', $password)) {
        $errors[] = "Password must contain at least one special character.";
    }
    if (!empty($errors)) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Error',
                        text: '".implode("\\n", $errors)."',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
              </script>";
    }
    return  $errors;
}
?>

<script>
    function validatePassword_Script() {
        var password = document.getElementById("password").value;
        var length = document.getElementById("length");
        var uppercase = document.getElementById("uppercase");
        var lowercase = document.getElementById("lowercase");
        var digit = document.getElementById("digit");
        var special = document.getElementById("special");
        if (password.length >= 8) {
            length.classList.remove("invalid_color");
            length.classList.add("valid_color");
            length.textContent = "✓ Minimum length of 8 characters.";
        } else {
            length.classList.remove("valid_color");
            length.classList.add("invalid_color");
            length.textContent = "X Minimum length of 8 characters.";
        }
        if (/[A-Z]/.test(password)) {
            uppercase.classList.remove("invalid_color");
            uppercase.classList.add("valid_color");
            uppercase.textContent = "✓ At least one uppercase letter.";
        } else {
            uppercase.classList.remove("valid_color");
            uppercase.classList.add("invalid_color");
            uppercase.textContent = "X At least one uppercase letter.";
        }
        if (/[a-z]/.test(password)) {
            lowercase.classList.remove("invalid_color");
            lowercase.classList.add("valid_color");
            lowercase.textContent = "✓ At least one lowercase letter.";
        } else {
            lowercase.classList.remove("valid_color");
            lowercase.classList.add("invalid_color");
            lowercase.textContent = "X At least one lowercase letter.";
        }
        if (/[0-9]/.test(password)) {
            digit.classList.remove("invalid_color");
            digit.classList.add("valid_color");
            digit.textContent = "✓ At least one digit.";
        } else {
            digit.classList.remove("valid_color");
            digit.classList.add("invalid_color");
            digit.textContent = "X At least one digit.";
        }
        if (/[\W]/.test(password)) {
            special.classList.remove("invalid_color");
            special.classList.add("valid_color");
            special.textContent = "✓ At least one special character (e.g., !@#$%^&*).";
        } else {
            special.classList.remove("valid_color");
            special.classList.add("invalid_color");
            special.textContent = "X At least one special character (e.g., !@#$%^&*).";
        }
    }
</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="./img/greenwoods_logo.png">
    <title>Reset Password</title>
    <style>
        .error-msg {
            color: red;
            margin-top: 5px;
        }
    </style>
    <link rel="stylesheet" href="CSS/forms.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="CSS/homepage.css">
</head>

<body>
<div class="lheader">
    <a href="homepage.php">
    <img src="./img/greenwoods_logo.png" alt="logo" style="width: 150px; height: auto; ">
</a>
    <h3>Greenwoods Executive Village</h3>
</div>

    <div class="form-container">

        <form action="" method="post">
            <h3>Reset Password</h3>
            <p>Please enter your new password.</p>
            <br>

            <input id="password" type="password" name="new_password" required placeholder="Enter your new password" onkeyup="validatePassword_Script()">
            <input type="password" name="confirm_new_password" required placeholder="Confirm your new password">
            <input type="submit" name="reset_password" value="Reset Password" class="form-btn">
        </form>
    </div>
    <div class="copyright">
        <strong>© 2024 MarsTech. </strong>All rights reserved.
    </div>
</body>

</html>