<!-- navbar.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Your Website</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Your custom CSS styles -->
    <style>
        /* Add your custom styles here */
        .navbar-logo {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            color: darkgreen;
        }

        .navbar-nav .nav-link:hover {
            color: lightgreen !important;
        }

        .navbar-account-options {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: none;
            position: absolute;
            background-color: #f8f9fa;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        .navbar-account:hover .navbar-account-options {
            display: block;
        }

        .navbar-account-options li {
            padding: 8px;
        }

        .navbar-account-options li:hover {
            background-color: #d1e7dd;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <span class="navbar-logo">Your Logo</span>
        </a>

        <!-- Toggle button for small screens -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="book-now.php">Book Now</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="schedule-status.php">Status</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Price</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Services</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link navbar-account" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        User Account
                    </a>
                    <ul class="navbar-account-options dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="account.php">Go to Account</a></li>
                        <li><a class="dropdown-item" href="logout.php">Log Out</a></li>
                        <!-- Add more account options as needed -->
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>
