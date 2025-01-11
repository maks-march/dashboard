<?php
    include "conn.php";
    session_start();
    if ((!isset($_POST['filename']) || $_POST['action_type'] == 'Удалить') && !isset($_POST['city'])) {
        foreach ($_POST['filename'] as $key => $value) {
            deleteList($value);
        }
        header('Location:index.php');
    }
    if (!isset($_COOKIE['log'])) {
        header('Location:index.php');
    }

    
    function deleteList($filename) {
        include "conn.php";
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
                    $sql = "DROP TABLE `".$filename.$_COOKIE['log']."list".$i."`";
                    mysqli_query($conn, $sql);
                } catch (Exception $e) {
                    break;
                }
                $i = $i + 1;
            }

        }
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
        <h2>Город(а)</h2>
        <input class= "choose_tables_input" type="text" required name ="city" list="list-of-city" required placeholder="название, название 2" <?php if (isset($_POST['city'])) { echo 'value = "'.$_POST['city'].'"'; } ?>>
        <datalist id="list-of-city"> 
        <?php 
                $cities = array();
                $years = array();
                foreach ($_POST['filename'] as $key => $value) {
                    $value = explode('_', $value);
                    $cities[$key] = join("_", array_slice($value, 0, count($value)-1));
                    $years[$key] = $value[array_key_last($value)];
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
        <h2>Год(а)</h2>
        <input class= "choose_tables_input" type="text" required name ="year" list="list-of-years" required placeholder="2019, 2021" <?php if (isset($_POST['year'])) { echo 'value = "'.$_POST['year'].'"'; } ?>>
        <datalist id="list-of-years">
            <?php 
                foreach ($years as $key => $year) {
                    echo '
                        <option value="'.$year.'">
                    ';
                }
            ?>
        </datalist>
        <h2>Раздел(ы)</h2>
        <input class= "choose_tables_input" type="text" list = "list-of-parts" required name ="parts" placeholder="1, 2, 3" <?php if (isset($_POST['parts'])) { echo 'value = "'.$_POST['parts'].'"'; } ?>>
        <datalist id="list-of-parts">
            <option value="1">  
            <option value="2">  
            <option value="3">  
            <option value="4">  
            <option value="5">  
            <option value="6"> 
            <option value="7">  
        </datalist>
        <select name = "type" id="list-of-types">
            <option value="pie">Круговая диаграмма</option>
            <option value="bar">Стобчатая диаграмма</option>
            <option value="gistogramm">Линейная диаграмма</option>
        </select>
        <div class="navigation">
            <a href="index.php?no_start" class = "add_files">
                К файлам
            </a>
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
                                $filenames[$key1+$key2+$key3+$i] = $city."_".$year.$_COOKIE['log']."list".intval($part)-1;
                            }
                            $i = $i + 10;
                        }
                    }
                    $flag = true;
                    foreach ($filenames as $key => $value) {
                        if ($key > 10) {
                            break;
                        }
                        try {
                            
                            $sql = "SELECT * FROM `".$value."`";
                            $table = mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC);
                            write_table($value, $key);
                        } catch (Exception $e){
                            echo 'Ошибка некорректные данные!';
                            $flag = false;
                        }
                    }
                    $filenames = join("~", $filenames);
                    if ($flag) {
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