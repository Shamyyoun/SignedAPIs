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
        <title>Beacons | Settings</title>
        <link href="css/style.css" rel='stylesheet' type='text/css' />
        <link rel='stylesheet' type='text/css'href='css/timepicki.css'/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script type="application/x-javascript">
            addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);function hideURLbar(){ window.scrollTo(0,1); }
        </script>
    </head>

    <body>
        <!-- HEADER -->
        <?php require_once 'inc/header.php'; ?>

        <!-- CONTAINER -->
        <div id="settings-container">
            <?php
            
            // check if add failed
            if (isset($_SESSION['settings'])) {
                if ($_SESSION['settings'] === "error") {
                    echo '<p class="error-msg">Error in saving settings!</p>';
                    $_SESSION['settings'] = "null";
                } else if ($_SESSION['settings'] === "time_error") {
                    echo '<p class="error-msg">End must be higher than Start!</p>';
                    $_SESSION['settings'] = "null";
                }
            }
            ?>

            <form action="settings-script.php" method="post" class="white-container" style="padding-top: 45px;">
                <p style="color: #49494a; font-size: 30px; font-weight: bold; text-align: center; margin-top: 0px;">Times</p>
                <table border="0" style="color: #49494a; font-size: 18px;">
                    <tr>
                        <td valign="center" style="text-align: center; padding-bottom: 25px; width: 30%">Start Time</td>
                        <td style="padding-bottom: 25px; width: 70%"><input type="text" name="start_time" id="start_time" style="margin: 0;" value="<?php
                            // get saved times from DB
                            $sql1 = "SELECT * FROM settings";
                            $result1 = mysqli_query($con, $sql1);
                            mysqli_close($con);

                            // check query result
                            $saved_start_time = "";
                            $saved_end_time = "";
                            if (mysqli_num_rows($result1) > 0) {
                                // saved settings exists >> output them
                                while ($row1 = mysqli_fetch_array($result1)) {
                                    $saved_start_time = date("h:i a", strtotime($row1['start_time']));
                                    $saved_end_time = date("h:i a", strtotime($row1['end_time']));
                                    echo $saved_start_time;
                                }
                            } else {
                                // not exists >> set defaults
                                $saved_start_time = "08:00 am";
                                $saved_end_time = "02:00 pm";
                                echo $saved_start_time;
                            }
                            ?>" /></td>
                    </tr>
                    <tr>
                        <td valign="center" style="text-align: center; padding-bottom: 25px; width: 30%">End Time</td>
                        <td style="padding-bottom: 25px; width: 70%"><input type="text" name="end_time" id="end_time" style="margin: 0;" value="<?php echo $saved_end_time; ?>" /></td>
                    </tr>
                </table>
                <input type="submit" value="Save" class="gray-button" style="float: left; width: 48%;; font-size: 18px; font-weight: bold"/>
                <a href="home.php" class="gray-button" style="float: right; width: 40%; font-size: 18px; font-weight: bold">Cancel </a>
                <div style="clear: both"></div>
            </form>
        </div>

        <!-- Load scripts at the end of page to load page faster -->
        <script type='text/javascript'src='js/jquery.min.js'></script>
        <script type='text/javascript'src='js/timepicki.js'></script>
        <script>
            $('#start_time').timepicki();
            $('#end_time').timepicki();
        </script>
    </body>
</html>