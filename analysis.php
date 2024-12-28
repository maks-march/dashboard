<?php
    include "conn.php";
    session_start();
    if (!isset($_COOKIE['log'])) {
        header('Location:enter.php');
    }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JustImport</title>
    <link rel="stylesheet" href="css/analysis_style.css">
</head>
<body>
    <header>
        <h1>JustImport</h1>
    </header>
    <main id="graphs">
        <div class="navigation">
            <a href="index.php?no_start" class = "add_files">
                    К файлам
            </a>
        </div>
        <h2 id = 'title'>Статистика</h2>
        <?php
            if(isset($_POST['coords'])){
                echo '<h1 class="type">'.$_POST['type'].'</h1>';
                include 'transfer_to_stats.php';
                $tables = explode("~", $_POST['tables']);
                $res = get_sorted($tables);
                $by_city = $res['by_city'];
                $by_year = $res['by_year'];
                $by_part = $res['by_part'];
                if (count($by_year) > 1) {
                    get_stat_by_year($by_year, $_POST['coords'], $conn);
                } else {
                    if (count($by_city) > 1) {
                        get_stat_by_city($by_city, $_POST['coords'], $conn);
                    } else {
                        get_stat_by_part($by_part, $_POST['coords'], $conn);
                    }
                }

            } else {
                header('Location:index.php');
            }
        ?>
        <?php
            function print_stat($stat) {
                $keys = array_keys($stat['headers']);
                echo '<h3>'.$stat['title'].'</h3>';
                foreach ($keys as $i => $key) {
                    echo '
                    <ul>
                        <li class = "header">'.$stat['headers'][$key].'</li>
                        <li class = "value">'.$stat['stats'][$key].'</li>
                    </ul>
                    ';
                }
            }
        ?>
        
    </main>
</body>
</html>
