<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['email'])) {
    // Redirect to the homepage
    header('Location: home.php');
    exit();
}

include('connect/connection.php');

if (isset($_POST["register"])) {
    $email = mysqli_real_escape_string($connect, $_POST["email"]);
    $password = mysqli_real_escape_string($connect, $_POST["password"]);
    $confirm_password = mysqli_real_escape_string($connect, $_POST["confirm_pass"]);
    $first_name = mysqli_real_escape_string($connect, $_POST["firstname"]);
    $middle_name = mysqli_real_escape_string($connect, $_POST["middlename"]);
    $last_name = mysqli_real_escape_string($connect, $_POST["lastname"]);
    $contact_no = mysqli_real_escape_string($connect, $_POST["contactnum"]);
    $address = mysqli_real_escape_string($connect, $_POST["address"]);
    $termsCheckbox = isset($_POST["termsCheckbox"]) ? 1 : 0;

    $check_query = mysqli_query($connect, "SELECT * FROM login WHERE email ='$email'");
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
                            // Redirect to verification page after showing the alert
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

<?php include 'partials/header.php' ?>

<body style="background: rgb(255,255,255);">
    <?php include 'partials/nav.php  ' ?>
    <section class="py-5" style="border-color: rgb(255,255,255);background: var(--bs-secondary-bg);">
        <div class="row">
            <div class="col" style="margin-top: -48px;background: var(--bs-secondary-bg);">
                <div class="container" data-aos="fade-left">
                    <div class="card shadow-lg o-hidden border-0 my-5">
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-lg-5 d-none d-lg-flex" style="background: var(--bs-secondary-bg);border-top-left-radius: 15px;">
                                    <div class="flex-grow-1 bg-register-image" style="background: url(&quot;assets/img/dogs/image2.jpeg&quot;);background-size: contain;"></div>
                                </div>
                                <div class="col-lg-7" style="background: #ffffff;border-bottom-right-radius: 15px;border-top-right-radius: 15px;">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h4 class="mb-4" style="color: rgb(65,157,85);">Create an Account!</h4>
                                        </div>
                                        <form action="#" method="POST" name="register" onsubmit="return validateForm()">
                                            <div class="row mb-3">
                                                <div class="col-md-4 mb-3 mb-sm-0"><input class="form-control form-control-user" type="text" id="first_name" placeholder="First Name" name="firstname" required autofocus></div>
                                                <div class="col-md-4 mb-3 mb-sm-0"><input class="form-control form-control-user" type="text" id="middle_name" placeholder="Middle Name" name="middlename" required autofocus> </div>
                                                <div class="col-md-4"><input class="form-control form-control-user" type="text" id="last_name" placeholder="Last Name" name="lastname" required autofocus></div>
                                            </div>
                                            <div class="mb-3"><input class="form-control form-control-user" type="tel" id="contact_num" placeholder="Contact Number" name="contactnum" pattern="[0-9]*" required autofocus></div>
                                            <div class="mb-3"><input class="form-control form-control-user" type="text" id="address" aria-describedby="emailHelp" placeholder="Address" name="address" required autofocus></div>
                                            <div class="mb-3"><input class="form-control form-control-user" type="email" id="email_address" aria-describedby="emailHelp" placeholder="Email Address" name="email" required autofocus></div>
                                            <div class="row mb-3">
                                                <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="password" id="password" placeholder="Password" name="password" required="" minlength="7" maxlength="50" pattern="^[a-zA-Z0-9_.-]*$"></div>
                                                <div class="col-sm-6"><input class="form-control form-control-user" type="password" id="confirm_pass" placeholder="Repeat Password" name="confirm_pass" required></div>
                                            </div>
                                            <div class="mb-3"></div>
                                            <div class="form-check" style="text-align: left;">
                                                <input class="form-check-input" type="checkbox" id="termsCheckbox" name="termsCheckbox" required>
                                                <label for="termsCheckbox" style="color: rgb(73, 80, 87);">
                                                    I agree to the <a href="#" data-toggle="modal" data-target="#termsModal" style="color: #2c4fc3">Terms and Conditions</a>
                                                </label>
                                            </div>
                                            <hr>
                                            <button class="btn btn-primary active d-block btn-user w-100" type="submit" value="Register" name="register">Register Account</button>
                                        </form>
                                        <div class="text-center">
                                            <hr style="padding-bottom: 1px;background: #419d55;"><a class="small" href="index.php" style="color: var(--bs-dark-text-emphasis);">Already have an account? Login!</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for Terms and Conditions -->
        <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content bg-light text-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                        <button type="button" class="close custom-close-btn border-0 bg-transparent " data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>This is where you put your terms and conditions.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            /* Custom style to increase the close button size */
            .custom-close-btn {
                font-size: 2.3rem; /* Adjust the font size as needed */
            }
        </style>

    </section>

    <?php include 'partials/footer.php' ?>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/aos.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

    <script src="assets/js/bold-and-dark.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    <script>
        function validateForm() {
            var email = document.getElementById('email_address').value;
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_pass').value;
            var termsCheckbox = document.getElementById('termsCheckbox').checked;

            if (email === '' || password === '' || confirmPassword === '' || termsCheckbox === false) {
                alert("All fields are required!");
                return false; // Prevent form submission
            }

            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                return false; // Prevent form submission
            }

            return true; // Allow form submission
        }
    </script>
</body>

</html>
