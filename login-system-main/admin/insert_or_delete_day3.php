<?php
include("../connect/connection.php");

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $responseDay = ''; // Initialize the response variable
    $displayModalDay = false; // Initialize the variable to determine if the modal should be displayed

    if ($action === 'insert_day') {
        $responseDay = insertDay();
    } elseif ($action === 'delete_day') {
        list($responseDay, $displayModalDay) = deleteDay();
    } elseif ($action === 'delete_day_confirm') {
        $responseDay = confirmDeleteDay();
    } else {
        $responseDay = "Invalid action.";
    }

    echo $responseDay;
    if ($displayModalDay) {
        // Display the modal here
    }
} else {
    echo "Invalid request.";
}

function confirmDeleteDay() {
    global $connect; // Add this line to make $conn accessible inside the function

    $responseDay = '';
    
    if (isset($_POST['selected_date'])) {
        $selectedDate = $_POST['selected_date'];

        // Check if there are any schedules with car_emission event_id for the selected day
        $sqlCheckCarEmission = "SELECT s.id, c.event_id
                                FROM schedule_list s
                                LEFT JOIN car_emission c ON s.id = c.event_id
                                WHERE DATE(s.start_datetime) = '$selectedDate'";

        $resultCheckCarEmission = $connect->query($sqlCheckCarEmission);

        if ($resultCheckCarEmission) {
            while ($row = $resultCheckCarEmission->fetch_assoc()) {
                $eventId = $row['event_id'];
                if (!is_null($eventId)) {
                    // Set the event_id to null
                    $sqlUpdateCarEmission = "UPDATE car_emission SET event_id = NULL WHERE event_id = $eventId";
                    $resultUpdateCarEmission = $connect->query($sqlUpdateCarEmission);

                    if (!$resultUpdateCarEmission) {
                        $responseDay = "Error updating car_emission event_id: " . $connect->error;
                        return $responseDay;
                    }
                }
            }
        } else {
            $responseDay = "Error checking car_emission event_id: " . $connect->error;
            return $responseDay;
        }

        // Delete time slots for the selected day
        $sqlDelete = "DELETE FROM schedule_list WHERE DATE(start_datetime) = '$selectedDate'";
        $resultDelete = $connect->query($sqlDelete);

        if ($resultDelete) {
            $responseDay = "Time slots for the selected day have been deleted successfully.";
        } else {
            $responseDay = "Error deleting time slots: " . $connect->error;
        }
    } else {
        $responseDay = "Invalid request.";
    }

    return $responseDay;
}





function insertDay() {
    global $connect; // Add this line to make $conn accessible inside the function

    if (isset($_POST['selected_date'])) {
        $selectedDate = $_POST['selected_date'];

        // Check if time slots already exist for the selected day
        $sqlCheck = "SELECT COUNT(*) as count FROM schedule_list WHERE DATE(start_datetime) = '$selectedDate'";
        $resultCheck = $connect->query($sqlCheck);

        if ($resultCheck) {
            $rowCheck = $resultCheck->fetch_assoc();
            if ($rowCheck['count'] > 0) {
                return "Time slots already exist for the selected day. No new entries were added.";
            }
        } else {
            return "Error checking existing time slots: " .$connect->error;
        }

      // Define time slots
    $timeSlots = [
        '8:00 AM - 8:30 AM', '8:30 AM - 9:00 AM', '9:00 AM - 9:30 AM', '9:30 AM - 10:00 AM',
        '10:00 AM - 10:30 AM', '10:30 AM - 11:00 AM', '11:00 AM - 11:30 AM', '11:30 AM - 12:00 PM',
        '1:00 PM - 1:30 PM', '1:30 PM - 2:00 PM', '2:00 PM - 2:30 PM', '2:30 PM - 3:00 PM',
        '3:00 PM - 3:30 PM', '3:30 PM - 4:00 PM', '4:00 PM - 4:30 PM', '4:30 PM - 5:00 PM'
    ];

    // Loop through each time slot
    foreach ($timeSlots as $timeSlot) {
        // Extract start and end times from the time slot
        list($startTime, $endTime) = explode(' - ', $timeSlot);

        // Convert to MySQL datetime format
        $startDatetime = date('Y-m-d H:i:s', strtotime($selectedDate . ' ' . $startTime));
        $endDatetime = date('Y-m-d H:i:s', strtotime($selectedDate . ' ' . $endTime));

        // Set the price values
        $price_3 = 600;
        $price_2 = 500;
        $price_1 = 300;

        // Insert into the database
        $sql = "INSERT INTO schedule_list (title, description, qty_of_person, start_datetime, end_datetime, availability, price_3, price_2, price_1) VALUES ('Vehicle Emission', 'emission', 5, '$startDatetime', '$endDatetime', 'available', $price_3, $price_2, $price_1)";
        $result = $connect->query($sql);

        if (!$result) {
            echo "Error: " . $connect->error;
            exit();
        }
    }

        echo "Successfully inserted time slots for the selected day.";
    } else {
        echo "Invalid request.";
    }
}

function deleteDay() {
    global $connect; // Add this line to make $conn accessible inside the function

    $displayModalDay = false; // Initialize the variable to determine if the modal should be displayed
    $responseDay = '';

    if (isset($_POST['selected_date'])) {
        $selectedDate = $_POST['selected_date'];

        // Check if there are any schedules with car_emission event_id for the selected day
        $sqlCheckCarEmission = "SELECT s.id 
                                FROM schedule_list s
                                INNER JOIN car_emission c ON s.id = c.event_id
                                WHERE DATE(s.start_datetime) = '$selectedDate'";

        $resultCheckCarEmission = $connect->query($sqlCheckCarEmission);

        if ($resultCheckCarEmission->num_rows > 0) {
            // Matching entry found, set the variable to display the modal
            $displayModalDay = true;
            $responseDay = "Matching Day schedule with car_emission event_id found. Display modal for confirmation.";
        } else {
            // Delete time slots for the selected day
            $sqlDelete = "DELETE FROM schedule_list WHERE DATE(start_datetime) = '$selectedDate'";
            $resultDelete = $connect->query($sqlDelete);

            if ($resultDelete) {
                $responseDay = "Time slots for the selected day have been deleted successfully.";
            } else {
                $responseDay = "Error deleting time slots: " . $connect->error;
            }
        }

        // Include a flag in the response to indicate whether to display the modal
        $responseDay .= '|modal:' . ($displayModalDay ? '1' : '0');

        return array($responseDay, $displayModalDay);
    } else {
        return array("Invalid request.", false);
    }
}
?>
