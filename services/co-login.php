<?php



require_once '../inc/config.php';



// change content type to json

header('Content-type: application/json');



if (isset($_POST['name']) && isset($_POST['password']) && isset($_POST['id']) && isset($_POST['day']) && isset($_POST['login_time'])) {



    $name = $_POST['name'];

    $password = $_POST['password'];

    $id = $_POST['id'];

    $day = $_POST['day'];

    $login_time = $_POST['login_time'];



    // validate inputs

    if (empty($name) || empty($password) || empty($id) || empty($day) || empty($login_time)) {

        echo "error";

        return;

    }



    // strip inputs

    $name = strip_tags($name);

    $password = strip_tags($password);

    $id = strip_tags($id);

    $day = strip_tags($day);

    $login_time = strip_tags($login_time);





    // ---CHECK IF EMPLOYEE EXISTS IN DB---

    // prepare sql statement

    $sql1 = "SELECT * FROM employees WHERE name = '" . mysqli_real_escape_string($con, $name) . "'

        AND password = '" . mysqli_real_escape_string($con, $password) . "' ";



    // execute query

    $result1 = mysqli_query($con, $sql1);



    // check query result

    if (mysqli_num_rows($result1) > 0) {

        // --exists--

        // check if day exists in DB

        $sql2 = "SELECT id FROM employees_log WHERE day = '" . mysqli_real_escape_string($con, $day) . "'"

                . " AND employee_id = " . mysqli_real_escape_string($con, $id);

        $result2 = mysqli_query($con, $sql2);

        if (mysqli_num_rows($result2) > 0) {

            // --day exists in DB--

            // get day id

            while ($row2 = mysqli_fetch_array($result2)) {

                $day_id = $row2['id'];

            }



            // prepare update sql statment

            $sql3 = "UPDATE employees_log SET login_time = '" . mysqli_real_escape_string($con, $login_time) . "' WHERE id = " . $day_id;

        } else {

            // --day not exists--

            // prepare add sql statement

            $sql3 = "INSERT INTO employees_log (day, login_time, employee_id)"

                    . " VALUES ('" . mysqli_real_escape_string($con, $day) . "',"

                    . " '" . mysqli_real_escape_string($con, $login_time) . "',"

                    . " " . mysqli_real_escape_string($con, $id) . ")";

        }



        // execute log sql statement

        $result3 = mysqli_query($con, $sql3);



        // check log query result

        if ($result3) {

            echo 'success';

        } else {

            echo 'error';

        }

    } else {

        // not exists 

        echo "error";

    }





    mysqli_close($con);

} else {

    echo "error";

}

?>