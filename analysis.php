<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JustImport</title>
    <link rel="stylesheet" href="css/analysis_style.css">
</head>

<body>
    <div id="hidden-data">
        <div class="chart-data">
            <h1 class="type">pie</h1>
            <ul class="5"><!-- номер раздела -->
                <li class="header">header 1</li>
                <li class="header">header 2</li>
                <li class="header">header 412423523523523523523523523465374684684</li>
                <li class="header">header 4</li>
                <li class="header">header 5</li>
            </ul>
            <ul class="5">
                <li class="value">867</li>
                <li class="value">2352</li>
                <li class="value">5235</li>
                <li class="value">2354</li>
                <li class="value">0</li>
            </ul>
        </div>
            <!-- или типо выбранно два раздела разных раздела, хз зачем -->
        <div class="chart-data">
            <h1 class="type">bar</h1>
            <ul class="0">
                <li class="header">header 1</li>
                <li class="header">header 412423523523523523523523523465374684684</li>
                <li class="header">header 3</li>
                <li class="header">header 412423523523523523523523523465374684684</li>
                <li class="header">header 5</li>
            </ul>
            <ul class="0">
                <li class="value">124</li>
                <li class="value">0</li>
                <li class="value">543</li>
                <li class="value">769</li>
                <li class="value">1034</li>
            </ul>

            <ul class="1">
                <li class="header">header 1</li>
                <li class="header">header 412423523523523523523523523465374684684</li>
            </ul>
            <ul class="1">
                <li class="value">2234</li>
                <li class="value">1034</li>
            </ul>
        </div> 
        
    </div>
    <header>
        <h1>JustImport</h1>
    </header>
    <main id="graphs">
        <div class="navigation">
            <a href="index.php?no_start">
                <button onclick="moveToFiles()" class="add_files">
                    К файлам
                </button>
            </a>
        </div>
        <h2 id='title'>Статистика</h2>
        <div class="visualization">
            <div class="graphs">
            </div>
        </div>

    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/analysis_script.js"></script>
</body>

</html>