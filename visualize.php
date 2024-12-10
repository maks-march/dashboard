<?php
    session_start();
    if (!isset($_COOKIE['log']) || (!isset($_GET['name']) && !isset($_GET['filenames']))) {
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
                if (isset($_GET['name'])) {
                    write_table($_GET['name'], 0);
                } else {
                    $names = explode("~", $_GET['filenames']);
                    foreach ($names as $key => $value) {
                        write_table($value, $key);
                    }
                }


                function write_table($name, $id){
                    include "conn.php";
                    $sql = "SELECT * FROM `".$name."`";
                    $table = mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC);
                    $cols = array_keys($table[0]);
                    echo '<div class="container" style="grid-template-columns: 3vw repeat('.(count($cols)-1).', 1fr)">';
                    foreach ($cols as $key) {
                        echo '
                            <input id = "0.'.$key.'.'.$id.'" type = "checkbox" value = "0.'.$key.'/" name = "coords[]">
                            <label for = "0.'.$key.'.'.$id.'" class ="title cell">
                            '.$key.'     
                            </label>
                        ';
                    }
                    for ($i=0; $i < count($table); $i++) {
                        $prev = "";
                        $row = $table[$i];
                        $nums = "";
                        for ($k = 1; $k < count($row); $k++) { 
                            $nums = $nums.$k;
                        }
                        if ($nums == join("", array_slice($row, 1, count($row)))) {
                            break;
                        }
                        foreach ($cols as $ckey => $cvalue) {
                            $value = $row[$cvalue];
                            if ($value == "X") {
                                $value = 0;
                            }
                            if ($value == "del" || ($value == "" && $cvalue != 'A')) {
                                continue;
                            }
                            $iter_ver = $i + 1;
                            $grid_row = "";
                            $ver_join = False;
                            while (!is_numeric($prev) && $iter_ver < count($table) && $table[$iter_ver][$cvalue] == "") {
                                $ver_join = True;
                                $table[$iter_ver][$cvalue] = "del";
                                $iter_ver = $iter_ver + 1;
                            }
                            if ($ver_join) {
                                $grid_row = "grid-row: ".($i+2)."/".($iter_ver+2).";";
                            }
                            $iter_hor = $ckey + 1;
                            $input_value = $i.'.'.$cols[$iter_hor-1]."/";
                            while ($iter_hor < count($cols) && $cols[$ckey] != 'id' && $row[$cols[$iter_hor]] == "") {
                                $input_value = $input_value.$i.'.'.$cols[$iter_hor]."/";
                                if (is_numeric($prev)){
                                    $row[$cols[$iter_hor]] = "0";
                                } else {
                                    $row[$cols[$iter_hor]] = "del";
                                    $iter_hor = $iter_hor + 1;
                                }
                            }
                            echo '
                                <input id = "'.($i+1).'.'.$cvalue.'.'.$id.'" class = "input" type = "checkbox" value = "'.$input_value.'" name = "coords[]">
                                <label for = "'.($i+1).'.'.$cvalue.'.'.$id.'"  class ="cell" style="grid-column: '.($ckey+1).' / '.($iter_hor+1).';'.$grid_row.'">
                                '.$value.'     
                                </label>
                            ';
                            if ($cvalue != 'id') {
                                $prev = $value;
                            }
                        }
                    }
                    echo '</div>';
                }
            ?>
        </main>
        <button class="check" id = "ready">
            Готово
        </button>
    </form>
    <script src="js/visualize_script.js"></script>
</body>
</html>