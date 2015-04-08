<?php
require_once 'inc/config.php';



// check if admin is not logged in, redirect it to login

if (!isset($_SESSION['activeAdmin'])) {

    header("location: index.php");

    return;
}
?>



<html>

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <title>Beacons | Add Employee</title>

        <link href="css/style.css" rel='stylesheet' type='text/css' />

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script type="application/x-javascript">

            addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);function hideURLbar(){ window.scrollTo(0,1); }

        </script>

    </head>



    <body>

        <!-- HEADER -->

<?php require_once 'inc/header.php'; ?>



        <!-- CONTAINER -->

        <div id="add-container">

<?php
// check if add failed

if (isset($_SESSION['add'])) {

    if ($_SESSION['add'] === "successful") {

        echo '<p class="success-msg">Employee added sucessfully</p>';

        $_SESSION['add'] = "null";
    } else if ($_SESSION['add'] === "name") {

        echo '<p class="error-msg">Name exists in DB!</p>';

        $_SESSION['add'] = "null";
    } else if ($_SESSION['add'] === "error") {

        echo '<p class="error-msg">Add employee failed!</p>';

        $_SESSION['add'] = "null";
    }
}
?>



            <form action="add-script.php" method="post" class="white-container">

                <input type="text" name="name" placeholder="Name" />

                <input type="text" name="password" placeholder="Password" />
                
                <input type="text" name="beacon_id" placeholder="Beacon ID" />

                <input type="text" name="beacon_major" placeholder="Beacon Major" />

                <input type="text" name="beacon_minor" placeholder="Beacon Minor" />

                <input type="submit" value="Save" class="gray-button" style="float: left; width: 48%;; font-size: 18px; font-weight: bold"/>

                <a href="home.php" class="gray-button" style="float: right; width: 40%; font-size: 18px; font-weight: bold">Cancel </a>

                <div style="clear: both"></div>

            </form>

        </div>

    </body>

</html>