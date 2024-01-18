<!-- save_schedule.php -->
<?php 
session_start();
include("../connect/connection.php");

if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script> alert('Error: No data to save.'); location.replace('book-now2.php') </script>";
    $connect->close();
    exit;
}

extract($_POST);
$allday = isset($allday);

if (empty($id)) {
    $sql = "INSERT INTO `schedule_list` (`title`, `description`, `qty_of_person`, `price_3`, `price_2`, `price_1`, `start_datetime`, `end_datetime`) VALUES ('$title', '$description', '$qty_persons', '$price3', '$price2', '$price1', '$start_datetime', '$end_datetime')";
} else {
    $sql = "UPDATE `schedule_list` SET `title` = '$title', `description` = '$description', `qty_of_person` = '$qty_persons', `price_3` = '$price3', `price_2` = '$price2', `price_1` = '$price1' , `start_datetime` = '$start_datetime', `end_datetime` = '$end_datetime' WHERE `id` = '$id'";
}

$save = $connect->query($sql);

if ($save) {    
    echo "<script> alert('Schedule Successfully Saved.'); location.replace('book-now2.php') </script>";
} else {
    echo "<pre>";
    echo "An Error occurred.<br>";
    echo "Error: " . $connect->error . "<br>";
    echo "SQL: " . $sql . "<br>";
    echo "</pre>";
}

$connect->close();
?>
