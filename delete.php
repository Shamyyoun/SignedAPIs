<?php

require_once 'inc/config.php';

// check if admin is not logged in, redirect it to login
if (!isset($_SESSION['activeAdmin'])) {
    header("location: index.php");
    return;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // validate inputs
    if (empty($id)) {
        $_SESSION['delete'] = "error";
        header("location: home.php");
        return;
    }
    
    // strip inputs
    $id = strip_tags($id);

    // prepare sql statement
    $sql = "DELETE FROM employees WHERE id = " . mysqli_real_escape_string($con, $id);

    // execute query
    $result = mysqli_query($con, $sql);
    mysqli_close($con);

    // check query result
    if ($result) {
        // goto home with successful delete flag
        $_SESSION['delete'] = "successful";
        header("location: home.php");
    } else {
        // if failed goto edit with edit flag error
        $_SESSION['delete'] = "error";
        header("location: home.php");
    }
} else {
    header("location: home.php");
}
?>
