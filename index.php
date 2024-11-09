<?php
    include "conn.php";
    session_start();
    setcookie('auth', true, time() + 3600);
    setcookie('log', 'user1', time() + 3600);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JustImport</title>
    <link rel="stylesheet" href="css/index_style.css">
</head>
<body>
    <div class="start_window" id ='start_window'>
        <div class="column">
            <div class="bubbles">
                <img src="img/bubbles.jpg" alt="">
            </div>
        </div>
        <div class="column">
            <button onclick = "delStartWindow()" class="start">
                START
            </button>
            <footer></footer>
        </div>
        <div class="column">
            <h1>JustImport</h1>
            <h2>Импортируйте с нами!</h2>
        </div>
    </div>
    <header>
        <h1>JustImport</h1>
    </header>
    <main id ="files">
        <div class="files">
            <div class="title">
                <span class="name">Файлы</span>
                <input type="text" placeholder="Поиск...">
            </div>
            <div class="scroller">
                <div class="file_item">
                    <span class = "name">
                        Екатеринбург 2017
                    </span>
                    <div>
                        <a href = "analysis.php?name=Екатеринбург 2017">
                            <button class="analysis">
                                Анализ
                            </button>
                        </a>
                        <button class="delete">
                            Удалить
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="navigation">
            <a href = "add.php" class="add_files">
                Добавить файлы
            </a>
        </div>
    </main>


    
    <script src="js/index_script.js"></script>
</body>
</html>
