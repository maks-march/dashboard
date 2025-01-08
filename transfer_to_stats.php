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
            if (array_key_exists($part, $by_city[$city])) {
                array_push($by_city[$city][$part], $table);
            } else {
                $by_city[$city][$part] = array($table);
            }
        } else {
            $by_city[$city][$part] = array($table);
        }

        if (array_key_exists($year, $by_year)) {
            if (array_key_exists($part, $by_year[$year])) {
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

function get_stats($coords, $lists, $headers, $conn) {
    $stats = array();
    $sql = "SELECT `tables_ids` FROM `users` WHERE `login` ='".$_COOKIE['log']."'";
    $tables_names = mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC)[0]['tables_ids'];
    foreach ($coords as $part => $coord) {
        foreach ($lists as $header => $parts) {
            $stats[$part][$header] = array();
            foreach ($parts[$part] as $key => $name) {
                
                if (!str_contains($tables_names, explode($_COOKIE['log'], $name)[0])) {
                    continue;
                }
                $stat = transfer_to_stats($name, $coord, $conn);
                if (count($stats[$part][$header]) > 0) {
                    foreach ($stats[$part][$header][0]['stats'] as $key => $value) {
                        $stats[$part][$header][0]['stats'][$key] = $stats[$part][$header][0]['stats'][$key] + $stat['stats'][$key];
                    }
                } else {
                    array_push($stats[$part][$header], $stat);
                }
            }
        }
    }
    return $stats;
}

function get_stat_by_year($lists, $coords, $conn) {
    $coords = format_coords($coords);
    $headers = array_keys($lists);
    $stats = get_stats($coords, $lists, $headers, $conn);
    foreach ($stats as $part => $values) {
        foreach ($values[$headers[0]] as $s_key => $stat) {
            foreach ($stat['headers'] as $h_key => $value) {
                echo '<div class = "chart-data">';
                echo '<h3 class = "title">'.$values[$headers[0]][$s_key]['headers'][$h_key].'</h3>';
                echo '<ul class = "'.$h_key.'">';
                foreach ($headers as $key => $header) {
                    echo '<li class="header">'.$header.'</li>';
                }
                echo '
                </ul><ul class = "'.$h_key.'">
                ';
                foreach ($headers as $key => $header) {
                    echo '<li class="value">'.$values[$header][$s_key]['stats'][$h_key].'</li>';
                }
                echo '
                </ul>
                ';
                echo '</div>';
            }
        }
    }
}

function get_stat_by_city($lists, $coords, $conn) {
    $coords = format_coords($coords);
    $headers = array_keys($lists);
    $stats = get_stats($coords, $lists, $headers, $conn);
    foreach ($stats as $part => $values) {
        foreach ($values[$headers[0]] as $s_key => $stat) {
            foreach ($stat['headers'] as $h_key => $value) {
                echo '<div class = "chart-data">';
                echo '<h3 class = "title">'.$values[$headers[0]][$s_key]['headers'][$h_key].'</h3>';
                echo '<ul class = "'.$h_key.'">';
                foreach ($headers as $key => $header) {
                    echo '<li class="header">'.$header.'</li>';
                }
                echo '
                </ul><ul class = "'.$h_key.'">
                ';
                foreach ($headers as $key => $header) {
                    echo '<li class="value">'.$values[$header][$s_key]['stats'][$h_key].'</li>';
                }
                echo '
                </ul>
                ';
                echo '</div>';
            }
        }
    }
}

function get_stat_by_part($lists, $coords, $conn) {
    $stats = array();
    $coords = format_coords($coords);
    $headers = array_keys($lists);
    $flag_compare_parts = true;
    foreach ($coords as $key => $columns) {
        if (count($columns) != 1 ) {
            $flag_compare_parts = false;
            break;
        }
    }
    if ($flag_compare_parts) {
        echo '<div class = "chart-data">';
        foreach ($lists as $part => $title) {
            $title = $title[0];
            $coord = $coords[$part];
            array_push($stats, transfer_to_stats($title, $coord, $conn));
        }
        echo '<ul class = "0">';
        foreach ($stats as $part => $values) {
            foreach ($values['headers'] as $key => $value) {
                echo '<li class="header">'.$value." раздел ".$part.'</li>';
            }
        }
        echo '
        </ul><ul class = "0">
        ';
        foreach ($stats as $part => $values) {
            foreach ($values['stats'] as $key => $value) {
                echo '<li class="value">'.$value.'</li>';
            }
        }
        echo '
        </ul>
        ';
        echo '</div>';
    } else {
        echo '<div class = "chart-data">';
        foreach ($lists as $part => $title) {
            $title = $title[0];
            $coord = $coords[$part];
            array_push($stats, transfer_to_stats($title, $coord, $conn));
        }
        echo '<ul class = "0">';
        foreach ($stats as $part => $values) {
            foreach ($values['headers'] as $key => $value) {
                echo '<li class="header">'.$value." раздел ".$part.'</li>';
            }
        }
        echo '
        </ul><ul class = "0">
        ';
        foreach ($stats as $part => $values) {
            foreach ($values['stats'] as $key => $value) {
                echo '<li class="value">'.$value.'</li>';
            }
        }
        echo '
        </ul>
        ';
        echo '</div>';
    }

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