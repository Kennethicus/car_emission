<?php


session_start();
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file
    include("../../connect/connection.php");

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the user credentials
    $query = "SELECT * FROM smurf_admin WHERE username = '$username' AND password = '$password'";
    $result = $connect->query($query);

    if ($result->num_rows == 1) {
        // Authentication successful, redirect to admin dashboard
        $_SESSION['admin'] = $username;
        $connect->close(); // Close the database connection before redirecting
        header("Location: ../dashboard.php");
        exit();
    } else {
        // Authentication failed, redirect back to the login form with an error message
        $connect->close(); // Close the database connection before redirecting
        header("Location: ../index.php?error=1");
        exit();
    }
} else {
    // If the form was not submitted, redirect to the login page
    header("Location: ../index.php");
    exit();
}
?>
