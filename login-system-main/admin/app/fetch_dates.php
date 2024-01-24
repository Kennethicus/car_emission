<?php
$host     = 'localhost';
$username = 'root';
$password = '';
$dbname   ='calendar';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch available dates from the database (modify this query based on your database structure)
$sql = "SELECT start_datetime FROM schedule_list WHERE availability = 'available'";
$result = $conn->query($sql);

$availableDates = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Add each available date to the array
        $availableDates[] = $row['start_datetime'];
    }
}

// Close the database connection
$conn->close();

// Return the available dates in JSON format
echo json_encode($availableDates);
?>