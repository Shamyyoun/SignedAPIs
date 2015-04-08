<?php



require_once 'inc/config.php';



// check if admin is not logged in, redirect it to login

if (!isset($_SESSION['activeAdmin'])) {

    header("location: index.php");

    return;

}



if (isset($_POST['name']) && isset($_POST['password']) &&

        isset($_POST['beacon_id']) && isset($_POST['beacon_major']) && isset($_POST['beacon_minor'])) {

    $name = $_POST['name'];

    $password = $_POST['password'];
    
    $beacon_id = $_POST['beacon_id'];

    $beacon_major = $_POST['beacon_major'];

    $beacon_minor = $_POST['beacon_minor'];



    // validate inputs

    if (empty($name) || empty($password) || empty($beacon_id) || empty($beacon_major) || empty($beacon_minor)) {

        $_SESSION['add'] = "error";

        header("location: add.php");

        return;

    }



    // strip inputs

    $name = strip_tags($name);

    $password = strip_tags($password);
    
    $beacon_id = strip_tags($beacon_id);

    $beacon_major = strip_tags($beacon_major);

    $beacon_minor = strip_tags($beacon_minor);



    // check if username exists in DB

    $sql1 = "SELECT id FROM employees WHERE name = '" . mysqli_real_escape_string($con, $name) . "'";

    $result1 = mysqli_query($con, $sql1);

    echo $sql1;

    if (mysqli_num_rows($result1) > 0) {

        // --username exists in DB--

        // close DB connection

        mysqli_close($con);



        // goto add with name exists flag

        $_SESSION['add'] = "name";

        header("location: add.php");

        return;

    } else {

        // --user not exists in DB--

        // add it

        $sql2 = "INSERT INTO employees (name, password, beacon_id, beacon_major, beacon_minor, time_stamp)"

                . " VALUES ('" . mysqli_real_escape_string($con, $name) . "',"

                . " '" . mysqli_real_escape_string($con, $password) . "',"
                
                . " '" . mysqli_real_escape_string($con, $beacon_id) . "',"

                . " " . mysqli_real_escape_string($con, $beacon_major) . ","

                . " " . mysqli_real_escape_string($con, $beacon_minor) . ", NOW())";



        $result2 = mysqli_query($con, $sql2);

        mysqli_close($con);



        // check query result

        if ($result2) {

            // goto add with successful add flag

            $_SESSION['add'] = "successful";

            header("location: add.php");

        } else {

            // if failed goto edit with add flag error

            $_SESSION['add'] = "error";

            header("location: add.php");

        }

    }

} else {

    header("location: add.php");

}

?>

