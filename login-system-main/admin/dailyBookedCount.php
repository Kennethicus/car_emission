<?php
//dailyBookedCount.php
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

// Assuming you're passing the year and month as GET parameters
$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('m');

// Initialize arrays for daily counts
$dailyBookedCounts = array_fill(1, 31, 0);
$dailyCanceledCounts = array_fill(1, 31, 0);
$dailyDonedCounts = array_fill(1, 31, 0);
$dailyFullyPaidCounts = array_fill(1, 31, 0);
$dailyHalfPaidCounts = array_fill(1, 31, 0); // Initialize array for half paid counts
$dailyUnpaidCounts = array_fill(1, 31, 0); // Initialize array for unpaid counts

// Query for daily booked counts
$dailyBookedQuery = "SELECT DAY(s.start_datetime) as day, COUNT(*) as count
                     FROM car_emission c
                     JOIN schedule_list s ON c.event_id = s.id
                     WHERE c.status = 'booked' AND YEAR(s.start_datetime) = $selectedYear AND MONTH(s.start_datetime) = $selectedMonth
                     GROUP BY DAY(s.start_datetime)";
$dailyBookedResult = $connect->query($dailyBookedQuery);

// Populate the array with fetched booked counts
while ($row = $dailyBookedResult->fetch_assoc()) {
    $day = $row['day'];
    $count = $row['count'];
    $dailyBookedCounts[$day] = $count;
}

// Query for daily canceled counts
$dailyCanceledQuery = "SELECT DAY(s.start_datetime) as day, COUNT(*) as count
                       FROM car_emission c
                       JOIN schedule_list s ON c.event_id = s.id
                       WHERE c.status = 'canceled' AND YEAR(s.start_datetime) = $selectedYear AND MONTH(s.start_datetime) = $selectedMonth
                       GROUP BY DAY(s.start_datetime)";
$dailyCanceledResult = $connect->query($dailyCanceledQuery);

// Populate the array with fetched canceled counts
while ($row = $dailyCanceledResult->fetch_assoc()) {
    $day = $row['day'];
    $count = $row['count'];
    $dailyCanceledCounts[$day] = $count;
}

// Query for daily doned counts
$dailyDonedQuery = "SELECT DAY(s.start_datetime) as day, COUNT(*) as count
                    FROM car_emission c
                    JOIN schedule_list s ON c.event_id = s.id
                    WHERE c.status = 'doned' AND YEAR(s.start_datetime) = $selectedYear AND MONTH(s.start_datetime) = $selectedMonth
                    GROUP BY DAY(s.start_datetime)";
$dailyDonedResult = $connect->query($dailyDonedQuery);

// Populate the array with fetched doned counts
while ($row = $dailyDonedResult->fetch_assoc()) {
    $day = $row['day'];
    $count = $row['count'];
    $dailyDonedCounts[$day] = $count;
}

// Query for daily fully paid counts
$dailyFullyPaidQuery = "SELECT DAY(s.start_datetime) as day, COUNT(*) as count
                        FROM car_emission c
                        JOIN schedule_list s ON c.event_id = s.id
                        WHERE c.paymentStatus = 'fully paid' AND YEAR(s.start_datetime) = $selectedYear AND MONTH(s.start_datetime) = $selectedMonth
                        GROUP BY DAY(s.start_datetime)";
$dailyFullyPaidResult = $connect->query($dailyFullyPaidQuery);

// Populate the array with fetched fully paid counts
while ($row = $dailyFullyPaidResult->fetch_assoc()) {
    $day = $row['day'];
    $count = $row['count'];
    $dailyFullyPaidCounts[$day] = $count;
}

// Query for daily half paid counts
$dailyHalfPaidQuery = "SELECT DAY(s.start_datetime) as day, COUNT(*) as count
                       FROM car_emission c
                       JOIN schedule_list s ON c.event_id = s.id
                       WHERE c.paymentStatus = 'half paid' AND YEAR(s.start_datetime) = $selectedYear AND MONTH(s.start_datetime) = $selectedMonth
                       GROUP BY DAY(s.start_datetime)";
$dailyHalfPaidResult = $connect->query($dailyHalfPaidQuery);

// Populate the array with fetched half paid counts
while ($row = $dailyHalfPaidResult->fetch_assoc()) {
    $day = $row['day'];
    $count = $row['count'];
    $dailyHalfPaidCounts[$day] = $count;
}

// Query for daily unpaid counts
$dailyUnpaidQuery = "SELECT DAY(s.start_datetime) as day, COUNT(*) as count
                     FROM car_emission c
                     JOIN schedule_list s ON c.event_id = s.id
                     WHERE c.paymentStatus = 'unpaid' AND YEAR(s.start_datetime) = $selectedYear AND MONTH(s.start_datetime) = $selectedMonth
                     GROUP BY DAY(s.start_datetime)";
$dailyUnpaidResult = $connect->query($dailyUnpaidQuery);

// Populate the array with fetched unpaid counts
while ($row = $dailyUnpaidResult->fetch_assoc()) {
    $day = $row['day'];
    $count = $row['count'];
    $dailyUnpaidCounts[$day] = $count;
}

// Return data as JSON including the daily half paid and unpaid counts
echo json_encode([
    'labels' => array_keys($dailyBookedCounts),
    'dailyBookedCounts' => array_values($dailyBookedCounts),
    'dailyCanceledCounts' => array_values($dailyCanceledCounts),
    'dailyDonedCounts' => array_values($dailyDonedCounts),
    'dailyFullyPaidCounts' => array_values($dailyFullyPaidCounts),
    'dailyHalfPaidCounts' => array_values($dailyHalfPaidCounts),
    'dailyUnpaidCounts' => array_values($dailyUnpaidCounts)
]);

?>
