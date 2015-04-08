<?php

require_once '../inc/config.php';

require_once '../inc/functions.php';



// change content type to json

header('Content-type: application/json');



if (isset($_POST['name']) && isset($_POST['password']) && isset($_POST['id'])) {



    $name = $_POST['name'];

    $password = $_POST['password'];

    $id = $_POST['id'];

    $month = date("Y-m");



    // validate inputs

    if (empty($name) || empty($password) || empty($id)) {

        echo "error";

        return;
    }



    // strip inputs

    $name = strip_tags($name);

    $password = strip_tags($password);

    $id = strip_tags($id);





    // ---CHECK IF EMPLOYEE EXISTS IN DB---
    // prepare sql statement

    $sql1 = "SELECT * FROM employees WHERE name = '" . mysqli_real_escape_string($con, $name) . "'

        AND password = '" . mysqli_real_escape_string($con, $password) . "' ";



    // execute query

    $result1 = mysqli_query($con, $sql1);



    // check query result

    if (mysqli_num_rows($result1) > 0) {

        // --exists--
        // get employee time_stamp

        while ($row1 = mysqli_fetch_array($result1)) {

            $time_stamp = $row1['time_stamp'];

            $time_stamp = date("Y-m-d", strtotime($time_stamp));
        }



        // create suitable month days array

        $first_day = date("Y-m-01", strtotime($month));

        $last_day = date("Y-m-t", strtotime($month));

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



        // loop for month days to display all month report

        $report = array();

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

                //--weekend--

                $report[] = array(
                    'day' => date("d/m/Y", strtotime($month_day)),
                    'day_name' => date("D", strtotime($month_day)),
                    'login_time' => "",
                    'logout_time' => "",
                    'weekend' => true
                );



                if ($month_day == $db_day) {

                    // if day exists in DB, increment index

                    $rows_index++;
                }
            } else if ($month_day == $db_day) {

                // day exists in DB

                if ($rows[$rows_index]['holiday'] == 1) {

                    // --holiday--

                    $report[] = array(
                        'day' => date("d/m/Y", strtotime($month_day)),
                        'day_name' => date("D", strtotime($month_day)),
                        'login_time' => "",
                        'logout_time' => "",
                        'holiday' => true
                    );
                } else {

                    // --not weekend or holiday--
                    // format times

                    $login_time = $rows[$rows_index]['login_time'];

                    $logout_time = $rows[$rows_index]['logout_time'];



                    if (!empty($login_time)) {

                        $login_time = date("h:i a", strtotime($login_time));
                    } else {
                        $login_time = "";
                    }

                    if (!empty($logout_time)) {

                        $logout_time = date("h:i a", strtotime($logout_time));
                    } else {
                        $logout_time = "";
                    }



                    // check if day not complete

                    if ($login_time > $start_time || $logout_time < $end_time || empty($logout_time)) {

                        $complete = false;
                    } else {

                        $complete = true;
                    }



                    $report[] = array(
                        'day' => date("d/m/Y", strtotime($db_day)),
                        'day_name' => date("D", strtotime($db_day)),
                        'login_time' => $login_time,
                        'logout_time' => $logout_time,
                        'complete' => $complete
                    );
                }



                // increment rows

                $rows_index++;
            } else {

                // --not exists--

                $report[] = array(
                    'day' => date("d/m/Y", strtotime($month_day)),
                    'day_name' => date("D", strtotime($month_day)),
                    'login_time' => "",
                    'logout_time' => "",
                    'missed' => true,
                );
            }
        }



        echo json_encode($report);
    } else {

        // not exists 

        echo "error";
    }





    mysqli_close($con);
} else {

    echo "error";
}
?>