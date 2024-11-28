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
    <form method="post" action = "analysis.php">
        <main>
            <?php
                $sql = "SELECT * FROM `".$_GET['name']."`";
                $table = mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC);
                $cols = array_keys($table[0]);
                echo '<div class="container" style="grid-template-columns: 3vw repeat('.(count($cols)-1).', 1fr)">';
                foreach ($cols as $key) {
                    echo '
                        <label for = "0.'.$key.'" class ="title cell">
                        '.$key.'     
                        </label>
                        <input id = "0.'.$key.'" type = "checkbox" value = "0.'.$key.'/" name = "coords[]">
                    ';
                }
                foreach ($table as $tkey => $row) {
                    $prev = "";
                    foreach ($cols as $ckey => $cvalue) {
                        if ($row[$cvalue] != "" || $cvalue == $cols[1] || is_numeric($prev)) {
                            $value = $row[$cvalue];
                            if (($value == "X" || $value == "") && $cvalue != $cols[1]) {
                                $value = 0;
                            }
                            $iter = $ckey + 1;
                            $input_value = $tkey.'.'.$cols[$iter-1]."/";
                            while (!is_numeric($value) && $ckey != 0 && $iter < count($cols) && $row[$cols[$iter]] == "") {
                                $input_value = $input_value.$tkey.'.'.$cols[$iter]."/";
                                $iter = $iter + 1;
                            }
                            echo '
                                <label for = "'.($tkey+1).'.'.$cvalue.'"  class ="cell" style="grid-column: '.($ckey+1).' / '.($iter+1).';">
                                '.$value.'     
                                </label>
                                <input id = "'.($tkey+1).'.'.$cvalue.'" type = "checkbox" value = "'.$input_value.'" name = "coords[]">
                            ';
                            if ($cvalue != $cols[0]) {
                                $prev = $value;
                            }
                        }
                    }
                }
                echo '</div>';
            ?>
        </main>
        <button class="check" id = "ready">
            Готово
        </button>
    </form>
    <script src="js/visualize_script.js"></script>
</body>
</html>