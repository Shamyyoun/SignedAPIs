<?php
require_once 'inc/config.php';

// check if admin is logged in, redirect it to home
if (isset($_SESSION['activeAdmin'])) {
    header("location: home.php");
    return;
}
?>

<html>
    <head>
        <title>Beacons</title>
        <meta charset="utf-8">
        <link href="css/style.css" rel='stylesheet' type='text/css' />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script type="application/x-javascript">
            addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);function hideURLbar(){ window.scrollTo(0,1); }
        </script>
    </head>

    <body>
        <!----CONTAINER---->
        <div class="white-container" id="login-container">
            <form action="login-script.php" method="POST">
                <input name="username" type="text" placeholder="Username">
                <input name="password" type="password" placeholder="Password">

                <?php
                // check if session contains flag login with error value >> show err msg
                if (isset($_SESSION['login'])) {
                    if ($_SESSION['login'] === "error") {
                        echo '<p class="error-label">Invalid username or password!</p>';
                        $_SESSION['login'] = "null";
                    }
                }
                ?>

                <input type="submit" value="Login" class="gray-button" style="width: 100%; font-size: 18px; font-weight: bold">
            </form>
        </div>
    </body>
</html>