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

        <title>Beacons | Home</title>

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

        <div id="home-container">

            <?php

            // check if admin tried to edit employee and success

            if (isset($_SESSION['edit'])) {

                if ($_SESSION['edit'] === "successful") {

                    echo '<p class="success-msg">Employee updated sucessfully</p>';

                    $_SESSION['edit'] = "null";

                }

            }



            // check if admin tried to delete employee and succeed or failed

            if (isset($_SESSION['delete'])) {

                if ($_SESSION['delete'] === "successful") {

                    echo '<p class="success-msg">Employee deleted successfully</p>';

                    $_SESSION['delete'] = "null";

                } else if ($_SESSION['delete'] === "error") {

                    echo '<p class="error-msg">Employee deletetion failed</p>';

                    $_SESSION['delete'] = "null";

                }

            }



            // check if admin tried to edit settings and success

            if (isset($_SESSION['settings'])) {

                if ($_SESSION['settings'] === "successful") {

                    echo '<p class="success-msg">Settings saved sucessfully</p>';

                    $_SESSION['settings'] = "null";

                }

            }

            ?>



            <table class="f-table" border="0">

                <tr>

                    <th style="width: 6%">ID</th>

                    <th style="width: 25%">Name</th>

                    <th style="width: 19%">Password</th>

                    <th style="width: 30%">Beacon ID</th>
                    
                    <th style="width: 6%">Beacon Major</th>

                    <th style="width: 6%">Beacon Minor</th>

                    <th style="width: 4%">Edit</th>

                    <th style="width: 4%">Delete</th>

                </tr>



                <?php

                // load employees

                $sql = "SELECT * FROM employees";

                $result = mysqli_query($con, $sql);

                mysqli_close($con);



                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_array($result)) {

                        // output employees

                        echo '<tr>';

                        echo '<td><a title="View Report" href="report.php?id=' . $row['id'] . '" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';">' . $row['id'] . '</a></td>';

                        echo '<td style="text-align: left"><a title="View Report" href="report.php?id=' . $row['id'] . '" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';">' . $row['name'] . '</a></td>';

                        echo '<td><input type="password"'

                        . ' onfocus="setAttribute(\'type\', \'text\');"'

                        . ' onblur="setAttribute(\'type\', \'password\');"'

                        . ' value="' . $row['password'] . '" readonly /></td>';

                        echo '<td>' . $row['beacon_id'] . '</td>';
                        
                        echo '<td>' . $row['beacon_major'] . '</td>';

                        echo '<td>' . $row['beacon_minor'] . '</td>';

                        echo '<td><a title="Edit Employee" href="edit.php?id=' . $row['id'] . '"><img src="images/edit.png" style="width: 25px; height: 25px" /></a></td>';

                        echo '<td><a title="Delete Employee" onclick="return confirm(\'Delete Employee?\')" href="delete.php?id=' . $row['id'] . '"><img src="images/delete.png" style="width: 25px; height: 25px" /></a></td>';

                        echo '</tr>';

                    }

                }

                ?>

            </table>

        </div>

    </body>

</html>

