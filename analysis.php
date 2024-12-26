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
                include "transfer_to_stats.php";
                $stats = array_reverse(transfer_to_stats($_POST['coords'], $conn));
                foreach ($stats as $key => $stat) {
                    print_stat($stat);
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
        <!-- <div class="visualization">
            <div class="graphs">
                <div class="row">
                    <canvas id="chart_place"></canvas>
                    <canvas id="chart_years"></canvas>
                </div>
                <div class="row">
                    <canvas id="chart_payment"></canvas>
                    <canvas id="chart_male"></canvas>
                </div>
            </div>
        </div> -->
        
    </main>
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/analysis_script.js"></script> -->
</body>
</html>
