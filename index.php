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
                <input type="text" placeholder="Поиск...">
            </div>
            <div class="scroller">
                <?php 
                
                    if (isset($_POST['filename']) && $_POST['filename'] != "") {
                        deleteList($_POST['filename']);
                        $_POST['filename'] = "";
                    }
                    $sql = "SELECT `tables_ids` FROM `users` WHERE `login` = '".$_COOKIE['log']."'";
                    $query = mysqli_query($conn, $sql);
                    $tables_ids = mysqli_fetch_all($query, MYSQLI_ASSOC)[0]['tables_ids'];
                    $tables_ids = explode(" ", $tables_ids);
                    foreach ($tables_ids as $key => $name) {
                        if ($name == "") {
                            continue;
                        }
                        echo '
                        <form method ="POST" class="file_item">
                            <span class = "name">
                                '.$name.'
                            </span>
                            <div>
                                <a class="analysis" href = "visualize.php?name='.$name.'">
                                    Анализ
                                </a>
                                <input type="text" name = "filename" value ="'.$name.'" style = "display:none">
                                <button class="delete">
                                    Удалить
                                </button>
                            </div>
                        </form>
                        <script src="js/window_script.js"></script>
                        ';
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
                            $sql = "DROP TABLE `".$filename."`";
                            mysqli_query($conn, $sql);
                        }
                    }
                ?>
            </div>
        </div>
        <div class="navigation">
            <a href = "add.php" class="add_files">
                Добавить файлы
            </a>
        </div>
    </main>
    
</body>
</html>
