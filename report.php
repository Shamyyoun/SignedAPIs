<?php
require_once 'inc/config.php';
require_once './inc/functions.php';

// check if admin is not logged in, redirect it to login
if (!isset($_SESSION['activeAdmin'])) {
    header("location: index.php");
    return;
}
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Beacons | Employee Report</title>
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
        <div id="report-container">
            <?php
            // check if admin tried to make holiday and succeed or failed
            if (isset($_SESSION['holiday'])) {
                if ($_SESSION['holiday'] === "successful") {
                    echo '<p class="success-msg">Holiday marked sucessfully</p>';
                    $_SESSION['holiday'] = "null";
                } else if ($_SESSION['holiday'] === "error") {
                    echo '<p class="error-msg">Error in marking the holiday!</p>';
                    $_SESSION['holiday'] = "null";
                }
            }

            // check if admin tried to clear all logs and succeed or failed
            if (isset($_SESSION['clear_logs'])) {
                if ($_SESSION['clear_logs'] === "successful") {
                    echo '<p class="success-msg">All log cleared sucessfully</p>';
                    $_SESSION['clear_logs'] = "null";
                } else if ($_SESSION['clear_logs'] === "error") {
                    echo '<p class="error-msg">Can\'t clear all logs</p>';
                    $_SESSION['clear_logs'] = "null";
                }
            }
            ?>

            <?php
            if (isset($_GET['id'])) {
                $id = $_GET['id'];

                // validate inputs
                if (empty($id)) {
                    header("location: home.php");
                    return;
                }

                // strip inputs
                $id = strip_tags($id);

                // check if specific date is passed
                if (isset($_GET['date'])) {
                    $m_date = $_GET['date'];
                    // validate passed arguments
                    if (!empty($m_date)) {
                        // strip inputs
                        $m_date = strip_tags($m_date);
                    } else {
                        $m_date = date("Y-m");
                    }
                } else {
                    $m_date = date("Y-m");
                }

                // load employee info
                $sql1 = "SELECT name, time_stamp FROM employees WHERE id = " . $id;
                $result1 = mysqli_query($con, $sql1);

                $time_stamp = "";
                if (mysqli_num_rows($result1) > 0) {
                    while ($row = mysqli_fetch_array($result1)) {
                        $time_stamp = $row['time_stamp'];
                        echo '<p class="container-header">' . $row['name'] . " | " . date("Y F", strtotime($m_date)) . '\'s Report</p>';
                    }
                }
            } else {
                header("location: home.php");
            }
            ?>

            <table class="f-table" border="0">
                <tr>
                    <th style="width: 30%">Day</th>
                    <th style="width: 30%">Login Time</th>
                    <th style="width: 30%">Logout Time</th>
                    <th style="width: 10%">Holiday</th>
                </tr>

                <?php
                // format time stamp
                $time_stamp = date("Y-m-d", strtotime($time_stamp));

                // create suitable month days array
                $first_day = date("Y-m-01", strtotime($m_date));
                $last_day = date("Y-m-t", strtotime($m_date));
                if ($time_stamp > $first_day) {
                    $first_day = $time_stamp;
                }
                if ($last_day > date('Y-m-d')) {
                    $last_day = date('Y-m-d');
                }
                $month_days = listDates($first_day, $last_day);

                // load employee logs from database
                $sql2 = "SELECT * FROM employees_log WHERE employee_id = " . $id
                        . " AND (day >= '" . $first_day . "' AND day <= '" . $last_day . "') ORDER BY day ASC";
                $result2 = mysqli_query($con, $sql2);

                // fetch employees logs in php array
                $rows = array();
                if (mysqli_num_rows($result2) > 0) {
                    while ($row = mysqli_fetch_array($result2)) {
                        $rows[] = $row;
                    }
                }

                // get login and logout times from DB
                $sql3 = "SELECT * FROM settings";
                $result3 = mysqli_query($con, $sql3);
                mysqli_close($con);

                $start_time = "";
                $end_time = "";
                if (mysqli_num_rows($result3) > 0) {
                    while ($row = mysqli_fetch_array($result3)) {
                        $start_time = $row['start_time'];
                        $end_time = $row['end_time'];
                    }
                } else {
                    $start_time = "08:00:00";
                    $end_time = "14:00:00";
                }

                // check month days length
                if (count($month_days) == 0) {
                    // output no data available row
                    echo '<tr>';
                    echo '<td colspan="4" style="font-weight: bold;">No data available</td>';
                    echo '</tr>';
                } else {
                    // loop for week days to display all month report
                    $non_complete_days = 0;
                    $missed_days = 0;
                    $rows_index = 0;
                    for ($month_days_index = 0; $month_days_index < count($month_days); $month_days_index++) {
                        $month_day = $month_days[$month_days_index];

                        // check if this day has log in the DB
                        $db_day = "";
                        if (count($rows) > $rows_index) {
                            $db_day = $rows[$rows_index]['day'];
                        }

                        // check if this day is weekend
                        if (date('w', strtotime($month_day)) == 5) {
                            // output gray weekend day
                            echo '<tr>';
                            echo '<td style="background-color: #EFEFEF">' . date("D, d/m/Y", strtotime($month_day)) . '</td>';
                            echo '<td style="background-color: #EFEFEF">--</td>';
                            echo '<td style="background-color: #EFEFEF">--</td>';
                            echo '<td style="background-color: #EFEFEF">--</td>';
                            echo '</tr>';

                            if ($month_day == $db_day) {
                                // if day exists in DB, increment index
                                $rows_index++;
                            }
                        } else if ($month_day == $db_day) {
                            // day exists in DB
                            if ($rows[$rows_index]['holiday'] == 1) {
                                // this day is holiday
                                // output green holiday
                                echo '<tr>';
                                echo '<td style="background-color: #98F0A6">' . date("D, d/m/Y", strtotime($month_day)) . '</td>';
                                echo '<td style="background-color: #98F0A6">--</td>';
                                echo '<td style="background-color: #98F0A6">--</td>';
                                echo '<td style="background-color: #98F0A6">--</td>';
                                echo '</tr>';
                            } else {
                                // not weekend or holiday
                                // display it in suitable way (yellow if not complete && white if complete)
                                $login_time = $rows[$rows_index]['login_time'];
                                $logout_time = $rows[$rows_index]['logout_time'];

                                // prepare suitable style according to login and logout times
                                $style = "";
                                if ($login_time > $start_time || $logout_time < $end_time || empty($logout_time)) {
                                    $style = " style=\"background-color: #FFFEA3\"";
                                    $non_complete_days++;
                                }

                                // output employee logs
                                echo '<tr>';
                                echo '<td' . $style . '>' . date("D, d/m/Y", strtotime($db_day)) . '</td>';
                                echo '<td' . $style . '>' . date("h:i a", strtotime($login_time)) . '</td>';

                                // check if logout time is null
                                if (!empty($logout_time)) {
                                    echo '<td' . $style . '>' . date("h:i a", strtotime($logout_time)) . '</td>';
                                } else {
                                    echo '<td' . $style . '>--</td>';
                                }
                                echo '<td' . $style . '>' . '<a onclick="return confirm(\'Mark this day as Holiday?\')" title="Make Holiday" href="holiday.php?em_id=' . $id . '&log_id=' . $rows[$rows_index]['id'] . '"><img src="images/holiday.png" style="width: 25px; height: 25px" /></a>' . '</td>';
                                echo '</tr>';
                            }

                            // increment rows
                            $rows_index++;
                        } else {
                            // not exists >> output empty log with this day
                            echo '<tr>';
                            echo '<td style="background-color: #FFC3C3">' . date("D, d/m/Y", strtotime($month_day)) . '</td>';
                            echo '<td style="background-color: #FFC3C3">--</td>';
                            echo '<td style="background-color: #FFC3C3">--</td>';
                            echo '<td style="background-color: #FFC3C3">' . '<a onclick="return confirm(\'Mark this day as Holiday?\')" title="Make Holiday" href="holiday.php?em_id=' . $id . '&day=' . date("d/m/Y", strtotime($month_day)) . '"><img src="images/holiday.png" style="width: 25px; height: 25px" /></a>' . '</td>';
                            echo '</tr>';
                            $missed_days++;
                        }
                    }
                }
                ?>
            </table>

            <?php
            if (isset($non_complete_days) && isset($missed_days)) {
                echo '<p class = "container-header">Non complete days: ' . $non_complete_days;
                echo nl2br("\nMissed days: " . $missed_days . '</p>');
            }
            ?>

            <div>
                <?php
                // prepare next date
                $next_date = strtotime("+1 month", strtotime($m_date));
                $next_date = date("Y-m", $next_date);

                // check if next month is lower than or equal to current calendar month
                if ($next_date <= date("Y-m")) {
                    // display next button
                    echo '<a title="Next" style="float: right; margin-top: 20px" href="report.php?id=' . $id . '&date=' . $next_date . '"><img src="images/next.png" style="width: 54px; height: 54px;" /></a>';
                }

                // prepare previous date
                $prev_date = strtotime("-1 month", strtotime($m_date));
                $prev_date = date("Y-m", $prev_date);

                // check if previous date is higher than or equal to time_stamp
                if ($prev_date >= date("Y-m", strtotime($time_stamp))) {
                    // display previous button
                    echo '<a title="Previous" style="float: left; margin-top: 20px" href="report.php?id=' . $id . '&date=' . $prev_date . '"><img src="images/prev.png" style="width: 54px; height: 54px;" /></a>';
                }
                ?>
            </div>

            <div style="width: 50%; margin: 2% auto;">
                <a href="home.php" class="gray-button" style="float: right; width: 38%; margin: 1%; font-size: 18px; font-weight: bold">Home</a>
                <a onclick="return confirm('Clear All Logs?!!')" href="clear-logs.php?em_id=<?php echo $id; ?>" class="gray-button" style="float: right; width: 38%; margin: 1%; font-size: 18px; font-weight: bold">Clear All </a>
                <div style="clear: both"></div>
            </div>

        </div>
    </body>
</html>
