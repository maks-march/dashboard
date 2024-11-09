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
            <a href="index.php?no_start">
                <button onclick = "moveToFiles()" class="add_files">
                    К файлам
                </button>
            </a>
        </div>
        <h2 id = 'title'>Статистика</h2>
        <div class="visualization">
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
        </div>
        
    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/analysis_script.js"></script>
</body>
</html>
