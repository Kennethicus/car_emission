<?php 
include("../connect/connection.php");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script> alert('Error: No data to save.'); location.replace('./') </script>";
    $connect->close();
    exit;
}

extract($_POST);
$allday = isset($allday);

// If id is empty and no duplicate, it means it's a new schedule
if (empty($id)) {
    // Check if a schedule with the same start and end datetime already exists
    $checkDuplicateSql = "SELECT COUNT(*) FROM `schedule_list` WHERE `start_datetime` = '$start_datetime' AND `end_datetime` = '$end_datetime'";

    $result = $connect->query($checkDuplicateSql);

    if ($result && $result->fetch_assoc()['COUNT(*)'] > 0) {
        // If a schedule with the same start and end datetime exists, show an alert
        echo "<script> alert('An existing schedule with the same start and end datetime already exists.'); location.replace('index2.php') </script>";
        $connect->close();
        exit;
    }

    // If id is empty and no duplicate, it means it's a new schedule
    $sql = "INSERT INTO `schedule_list` (`title`, `description`, `qty_of_person`, `start_datetime`, `end_datetime`, `price_3`, `price_2`, `price_1`) VALUES ('$title', '$description', '$qty_persons', '$start_datetime', '$end_datetime', '$price3', '$price2', '$price1')";
} else {
    // If id is not empty, it means it's an existing schedule, so update it
    $sql = "UPDATE `schedule_list` SET `title` = '$title', `description` = '$description', `qty_of_person` = '$qty_persons', `start_datetime` = '$start_datetime', `end_datetime` = '$end_datetime', `price_3` = '$price3', `price_2` = '$price2', `price_1` = '$price1' WHERE `id` = '$id'";
}


$save = $connect->query($sql);

if ($save) {
    echo "<script> alert('Schedule Successfully Saved.'); location.replace('index2.php') </script>";
} else {
    echo "<pre>";
    echo "An Error occurred.<br>";
    echo "Error: " . $connect->error . "<br>";
    echo "SQL: " . $sql . "<br>";
    echo "</pre>";
}

$connect->close();
?>
