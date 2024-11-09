<?php
    $db_host = "localhost";
    $db_name = "dashboard";
    $db_user = "admins";
    $db_pass = "11203445";
    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (mysqli_connect_error()) {
       echo mysqli_connect_error();
       exit;
    }
    mysqli_set_charset($conn,"utf8mb4");