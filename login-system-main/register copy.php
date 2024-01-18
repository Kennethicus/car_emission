<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['email'])) {
    // Redirect to the homepage
    header('Location: home.php');
    exit();
}
?>

<?php
include('connect/connection.php');

if (isset($_POST["register"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_pass"];
    $first_name = $_POST["firstname"];
    $middle_name = $_POST["middlename"];
    $last_name = $_POST["lastname"];
    $contact_no = $_POST["contactnum"];
    $address = $_POST["address"];
    $termsCheckbox = isset($_POST["termsCheckbox"]) ? 1 : 0;

    $check_query = mysqli_query($connect, "SELECT * FROM login where email ='$email'");
    $rowCount = mysqli_num_rows($check_query);

    if (empty($email) || empty($password) || empty($first_name) || empty($last_name) || empty($contact_no) || empty($address) || empty($middle_name) || !$termsCheckbox) {
?>
        <script>
            alert("All fields are required!");
        </script>
    <?php
    } else {
        // Check if email already exists
        if ($rowCount > 0) {
    ?>
            <script>
                alert("User with email already exists!");
            </script>
        <?php
        } else {
            // Check if passwords match
            if ($password != $confirm_password) {
        ?>
                <script>
                    alert("Passwords do not match!");
                </script>
            <?php
            } else {
                // Continue with your existing code for email sending and database insertion
                $password_hash = password_hash($password, PASSWORD_BCRYPT);

                $result = mysqli_query($connect, "INSERT INTO login (email, password, first_name, last_name, middle_name, contact_no, address, status, accept_terms) VALUES ('$email', '$password_hash', '$first_name', '$last_name', '$middle_name', '$contact_no', '$address', 0, '$termsCheckbox')");

                if ($result) {
                    $otp = rand(100000, 999999);
                    $_SESSION['otp'] = $otp;
                    $_SESSION['mail'] = $email;

                    require "Mail/phpmailer/PHPMailerAutoload.php";
                    $mail = new PHPMailer;

                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->Port = 587;
                    $mail->SMTPAuth = true;
                    $mail->SMTPSecure = 'tls';

                    $mail->Username = 'tecson.k.bsinfotech@gmail.com';
                    $mail->Password = 'eqjg sydu gegb qddk';

                    $mail->setFrom('email account', 'OTP Verification');
                    $mail->addAddress($_POST["email"]);

                    $mail->isHTML(true);
                    $mail->Subject = "Your verify code";
                    $mail->Body = "<p>Dear user, </p> <h3>Your verify OTP code is $otp <br></h3>
                    <br><br>
                    <p>With regrads,</p>
                    <b>Staff</b>";

                    if (!$mail->send()) {
            ?>
                        <script>
                            alert("<?php echo "Register Failed, Invalid Email " ?>");
                        </script>
                    <?php
                    } else {
                    ?>
                        <script>
                            alert("<?php echo "Register Successfully, OTP sent to " . $email ?>");
                            window.location.replace('verification.php');
                        </script>
    <?php
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="style.css">

    <link rel="icon" href="Favicon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    <title>Register Form</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="#">Register Form</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php" style="font-weight:bold; color:black; text-decoration:underline">Register</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>

    <main class="login-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Register</div>
                        <div class="card-body">
                            <form action="#" method="POST" name="register">

                                <div class="form-group row">
                                    <label for="first_name" class="col-md-4 col-form-label text-md-right">First name</label>
                                    <div class="col-md-6">
                                        <input type="text" id="first_name" class="form-control" name="firstname" required autofocus>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="middle_name" class="col-md-4 col-form-label text-md-right">Middle name</label>
                                    <div class="col-md-6">
                                        <input type="text" id="middle_name" class="form-control" name="middlename" autofocus>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="last_name" class="col-md-4 col-form-label text-md-right">Last name</label>
                                    <div class="col-md-6">
                                        <input type="text" id="last_name" class="form-control" name="lastname" required autofocus>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="contact_num" class="col-md-4 col-form-label text-md-right">Contact Number</label>
                                    <div class="col-md-6">
                                        <input type="text" id="contact_num" class="form-control" name="contactnum" required autofocus>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="address" class="col-md-4 col-form-label text-md-right">Address</label>
                                    <div class="col-md-6">
                                        <input type="text" id="address" class="form-control" name="address" required autofocus>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                                    <div class="col-md-6">
                                        <input type="text" id="email_address" class="form-control" name="email" required autofocus>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                    <div class="col-md-6">
                                        <input type="password" id="password" class="form-control" name="password" required>
                                        <i class="bi bi-eye-slash" id="togglePassword"></i>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="confirm_pass" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                                    <div class="col-md-6">
                                        <input type="password" id="confirm_pass" class="form-control" name="confirm_pass" required>
                                        <i class="bi bi-eye-slash" id="togglePassword"></i>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Terms and Conditions</label>
                                    <div class="col-md-6">
                                        <input type="checkbox" id="termsCheckbox" name="termsCheckbox" required>
                                        <label for="termsCheckbox">I agree to the <a href="#" data-toggle="modal" data-target="#termsModal">Terms and Conditions</a></label>
                                    </div>
                                </div>

                                <div class="col-md-6 offset-md-4">
                                    <input type="submit" value="Register" name="register" class="btn btn-primary">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal for Terms and Conditions -->
    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>This is where you put your terms and conditions.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script>
        const toggle = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        toggle.addEventListener('click', function () {
            if (password.type === "password") {
                password.type = 'text';
            } else {
                password.type = 'password';
            }
            this.classList.toggle('bi-eye');
        });
    </script>

</body>

</html>
