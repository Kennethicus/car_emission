<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['email'])) {
    // Redirect to the homepage
    header('Location: home.php');
    exit();
}

include('connect/connection.php');

$error = ''; // Initialize an empty variable to store the error message
$emailValue = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';

if (isset($_POST["login"])) {
    $email = mysqli_real_escape_string($connect, trim($_POST['email']));
    $password = trim($_POST['password']);

    $sql = mysqli_query($connect, "SELECT * FROM login where email = '$email'");
    $count = mysqli_num_rows($sql);

    if ($count > 0) {
        $fetch = mysqli_fetch_assoc($sql);
        $hashpassword = $fetch["password"];

        if ($fetch["status"] == 0) {
            $error = "Please verify email account before login.";
        } else {
            // Check if the entered password matches the hashed password in the database
            if (password_verify($password, $hashpassword)) {
                $_SESSION['email'] = $email; // Store the user's email in the session
                echo '<script>
                        alert("Login successful.");
                        window.location.href = "home.php";
                      </script>';
                exit(); // Stop further execution of the script
            } else {
                $error = "Password invalid, please try again.";
            }
        }
    } else {
        // Alert for non-existing email
        $error = "Email invalid, please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'partials/header.php' ?>

<body>
    <?php include 'partials/nav-not-login.php' ?>
    <section class="py-5" style="background: var(--bs-border-color);">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-9 col-lg-12 col-xl-10" style="padding-top: 0px;margin-top: -40px;">
                    <div class="card shadow-lg o-hidden border-0 my-5"
                        <?php echo !empty($error) ? '' : 'data-aos="fade-left"'; ?>>
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-lg-6 col-xxl-6 d-none d-lg-flex"
                                    style="background: var(--bs-border-color);border-top-left-radius: 15px;border-bottom-left-radius: 15px;">
                                    <div class="flex-grow-1 bg-login-image"
                                        style="background: url(&quot;assets/img/dogs/image3.jpeg&quot;), #ffffff;background-size: cover, auto;"></div>
                                </div>
                                <div class="col-lg-6"
                                    style="background: #ffffff;border-bottom-right-radius: 15px;border-bottom-left-radius: 0px;border-top-right-radius: 15px;">
                                    <div class="p-5"
                                        style="background: #ffffff;border-top-right-radius: 15px;border-bottom-right-radius: 15px;">
                                        <h4 class="mb-4"
                                            style="text-align: center;color: rgb(65,157,85);">Welcome Back!</h4>
                                        <div class="text-center">
                                            <?php
                                            // Display the error message
                                            if (!empty($error)) {
                                                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                                            }
                                            ?>
                                        </div>
                                        <form action="#" method="POST" name="login">
                                            <div class="mb-3"><input class="form-control form-control-user" type="email"
                                                    id="email_address" class="form-control" aria-describedby="emailHelp"
                                                    placeholder="Email Address" name="email" required
                                                    value="<?php echo $emailValue; ?>"></div>
                                            <div class="mb-3"><input class="form-control form-control-user"
                                                    type="password" id="password" class="form-control"
                                                    placeholder="Password" name="password" required=""></div>
                                            <div class="mb-3">
                                                <div class="custom-control custom-checkbox small">
                                                    <div class="form-check"><input
                                                            class="form-check-input custom-control-input" type="checkbox"
                                                            id="formCheck-1"><label
                                                            class="form-check-label custom-control-label"
                                                            for="formCheck-1"
                                                            style="color: var(--bs-dark-text-emphasis);">Remember
                                                            Me</label></div>
                                                </div>
                                            </div><button
                                                class="btn btn-primary d-block btn-user w-100" type="submit"
                                                name="login">Login</button>
                                            <hr
                                                style="padding-bottom: 0px;padding-top: 1px;background: #419d55;">
                                            <hr>
                                        </form>
                                        <div class="text-center"><a class="small" href="recover_psw.php"
                                                style="color: var(--bs-dark-text-emphasis);">Forgot
                                                Password?</a></div>
                                        <div class="text-center"><a class="small" href="register.php"
                                                style="color: var(--bs-dark-text-emphasis);">Create an
                                                Account!</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include 'partials/footer.php'?>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/aos.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lightpick@1.3.4/lightpick.min.js"></script>
    <script src="assets/js/Date-Picker-From-and-To-datepicker.js"></script>
    <script src="assets/js/bold-and-dark.js"></script>
</body>

</html>
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
