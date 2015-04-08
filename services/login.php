<?php

require_once '../inc/config.php';

// change content type to json
header('Content-type: application/json');

if (isset($_POST['name']) && isset($_POST['password'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];

    // validate inputs
    if (empty($name) || empty($password)) {
        echo "error";
        return;
    }

    // strip inputs
    $name = strip_tags($name);
    $password = strip_tags($password);

    // prepare sql statement
    $sql = "SELECT * FROM employees WHERE name = '" . mysqli_real_escape_string($con, $name) . "'
        AND password = '" . mysqli_real_escape_string($con, $password) . "' ";

    // execute query
    $result = mysqli_query($con, $sql);
    mysqli_close($con);

    // check query result
    if (mysqli_num_rows($result) > 0) {
        // exists
        while ($row = mysqli_fetch_array($result)) {
            $employee_info = array(
                'id' => intval($row['id']),
                'name' => $row['name'],
                'password' => $row['password'],
                'beacon_id' => $row['beacon_id'],
                'beacon_major' => intval($row['beacon_major']),
                'beacon_minor' => intval($row['beacon_minor'])
            );
        }
        echo json_encode($employee_info);
    } else {
        // not exists 
        echo "error";
    }
} else {
    echo "error";
}
?>