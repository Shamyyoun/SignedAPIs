<?php

require_once 'inc/config.php';

// check if admin is not logged in, redirect it to login
if (isset($_SESSION['activeAdmin'])) {
    header("location: home.php");
    return;
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // validate inputs
    if (empty($username) || empty($password)) {
        $_SESSION['login'] = "error";
        header("location: index.php");
        return;
    }
    
    // strip inputs
    $username = strip_tags($username);
    $password = strip_tags($password);

    // prepare sql statement
    $sql = "SELECT * FROM admins WHERE username = '" . mysqli_real_escape_string($con, $username) . "'
        AND password = '" . mysqli_real_escape_string($con, $password) . "' ";

    // execute query
    $result = mysqli_query($con, $sql);
    mysqli_close($con);

    // check query result
    if (mysqli_num_rows($result) > 0) {
        // if exists >> goto home
        $_SESSION['activeAdmin'] = mysqli_fetch_array($result);
        header("location: home.php");
    } else {
        // if not exists >> continue to login with error flag
        $_SESSION['login'] = "error";
        header("location: index.php");
    }
} else {
    header("location: index.php");
}
?>
