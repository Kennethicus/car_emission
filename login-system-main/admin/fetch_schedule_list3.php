<?php
include("../connect/connection.php");

if(isset($_POST['id'])) {
    $scheduleId = $_POST['id'];

    // Fetch detailed schedule information for the selected ID
    $sql = "SELECT * FROM schedule_list WHERE id = $scheduleId";
    $result = $connect->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the schedule details
        $row = $result->fetch_assoc();

        // Prepare the details as an associative array
        $scheduleDetails = [
            'title' => $row['title'],
            'description' => $row['description'],
            'qty_of_person' => $row['qty_of_person'],
            'reserve_count' => $row['reserve_count'],
            'price_3' => $row['price_3'],
            'price_2' => $row['price_2'],
            'price_1' => $row['price_1'],
            'start_datetime' => $row['start_datetime'],
            'end_datetime' => $row['end_datetime'],
            'availability' => $row['availability']
        ];

        // Send the details as a JSON response
        echo json_encode($scheduleDetails);
    } else {
        echo 'Schedule details not found';
    }
} else {
    echo 'Invalid request';
}

// Close the database connection
$connect->close();
?>
