<?php
// homepage.php
// Start the session
session_start();
include("partials/navbar.php");
include("connect/connection.php");
// Check if the email session variable is set
if (isset($_SESSION['email'])) {
    // Get the email from the session
    $email = $_SESSION['email'];

    // Query the database to fetch user details
    $sql = "SELECT * FROM login WHERE email = '$email'";
    $result = $connect->query($sql);

    // Check if the query was successful
    if ($result) {
        // Fetch the user details
        $user = $result->fetch_assoc();

        // Echo the email, first_name, and last_name
        echo 'Welcome, ' . $user['email'] . '<br>';
        echo 'First Name: ' . $user['first_name'] . '<br>';
        echo 'Last Name: ' . $user['last_name'];
    } else {
        // Handle the error, e.g., display an error message
        echo 'Error fetching user details';
    }
} else {
    // If the email session variable is not set, redirect to the login page
    header('Location: index.php');
    exit();
}
?>
