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
    <link rel="stylesheet" href="css/visualize_style.css">
</head>
<body>
    <main>
        <?php
            $sql = "SELECT * FROM `".$_GET['name']."`";
            $table = mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC);
            $cols = array_keys($table[0]);
            echo '<div class="container" style="grid-template-columns: 3vw repeat('.(count($cols)-1).', 1fr)">';
            foreach ($cols as $key) {
                echo '
                <div class ="title cell">
                '.$key.'     
                </div>';
            }
            foreach ($table as $tkey => $row) {
                foreach ($cols as $ckey => $cvalue) {
                    if ($row[$cvalue] != "" || $cvalue == $cols[1]) {
                        $iter = $ckey + 1;
                        while ($ckey != 0 && $iter < count($cols) && $row[$cols[$iter]] == "") {
                            $iter = $iter + 1;
                        }
                        echo '
                        <div class ="cell" style="grid-column: '.($ckey+1).' / '.($iter+1).';">
                        '.$row[$cvalue].'     
                        </div>';
                    }
                }
            }
            echo '</div>';
        ?>
    </main>

</body>
</html>