<?php

require_once 'inc/config.php';

// check if admin is not logged in, redirect it to login
if (!isset($_SESSION['activeAdmin'])) {
    header("location: index.php");
    return;
}

if (isset($_GET['em_id'])) {
    $employee_id = $_GET['em_id'];

    // validate inputs
    if (empty($employee_id)) {
        header("location: home.php");
        return;
    }

    // strip inputs
    $employee_id = strip_tags($employee_id);

    // prepare delete sql statement
    $sql = "DELETE FROM employees_log WHERE employee_id = " . mysqli_real_escape_string($con, $employee_id);

    // execute delete query
    $result = mysqli_query($con, $sql);

    // prepare update employee sql statement
    $sql2 = "UPDATE employees SET time_stamp = '" . mysqli_real_escape_string($con, date("Y-m-d H:i:s", strtotime("+1 day"))) . "' WHERE id = " . mysqli_real_escape_string($con, $employee_id);

    // execute update employee query
    $result2 = mysqli_query($con, $sql2);

    // close connection
    mysqli_close($con);

    // check query result
    if ($result && $result2) {
        // goto report with successful clear flag
        $_SESSION['clear_logs'] = "successful";
        header("location: report.php?id=" . $employee_id);
    } else {
        // if failed goto report with clear flag error
        $_SESSION['clear_logs'] = "error";
        header("location: report.php?id=" . $employee_id);
    }
} else {
    header("location: home.php");
}
?>
