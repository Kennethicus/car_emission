<?php
include("../connect/connection.php");

// Check if it's an AJAX request
if (isset($_POST['action'])) {
    $response = ''; // Initialize the response variable
    $displayModal = false; // Initialize the variable to determine if the modal should be displayed




    if ($_POST['action'] == 'delete_confirm') {
        // Additional logic for handling the delete_confirm action
        // This is where you can perform the deletion of schedule_list entries and update car_emission entries

        // Example: Set car_emission event_id to null for matching schedule_list entries
        $monthToDelete = $_POST['month'];
        $yearToDelete = date('Y', strtotime($monthToDelete));
        $monthToDelete = date('m', strtotime($monthToDelete));

        // Update car_emission entries
        $sqlUpdateCarEmission = "UPDATE car_emission
                                SET event_id = NULL
                                WHERE event_id IN (
                                    SELECT s.id 
                                    FROM schedule_list s
                                    WHERE YEAR(s.start_datetime) = $yearToDelete 
                                    AND MONTH(s.start_datetime) = $monthToDelete
                                )";

        $resultUpdateCarEmission = $connect->query($sqlUpdateCarEmission);

        // Delete schedule_list entries
        $sqlDeleteScheduleList = "DELETE FROM schedule_list 
                                  WHERE YEAR(start_datetime) = $yearToDelete 
                                  AND MONTH(start_datetime) = $monthToDelete";

        $resultDeleteScheduleList = $connect->query($sqlDeleteScheduleList);

        if ($resultUpdateCarEmission && $resultDeleteScheduleList) {
            $response = "car_emission entries updated and schedule_list entries deleted successfully.";
        } else {
            $response = "Error updating car_emission entries or deleting schedule_list entries: " . $connect->error;
        }
    }



    if ($_POST['action'] == 'delete_month') {
        $monthToDelete = $_POST['month'];

        // Extract year and month from the selected date
        $yearToDelete = date('Y', strtotime($monthToDelete));
        $monthToDelete = date('m', strtotime($monthToDelete));

        // Check if there are any schedules with car_emission event_id in the specified month and year
        $sqlCheckCarEmission = "SELECT s.id 
                                FROM schedule_list s
                                INNER JOIN car_emission c ON s.id = c.event_id
                                WHERE YEAR(s.start_datetime) = $yearToDelete 
                                AND MONTH(s.start_datetime) = $monthToDelete";

        $resultCheckCarEmission = $connect->query($sqlCheckCarEmission);

        if ($resultCheckCarEmission->num_rows > 0) {
            // Matching entry found, set the variable to display the modal
            $displayModal = true;
            $response = "Matching schedule with car_emission event_id found. Display modal for confirmation.";
        } else {
            // Delete all time slots for the specified month and year
            $sqlDelete = "DELETE FROM schedule_list WHERE YEAR(start_datetime) = $yearToDelete AND MONTH(start_datetime) = $monthToDelete";
            $resultDelete = $connect->query($sqlDelete);

            if ($resultDelete) {
                $response = "All time slots for the selected month have been deleted.";
            } else {
                $response = "Error deleting time slots: " . $connect->error;
            }
        }
    }


 // Check if it's an insert_month action
elseif ($_POST['action'] == 'insert_month') {
    $newEntriesAdded = false; // Declare the variable outside the block

    $month = $_POST['month'];

    // Get the total number of days in the specified month
    $lastDay = date('t', strtotime($month));

        // Define time slots
        $timeSlots = [
            '8:00 AM - 8:30 AM', '8:30 AM - 9:00 AM', '9:00 AM - 9:30 AM', '9:30 AM - 10:00 AM',
            '10:00 AM - 10:30 AM', '10:30 AM - 11:00 AM', '11:00 AM - 11:30 AM', '11:30 AM - 12:00 PM',
            '1:00 PM - 1:30 PM', '1:30 PM - 2:00 PM', '2:00 PM - 2:30 PM', '2:30 PM - 3:00 PM',
            '3:00 PM - 3:30 PM', '3:30 PM - 4:00 PM', '4:00 PM - 4:30 PM', '4:30 PM - 5:00 PM'
        ];

        // Check if there are any existing time slots for the entire month
        $sqlCheckMonth = "SELECT COUNT(*) as count FROM schedule_list WHERE YEAR(start_datetime) = YEAR('$month') AND MONTH(start_datetime) = MONTH('$month')";
        $resultCheckMonth = $connect->query($sqlCheckMonth);

        if ($resultCheckMonth) {
            $rowCheckMonth = $resultCheckMonth->fetch_assoc();
            if ($rowCheckMonth['count'] > 0) {
                // Existing slots found for the entire month
                $response = "Existing time slots found for the selected month. No new entries were added.";
            }
        }
        
        
        else {
            $response = "Error checking existing time slots for the month: " . $connect->error;
        }

        // Loop through each day of the month
        for ($day = 1; $day <= $lastDay; $day++) {
            // Check if the current day is not Saturday or Sunday
            $currentDayOfWeek = date('N', strtotime($month . '-' . str_pad($day, 2, '0', STR_PAD_LEFT)));
            if ($currentDayOfWeek >= 6) { // 6 is Saturday, 7 is Sunday
                continue; // Skip weekends
            }

            // Check if there are existing slots for the current day
            $currentDate = $month . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
            $sqlCheck = "SELECT COUNT(*) as count FROM schedule_list WHERE start_datetime BETWEEN '$currentDate 00:00:00' AND '$currentDate 23:59:59'";
            $resultCheck = $connect->query($sqlCheck);

            if ($resultCheck) {
                $rowCheck = $resultCheck->fetch_assoc();
                if ($rowCheck['count'] > 0) {
                    // Existing slots found for the current day, continue to the next day
                    continue;
                }
            } else {
                $response = "Error checking existing time slots: " . $connect->error;
            }

            // Combine the current date with the time slots
            foreach ($timeSlots as $timeSlot) {
                // Combine the current date with the time slot
                $currentDate = $month . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);

                // Extract start and end times from the time slot
                list($startTime, $endTime) = explode(' - ', $timeSlot);

                // Convert to MySQL datetime format
                $startDatetime = date('Y-m-d H:i:s', strtotime($currentDate . ' ' . $startTime));
                $endDatetime = date('Y-m-d H:i:s', strtotime($currentDate . ' ' . $endTime));

                // Set the price values
                $price_3 = 600;
                $price_2 = 500;
                $price_1 = 300;

                // Insert into the database
                $sql = "INSERT INTO schedule_list (title, description, qty_of_person, start_datetime, end_datetime, availability, price_3, price_2, price_1) VALUES ('Vehicle Emission', 'emission', 5, '$startDatetime', '$endDatetime', 'available', $price_3, $price_2, $price_1)";
                $result = $connect->query($sql);

                if ($result) {
                    $newEntriesAdded = true; // Set the flag to true if new entries are added
                } else {
                    $response = "Error: " . $connect->error;
                }
            }
        }

        // Check if new entries were added for any day
        if (!$newEntriesAdded) {
            $response = "Time slots already exist for all days in the selected month. No new entries were added.";
        }
    }

   // Include a flag in the response to indicate whether to display the modal
    $response .= '|modal:' . ($displayModal ? '1' : '0');

    echo $response;
    exit(); // Stop further execution after sending the response
}

// If it's not an AJAX request, handle direct form submission
// Your existing code for direct form submission can go here

?>
