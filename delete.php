<?php 

function deleteList($filename) {
    include 'conn.php';
    $user = expand('List', $filename)[0];
    $id = expand('List', $filename)[1];
    $sql = "SELECT `tables_ids` FROM `users` WHERE `login` = '".$user."'";
    $query = mysqli_query($conn, $sql);
    $tables_ids = mysqli_fetch_all($query, MYSQLI_ASSOC)[0]['tables_ids'];
    $tables_ids = explode(" ", $tables_ids);
    $new_ids = "";
    foreach ($tables_ids as $key => $table_id) {
        if ($table_id != $id && $table_id != "") {
            $new_ids = $new_ids.$table_id." ";
        }
    }
    $sql = "UPDATE `users` SET `tables_ids` = '".$new_ids." ' WHERE `login` = '".$user."';";
    mysqli_query($conn, $sql);
}

?>