<?php
    include "conn.php";
    session_start();
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
    <script src="js/index_script.js"></script>
    <?php 
        if (!isset($_COOKIE['log'])) {
            echo '    
            <div class="start_window" id ="start_window">
                <div class="column">
                    <div class="bubbles">
                        <img src="img/bubbles.jpg" alt="">
                    </div>
                </div>
                <div class="column">
                    <a href = "enter.php" class="start">
                        START
                    </a>
                    <footer></footer>
                </div>
                <div class="column">
                    <h1>JustImport</h1>
                    <h2>Импортируйте с нами!</h2>
                </div>
            </div>
            ';
        } else {
            echo '
                <script src="js/window_script.js"></script>
            ';
        }
    ?>

    </div>
    <header>
        <h1>JustImport</h1>
    </header>
    <main id ="files">
        <div class="files">
            <div class="title">
                <span class="name">Файлы</span>
            </div>
            <form method="POST" action ="visualize.php" class="scroller">
                <div class="file_item">
                    <label for="filename">
                        <input type="checkbox" onClick="toggle(this)">
                        <span class = "name">
                            Все файлы
                        </span>
                    </label>
                    <div>
                        <input id = "a" type = "submit" name= "action_type" class="delete" value = "Удалить">
                    </div>
                </div>
                <?php
                    $sql = 'SELECT `tables_ids` FROM `users` WHERE `login` = "'.$_COOKIE['log'].'"';
                    $query = mysqli_query($conn, $sql);
                    $tables_ids = mysqli_fetch_all($query, MYSQLI_ASSOC)[0]['tables_ids'];
                    $tables_ids = explode(" ", $tables_ids);
                    $i = 1;
                    foreach ($tables_ids as $key => $name) {
                        if ($name == "") {
                            continue;
                        }
                        $i = $i + 1;
                        echo '
                        <div class="file_item">
                            <label for="filename">
                                <input type="checkbox" name = "filename[]" value ="'.$name.'">
                                <span class = "name">
                                    '.$name.'
                                </span>
                            </label>
                            <div>
                                <a class="delete"  href = "delete.php?filename='.$name.'">
                                    Удалить
                                </a>
                            </div>
                        </div>
                        ';
                    }
                ?>
                <div class="navigation">
                    <a href = "add.php" class="add_files">
                        Добавить файлы
                    </a>
                    <input type = "submit" name= "action_type" class="visualize_files" value = "Создать графики">           
                    <a href = "logout.php" class="logout">
                        Выйти
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
