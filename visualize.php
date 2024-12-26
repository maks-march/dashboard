<?php
    include "conn.php";
    session_start();
    if (!isset($_COOKIE['log'])) {
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
    <header>
        <h1>JustImport</h1>
    </header>
    <main>
    

    <form method="post" class = "choose_tables">
        <div class="navigation">
            <a href="index.php?no_start" class = "add_files">
                К файлам
            </a>
        </div>
        <span>Город(а)</span>
        <input class= "choose_tables_input" type="text" required name ="city" list="list-of-city" required placeholder="название, название 2">
        <datalist id="list-of-city"> 
        <?php 
                $sql = 'SELECT `tables_ids` FROM `users` WHERE `login` = "'.$_COOKIE['log'].'"';
                $query = mysqli_query($conn, $sql);
                $tables_ids = mysqli_fetch_all($query, MYSQLI_ASSOC)[0]['tables_ids'];
                $tables_ids = explode(" ", $tables_ids);
                $cities = array();
                $years = array();
                foreach ($tables_ids as $key => $value) {
                    if ($value == "") {
                        continue;
                    }
                    $value = explode("_", $value);
                    $cities[$key] = $value[0];
                    $years[$key] = $value[1];
                }
                $cities = array_unique($cities);
                foreach ($cities as $key => $city) {
                    echo '
                        <option value="'.$city.'">
                    ';
                }
                $years = array_unique($years);
            ?>
        </datalist>
        <span>Год(а)</span>
        <input class= "choose_tables_input" type="text" required name ="year" list="list-of-years" required placeholder="2019, 2021">
        <datalist id="list-of-years">
            <?php 
                foreach ($years as $key => $year) {
                    echo '
                        <option value="'.$year.'">
                    ';
                }
            ?>
        </datalist>
        <span>Разделы</span>
        <input class= "choose_tables_input" type="text" list = "list-of-parts" required name ="parts" placeholder="0, 1, 2, 3">
        <datalist id="list-of-parts">
            <option value="0">  
            <option value="1">  
            <option value="2">  
            <option value="3">  
            <option value="4">  
            <option value="5">  
            <option value="6"> 
        </datalist>
        <select name = "type" id="list-of-types">
            <option value="pie">Круговая диаграмма</option>
            <option value="bar">Стобчатая диаграмма</option>
        </select>
        <div class="navigation">
            <button class="check" id = "ready">
                Далее
            </button>
        </div>
    </form>
    <form method="post" action = "analysis.php" class = "choose_values">
            <?php
                if (isset($_POST['city'])) {
                    $cities = explode(",", str_replace(" ", "", $_POST['city']));
                    $years = explode(",", str_replace(" ", "", $_POST['year']));
                    $parts = explode(",", str_replace(" ", "", $_POST['parts']));
                    $type = $_POST['type'];
                    $filenames = array();
                    $i = 0;
                    foreach ($cities as $key1 => $city) {
                        foreach ($years as $key2 => $year) {
                            foreach ($parts as $key3 => $part) {
                                $filenames[$key1+$key2+$key3+$i] = $city."_".$year.$_COOKIE['log']."list".$part;
                            }
                            $i = $i + 10;
                        }
                    }
                    foreach ($filenames as $key => $value) {
                        if ($key > 10) {
                            break;
                        }
                        write_table($value, $key);
                    }
                    $filenames = join("~", $filenames);
                    echo '
                        
                        <div class="navigation" >
                            <a href="index.php?no_start" class = "add_files">
                                К файлам
                            </a>
                            <button class="check" id = "ready">
                                Готово
                            </button>
                        </div>
                        <input id = "filnames" type = "text" value = "'.$filenames.'" name = "tables">
                        <input id = "type" type = "text" value = "'.$type.'" name = "type">
                    ';
                }

                function write_table($name, $id){
                    include "conn.php";
                    $sql = "SELECT * FROM `".$name."`";
                    $table = mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC);
                    $part = explode("list", $name);
                    $part = $part[array_key_last($part)];
                    $cols = array_keys($table[0]);
                    echo '<div class="container" style="grid-template-columns: 3vw repeat('.(count($cols)-1).', 1fr)">';
                    foreach ($cols as $key) {
                        echo '
                            <input id = "0.'.$key.'.'.$id.'" type = "checkbox" value = "'.$part.'/'.$key.'/" name = "coords[]">
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
                            $input_value = $cols[$iter_hor-1]."/";
                            while ($iter_hor < count($cols) && $cols[$ckey] != 'id' && $row[$cols[$iter_hor]] == "") {
                                $input_value = $input_value.$cols[$iter_hor]."/";
                                if (is_numeric($prev)){
                                    $row[$cols[$iter_hor]] = "0";
                                } else {
                                    $row[$cols[$iter_hor]] = "del";
                                    $iter_hor = $iter_hor + 1;
                                }
                            }
                            echo '
                                <input id = "'.($i+1).'.'.$cvalue.'.'.$id.'" class = "input" type = "checkbox" value = "'.$part.'/'.$input_value.'" name = "coords[]">
                                <label for = "'.($i+1).'.'.$cvalue.'.'.$id.'"  class ="cell" style="grid-column: '.($ckey+1).' / '.($iter_hor+1).';'.$grid_row.'">
                                '.$value.'     
                                </label>
                            ';
                            if ($cvalue != 'id') {
                                $prev = $value;
                            }
                        }
                    }
                    echo '
                    </div>
                    ';
                }
            ?>
    </form>
    </main>
    <?php
        echo '
            <script src="js/visualize_script.js"></script>
        ';
    ?>
</body>
</html>