<?php

function transfer_to_stats($lists, $conn) {
    $stats = array();
    foreach ($lists as $key => $coords) {
        $coords = explode("/", $coords);
        $title = $coords[0];
        $coords = array_slice($coords, 1, count($coords)-2);
        $sql = "SELECT `id` FROM `".$title."` WHERE `A` = 1";
        $header = mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC)[0]['id'];
        $coords_sql = "`".join("`, `", $coords)."`";
        $sql = "SELECT ".$coords_sql." FROM `".$title."` WHERE `id` < ".$header;
        $headers = mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC);
        $headers = array_reverse($headers);
        $sql = "SELECT ".$coords_sql." FROM `".$title."` WHERE `id` > ".$header;
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
        $stats[$key] = array('title' => $title, 'headers' => $new_headers, 'stats' => $sum_values);
    }
    return $stats;
}