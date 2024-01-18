<?php
// get_customer_details.php

include("../connect/connection.php");

if (isset($_POST['reservationId'])) {
    $reservationId = $_POST['reservationId'];

    // Fetch customer details and event details based on ID
    $query = "SELECT ce.customer_first_name, ce.customer_last_name, sl.start_datetime, sl.end_datetime
              FROM car_emission ce
              JOIN schedule_list sl ON ce.event_id = sl.id
              WHERE ce.id = $reservationId";

    $result = $connect->query($query);

    if ($result->num_rows == 1) {
        $data = $result->fetch_assoc();
        $customerName = $data['customer_first_name'] . ' ' . $data['customer_last_name'];
        $eventDate = date("F j, Y", strtotime($data['start_datetime']));
        $startTime = date("h:i A", strtotime($data['start_datetime']));
        $endTime = date("h:i A", strtotime($data['end_datetime']));

        echo json_encode([
            'customerName' => $customerName,
            'eventDate' => $eventDate,
            'startTime' => $startTime,
            'endTime' => $endTime
        ]);
    } else {
        echo json_encode(['error' => 'Details not found']);
    }
}
?>
