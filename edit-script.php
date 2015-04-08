<?php

require_once 'inc/config.php';



// check if admin is not logged in, redirect it to login

if (!isset($_SESSION['activeAdmin'])) {

    header("location: index.php");

    return;
}



if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['password']) &&
        isset($_POST['beacon_id']) && isset($_POST['beacon_major']) && isset($_POST['beacon_minor'])) {

    $id = $_POST['id'];

    $name = $_POST['name'];

    $password = $_POST['password'];

    $beacon_id = $_POST['beacon_id'];

    $beacon_major = $_POST['beacon_major'];

    $beacon_minor = $_POST['beacon_minor'];



    // validate inputs

    if (empty($id)) {

        header("location: home.php");

        return;
    }



    // validate inputs

    if (empty($name) || empty($password) || empty($beacon_id) || empty($beacon_major) || empty($beacon_minor)) {

        $_SESSION['edit'] = "error";

        header("location: edit.php");

        return;
    }



    // strip inputs

    $id = strip_tags($id);

    $name = strip_tags($name);

    $password = strip_tags($password);

    $beacon_id = strip_tags($beacon_id);

    $beacon_major = strip_tags($beacon_major);

    $beacon_minor = strip_tags($beacon_minor);



    // get old name

    $sql1 = "SELECT name, time_stamp FROM employees WHERE id = " . mysqli_real_escape_string($con, $id);

    $result1 = mysqli_query($con, $sql1);

    $old_name = "";
    $time_stamp = "";

    if (mysqli_num_rows($result1) > 0) {

        while ($row = mysqli_fetch_array($result1)) {

            $old_name = $row['name'];
            $time_stamp = $row['time_stamp'];
        }
    }



    // check if admin updated username

    if ($old_name !== $name) {

        // admin updated username
        // check if username exists in DB

        $sql2 = "SELECT id FROM employees WHERE name = '" . mysqli_real_escape_string($con, $name) . "'";

        $result2 = mysqli_query($con, $sql2);

        if (mysqli_num_rows($result2) > 0) {

            // --username exists in DB--
            // close DB connection

            mysqli_close($con);



            // goto edit with name exists flag

            $_SESSION['edit'] = "name";

            header("location: edit.php?id=" . $id);

            return;
        }
    }



    // --here, username not exists in DB--
    // --edit it--
    // prepare edit sql statement
    $sql3 = "UPDATE employees SET name = '" . mysqli_real_escape_string($con, $name) . "', "
            . "password = '" . mysqli_real_escape_string($con, $password) . "', "
            . "beacon_id = '" . mysqli_real_escape_string($con, $beacon_id) . "', "
            . "beacon_major = " . mysqli_real_escape_string($con, $beacon_major) . ", "
            . "beacon_minor = " . mysqli_real_escape_string($con, $beacon_minor) . " ";

    if (!empty($time_stamp)) {
        $sql3 .= ",time_stamp = '$time_stamp' ";
    }

    $sql3 .= "WHERE id = " . mysqli_real_escape_string($con, $id);



    $result3 = mysqli_query($con, $sql3);

    mysqli_close($con);



    // check edit query result

    if ($result3) {

        // goto home with successful edit flag

        $_SESSION['edit'] = "successful";

        header("location: home.php");
    } else {

        // if failed goto edit with edit flag error

        $_SESSION['edit'] = "error";

        header("location: edit.php?id=" . $id);
    }
} else {

    header("location: home.php");
}
?>

