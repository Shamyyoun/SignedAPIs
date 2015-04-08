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

        <title>Beacons | Edit Employee</title>

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

        <div id="edit-container">

            <?php

            // check if edit failed

            if (isset($_SESSION['edit'])) {

                if ($_SESSION['edit'] === "error") {

                    echo '<p class="error-msg">Edit failed!</p>';

                    $_SESSION['edit'] = "null";

                } else if ($_SESSION['edit'] === "name") {

                    echo '<p class="error-msg">Name exists in DB!</p>';

                    $_SESSION['edit'] = "null";

                }

            }

            ?>



            <form action="edit-script.php" method="post" class="white-container">

                <input type="hidden" name="id" value="<?php

                // check if id sent

                if (isset($_GET['id'])) {

                    // validate and prepare id

                    $id = strip_tags($_GET['id']);

                    $id = mysqli_real_escape_string($con, $id);



                    echo $id;

                } else {

                    header("location: home.php");

                }

                ?>" />



                <input type="text" name="name" placeholder="Name" value="<?php

                // load employee info

                $sql = "SELECT * FROM employees WHERE id = " . $id;

                $result = mysqli_query($con, $sql);

                mysqli_close($con);



                $name = "";

                $password = "";

                $beacon_major = "";

                $beacon_minor = "";

                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_array($result)) {

                        // fetch employee info

                        $name = $row['name'];

                        $password = $row['password'];
                        
                        $beacon_id = $row['beacon_id'];

                        $beacon_major = $row['beacon_major'];

                        $beacon_minor = $row['beacon_minor'];



                        // output employee name

                        echo $name;

                    }

                }

                ?>" />

                <input type="password" name="password" placeholder="Password" onfocus="setAttribute('type', 'text');" onblur="setAttribute('type', 'password');" value="<?php echo $password; ?>" />

                <input type="text" name="beacon_id" placeholder="Beacon ID" value="<?php echo $beacon_id; ?>" />

                <input type="text" name="beacon_major" placeholder="Beacon Major" value="<?php echo $beacon_major; ?>" />

                <input type="text" name="beacon_minor" placeholder="Beacon Minor" value="<?php echo $beacon_minor; ?>" />

                <input type="submit" value="Save" class="gray-button" style="float: left; width: 48%;; font-size: 18px; font-weight: bold"/>

                <a href="home.php" class="gray-button" style="float: right; width: 40%; font-size: 18px; font-weight: bold">Cancel </a>

                <div style="clear: both"></div>

            </form>

        </div>

    </body>

</html>