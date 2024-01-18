<!-- admin/index.php -->
<?php
// Start the session
session_start();

// Check if the admin is already logged in, redirect to home if true
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login - Brand</title>
    <link rel="stylesheet" href="assets2/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,600,600i&amp;display=swap">
    <link rel="stylesheet" href="assets2/css/aos.min.css">
    <link rel="stylesheet" href="assets2/css/baguetteBox.min.css">
    <link rel="stylesheet" href="assets2/css/Footer-Basic-icons.css">
    <link rel="stylesheet" href="assets2/css/vanilla-zoom.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top bg-body clean-navbar navbar-light">
        <div class="container"><a class="navbar-brand logo" href="#"><span style="color: rgb(11, 80, 55);">Brand</span></a><button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="navbar-nav ms-auto"></ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#"><span style="color: rgb(11, 80, 55);">Second Item</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><span style="color: rgb(11, 80, 55);">Third Item</span></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="page login-page">
        <section class="clean-block clean-form dark">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text-info"><span style="color: rgb(11, 80, 55);">Log In</span></h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo.</p>
                </div>
                <form data-aos="fade-left" style="border-top-color: #419d55;" action="process/login-p.php" method="post">
                    <div class="mb-3"><label class="form-label" for="email">Username</label>
                    <input type="text" class="form-control item" id="username" name="username" data-bs-theme="light" required></div>
                    <div class="mb-3"><label class="form-label" for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" data-bs-theme="light"></div>
                    <div class="mb-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="checkbox" data-bs-theme="light"><label class="form-check-label" for="checkbox">Remember me</label></div>
                    </div><button class="btn btn-primary" type="submit">Log In</button>
                </form>
            </div>
        </section>
        <footer class="text-center">
            <div class="container text-muted py-4 py-lg-5">
                <p class="mb-0">Copyright © 2024 Brand</p>
            </div>
        </footer>
    </main>
    <script src="assets2/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets2/js/aos.min.js"></script>
    <script src="assets2/js/bs-init.js"></script>
    <script src="assets2/js/baguetteBox.min.js"></script>
    <script src="assets2/js/vanilla-zoom.js"></script>
    <script src="assets2/js/theme.js"></script>
</body>

</html>
