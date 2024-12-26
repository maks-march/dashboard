<?php

function get_sorted($tables) {
    $by_city = array();
    $by_year = array();
    $by_part = array();
    foreach ($tables as $key => $table) {
        $promo = explode("_", $table);
        $city = $promo[0];
        $promo = explode($_COOKIE['log']."list", $promo[1]);
        $year = $promo[0];
        $part = $promo[1];
        if (array_key_exists($city, $by_city)) {
            if (in_array($part, array_keys($by_city[$city]))) {
                array_push($by_city[$city][$part], $table);
            } else {
                $by_city[$city][$part] = array($table);
            }
        } else {
            $by_city[$city][$part] = array($table);
        }

        if (array_key_exists($year, $by_year)) {
            if (in_array($part, array_keys($by_year[$year]))) {
                array_push($by_year[$year][$part], $table);
            } else {
                $by_year[$year][$part] = array($table);
            }
        } else {
            $by_year[$year][$part] = array($table);
        }

        if (array_key_exists($part, $by_part)) {
            array_push($by_part[$part], $table);
        } else {
            $by_part[$part] = array($table);
        }
    }
    return array('by_city' => $by_city, 'by_year' => $by_year,'by_part' => $by_part);
}

function get_stat_by_year($lists, $coords, $conn) {
    $stats = array('title' => 'Сравнение нас. пунктов за ');
    return $stats;
}

function get_stat_by_city($lists, $coords, $conn) {
    $stats = array();
    $coords = format_coords($coords);
    $headers = array_keys($lists);
    foreach ($coords as $part => $coord) {
        foreach ($lists as $header => $parts) {
            $stats[$part][$header] = array();
            $tablename = $parts[$part][0];
            $stat = transfer_to_stats($tablename, $coord, $conn);
            array_push($stats[$part][$header], $stat);
        }
    }
    foreach ($stats as $part => $values) {
        echo '<h2>Сравнение в разделе № '.$part.'</h2>';
        foreach ($values[$headers[0]] as $key => $stat) {
            // Вернись сюда
            // Короче надо перебрать все колонки в headers and stats подставляя ключи из найденного, 
            // меняя названия первого ключа можно получать значения из разных таблиц, для каждого столбца
            // надо сделать диаграмму, в сравнении, заголовок - сравнение по разделу -> по названию параметра
            // когда будут просто сравнивать значения в одной таблице - зоголовкии столбцов можно отобразить в диаграммах
            // жопа
        }
    }
    return $stats;
}

function get_stat_by_part($lists, $coords, $conn) {
    $stats = array('title' => 'Сравнение нас. пунктов за ');
    return $stats;
}

function format_coords($coords) {
    $new_coords = array();
    foreach ($coords as $key => $coord) {
        $coord = explode("/", $coord);
        if (array_key_exists($coord[0], $new_coords)) {
            array_push($new_coords[$coord[0]], $coord[1]);
        } else {
            $new_coords[$coord[0]] = array($coord[1]);
        }
    }
    return $new_coords;
}

function transfer_to_stats($title, $coords, $conn) {
    $stats = array();
    $sql = "SELECT `id` FROM `".$title."` WHERE `A` = 1";
    $header = mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC)[0]['id'];
    $coords_sql = "`".join("`, `", $coords)."`";
    $sql = "SELECT ".$coords_sql." FROM `".$title."` WHERE `id` < ".$header;
    $headers = mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC);
    $headers = array_reverse($headers);
    $sql = "SELECT ".$coords_sql." FROM `".$title."` WHERE `id` > ".$header." AND `A` != ''";
    $values = mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC);
    $new_headers = array();
    foreach ($coords as $key => $column) {
        foreach ($headers as $key_headers => $value) {
            $new_headers[$column] = $value[$column];
            if ($value[$column] != "") {
                break;
            }
        }
    }
    $sum_values = array();
    foreach ($coords as $key => $column) {
        $summary = 0;
        foreach ($values as $key_headers => $value) {
            if (!is_numeric($value[$column])) {
                continue;
            }
            $summary = $summary + intval($value[$column]);

        }
        $sum_values[$column] = $summary;
    }
    $stats = array('headers' => $new_headers, 'stats' => $sum_values);
    return $stats;
}