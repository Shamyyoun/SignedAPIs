<?php

require_once 'inc/config.php';

// check if admin is not logged in, redirect it to login
if (!isset($_SESSION['activeAdmin'])) {
    header("location: index.php");
    return;
}

if (isset($_GET['em_id']) && (isset($_GET['log_id']) || isset($_GET['day']))) {
    $employee_id = $_GET['em_id'];
    $log_id = "";
    $day = "";
    if (isset($_GET['log_id'])) {
        $log_id = $_GET['log_id'];
    }
    if (isset($_GET['day'])) {
        $day = $_GET['day'];
    }

    // validate inputs
    if (empty($employee_id)) {
        header("location: home.php");
        return;
    }

    if (!empty($log_id)) {
        if (empty($log_id)) {
            $_SESSION['holiday'] = "error";
            header("location: report.php?id=" . $employee_id);
            return;
        }
    }
    if (!empty($day)) {
        if (empty($day)) {
            $_SESSION['holiday'] = "error";
            header("location: report.php?id=" . $employee_id);
            return;
        } else {
            // format day
            $day = str_replace('/', '-', $day);
            $day = date("Y-m-d", strtotime($day));
        }
    }

    // strip inputs
    $employee_id = strip_tags($employee_id);
    if (!empty($log_id)) {
        $log_id = strip_tags($log_id);
    }
    if (!empty($day)) {
        $day = strip_tags($day);
    }

    // prepare sql statement
    $sql1 = "";
    $sql2 = "";
    if (!empty($log_id)) {
        $sql1 = "UPDATE employees_log SET holiday = 1, login_time = '', logout_time = '' WHERE id = " . mysqli_real_escape_string($con, $log_id) . " AND employee_id = " . mysqli_real_escape_string($con, $employee_id);
    }
    if (!empty($day)) {
        echo 'DAY: ' . $day . '<br>';
        $sql1 = "DELETE FROM employees_log WHERE day = '" . mysqli_real_escape_string($con, $day) . "' AND employee_id = " . mysqli_real_escape_string($con, $employee_id);
        $sql2 = "INSERT INTO employees_log (day, holiday, employee_id) VALUES('" . mysqli_real_escape_string($con, $day) . "', 1, " . mysqli_real_escape_string($con, $employee_id) . ")";
    }

    // execute query
    if (!empty($log_id)) {
        $result = mysqli_query($con, $sql1);
    }
    if (!empty($day)) {
        mysqli_query($con, $sql1);
        $result = mysqli_query($con, $sql2);
    }
    mysqli_close($con);

    // check query result
    if ($result) {
        // goto report with successful holiday flag
        $_SESSION['holiday'] = "successful";
        header("location: report.php?id=" . $employee_id);
    } else {
        // if failed goto report with holiday flag error
        $_SESSION['holiday'] = "error";
        header("location: report.php?id=" . $employee_id);
    }
} else {
    header("location: home.php");
}
?>
