<?php

require_once 'inc/config.php';

// check if admin is not logged in, redirect it to login
if (!isset($_SESSION['activeAdmin'])) {
    header("location: index.php");
    return;
}

if (isset($_POST['start_time']) && isset($_POST['end_time'])) {
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // validate inputs
    if (empty($start_time) || empty($end_time)) {
        $_SESSION['settings'] = "error";
        header("location: settings.php");
        return;
    }
    
    // convert times to sql times format
    $start_time = date('H:i:s', strtotime($start_time));
    $end_time = date('H:i:s', strtotime($end_time));
    
    // validate times
    if ($end_time < $start_time) {
        $_SESSION['settings'] = "time_error";
        header("location: settings.php");
        return;
    }

    echo $start_time . '<br>';
    echo $end_time;
    
    // strip inputs
    $start_time = strip_tags($start_time);
    $end_time = strip_tags($end_time);

    // prepare sql statements
    $sql1 = "DELETE FROM settings";
    $sql2 = "INSERT INTO settings (start_time, end_time) VALUES ('" . mysqli_real_escape_string($con, $start_time) . "', '" . mysqli_real_escape_string($con, $end_time) . "')";

    // execute queries
    $result1 = mysqli_query($con, $sql1);
    $result2 = mysqli_query($con, $sql2);
    mysqli_close($con);

    // check add query result
    if ($result2) {
        // goto home with successful settings flag
        $_SESSION['settings'] = "successful";
        header("location: home.php");
    } else {
        // if failed goto settings with settings flag error
        $_SESSION['settings'] = "error";
        header("location: settings.php");
    }
} else {
    header("location: settings.php");
}
?>
