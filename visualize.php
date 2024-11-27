<?php
    include "conn.php";
    session_start();
    if (!isset($_COOKIE['log']) || !isset($_GET['name'])) {
        header('Location:index.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JustImport</title>
</head>
<body>
    <?php
        $sql = "SELECT * FROM `".$_GET['name']."`";
        $table = mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC);
        $keys = array_keys($table[0]);
        echo '<div class = "title">';
        foreach ($keys as $key => $key) {
            echo '
            <div class ="cell">
               '.$key.'     
            </div>';
        }
        echo '</div>';
        foreach ($table as $key => $row) {
            echo '<div class = "row">';
            foreach ($row as $key => $value) {
                echo '
                <div class ="cell">
                    '.$value.'     
                </div>';
            }
            echo '</div>';
        }
    ?>
</body>
</html>