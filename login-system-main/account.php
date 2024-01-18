<?php
// account.php
session_start();
include(__DIR__ . "/navbar.php");
include("connect/connection.php"); // Include the database connection file

// Check if the user is logged in
if (isset($_SESSION['email'])) {
    // Retrieve user information from the database
    $email = $_SESSION['email'];
    $query = "SELECT * FROM login WHERE email = '$email'";
    $result = $connect->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit();
    }
} else {
    // If the user is not logged in, redirect to the login page
    header('Location: index.php');
    exit();
}

// Close the database connection
$connect->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>User Account</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: linear-gradient(45deg, #4CAF50, #2196F3);
            border-bottom: none;
            border-radius: 15px 15px 0 0;
            color: white;
            text-align: center;
        }

        .card-title {
            margin-top: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        .card-text {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .edit-profile-link {
            color: #ffffff;
            text-decoration: none;
        }

        .edit-profile-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">User Information</h5>
            </div>
            <div class="card-body">
                <p class="card-text"><strong>Email:</strong> <?php echo $row['email']; ?></p>
                <p class="card-text"><strong>First Name:</strong> <?php echo $row['first_name']; ?></p>
                <p class="card-text"><strong>Last Name:</strong> <?php echo $row['last_name']; ?></p>
                <p class="card-text"><strong>Contact No:</strong> <?php echo $row['contact_no']; ?></p>
                <p class="card-text"><strong>Address:</strong> <?php echo $row['address']; ?></p>
                <!-- Add more fields as needed -->

                <!-- "Edit Profile" button linking to the edit_profile.php page -->
                <a href="edit-profile.php" class="btn btn-success edit-profile-link">Edit Profile</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
