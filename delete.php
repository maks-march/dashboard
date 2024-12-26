<?php 
    include "conn.php";
    session_start();
    if (!isset($_COOKIE['log'])) {
        header('Location:index.php?no_start');
    }
    $filename = $_GET['filename'];
    $sql = "SELECT `tables_ids` FROM `users` WHERE `login` = '".$_COOKIE['log']."'";
    $query = mysqli_query($conn, $sql);
    $tables_ids = mysqli_fetch_all($query, MYSQLI_ASSOC)[0]['tables_ids'];
    $tables_ids = explode(" ", $tables_ids);
    $new_ids = "";
    $flag = FALSE;
    foreach ($tables_ids as $key => $table_id) {
        if ($table_id != "") {
            if ($table_id != $filename) {
                $new_ids = $new_ids.$table_id." ";
            } else {
                $flag = TRUE;
            }
        }
    }
    if ($flag) {
        $sql = "UPDATE `users` SET `tables_ids` = '".$new_ids." ' WHERE `login` = '".$_COOKIE['log']."';";
        mysqli_query($conn, $sql);
        $i = 0;
        while (True) {
            try {
                $sql = "DROP TABLE `".$filename."list".$i."`";
                mysqli_query($conn, $sql);
            } catch (Exception $e) {
                break;
            }
            $i = $i + 1;
        }

    }
    header('Location:index.php?no_start');
?>