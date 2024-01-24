<?php
include("../connect/connection.php");

if(isset($_POST['date'])) {
    $selectedDate = $_POST['date'];

    // Fetch schedule details for the selected date
    $sql = "SELECT * FROM schedule_list WHERE DATE(start_datetime) = '$selectedDate'";
    $result = $connect->query($sql);

    if ($result->num_rows > 0) {
        // Separate schedules into two arrays based on time
        $column1 = [];
        $column2 = [];

        while ($row = $result->fetch_assoc()) {
            // Extract time portion from datetime values with AM/PM format
            $startTime = date('h:i A', strtotime($row['start_datetime']));
            $endTime = date('h:i A', strtotime($row['end_datetime']));

            // Determine which column to add the schedule
            if (strtotime($startTime) < strtotime('12:00 PM')) {
                $column1[] = '<button class="btn btn-primary" onclick="showDetails(' . $row['id'] . ')">' . $startTime . ' to ' . $endTime . '</button>';
            } else {
                $column2[] = '<button class="btn btn-primary" onclick="showDetails(' . $row['id'] . ')">' . $startTime . ' to ' . $endTime . '</button>';
            }
        }

        echo '<h4>Schedule for ' . $selectedDate . '</h4>';
        echo '<div class="row">';
        
        echo '<div class="col-md-4">';
        echo '<ul style="list-style-type: none; padding: 0;">';
        foreach ($column1 as $schedule) {
            echo '<li style="margin-bottom: 10px;">' . $schedule . '</li>';
        }
        echo '</ul>';
        echo '</div>';
        
        echo '<div class="col-md-4">';
        echo '<ul style="list-style-type: none; padding: 0;">';
        foreach ($column2 as $schedule) {
            echo '<li style="margin-bottom: 10px;">' . $schedule . '</li>';
        }
        echo '</ul>';
        echo '</div>';
        
        echo '</div>';
    } else {
        echo 'No schedule for ' . $selectedDate;
    }
}
?>
