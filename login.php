<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="image/png" href="./img/greenwoods_logo.png">
   <title>Log In</title>
   
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
	  
   </style>
   <link rel="stylesheet" href="CSS/forms.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.min.css">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="lheader">
    <a href="homepage.php">
    <img src="./img/greenwoods_logo.png" alt="logo" style="width: 150px; height: auto; ">
</a>
    <h3>Greenwoods Executive Village</h3>
</div>

<div class="form-container">

   <form action="process_login.php" method="post">
      <h3>Log Into Your Account</h3>
      <p>Enter your email and password to login.</p>
      <br>
		
      <input type="email" name="email" required placeholder="Enter your email" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
      <div class="input-pass">
         <input type="password" name="password" required placeholder="Enter your password" id="password">
         <div class="eye-icon">
            <img src="img/eye-close.png" alt="Eye Icon">
         </div>
      </div>
	  <div>
	  
        <em style="color: red !important; margin-top: 5px;"></em>
    </div>
      <input type="submit" name="submit" value="login now" class="form-btn">
      <p><a href="forgot_pass.php" >Forgot your password?</a></p>
      <p>Don't have an account? <a href="register.php">Register here</a></p>
	  
   </form>
</div>
<div class="copyright">
   <strong>© 2024 MarsTech. </strong>All rights reserved.
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.querySelector('input[name="password"]');
        const eyeIcon = document.querySelector('.eye-icon');

        eyeIcon.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            const eyeIconImg = document.querySelector('.eye-icon img');
            eyeIconImg.src = type === 'password' ? 'img/eye-close.png' : 'img/eye-open.png';
        });

        $('form').on('submit', function(event) {
            event.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: 'process_login.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        title: response.title,
                        text: response.message,
                        icon: response.status,
                        confirmButtonText: 'OK'
                    }).then(function() {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else if (response.status === 'error' && response.title === 'No User Found') {
                            $('form')[0].reset();
                        }
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        title: 'Error',
                        text: 'AJAX request failed: ' + textStatus + ' - ' + errorThrown,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('status') === 'success') {
            Swal.fire({
                title: 'Success',
                text: 'Logged in successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }
    });
</script>

</body>
</html>