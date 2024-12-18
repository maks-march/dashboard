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
            <form method="POST" class="scroller">
                <div class="file_item">
                    <label for="filename">
                        <input type="checkbox" onClick="toggle(this)">
                        <span class = "name">
                            Выбранные листы
                        </span>
                    </label>
                    <div>
                        <input id = "a" type = "submit" name= "action_type" class="analysis" value = "Анализ">
                        <input id = "a" type = "submit" name= "action_type" class="delete" value = "Удалить">
                    </div>
                </div>
                <?php
                    if (isset($_POST['filename']) && $_POST['filename'] != "") {
                        if ($_POST['action_type'] == "Удалить") {
                            foreach ($_POST['filename'] as $key => $value) {
                                deleteList($value);
                            }
                        } else {
                            $filenames = join('~', $_POST['filename']);
                            $_POST['filename'] = "";
                            header("Location: visualize.php?filenames=".$filenames);
                        }
                    }
                    $sql = "SELECT `tables_ids` FROM `users` WHERE `login` = '".$_COOKIE['log']."'";
                    $query = mysqli_query($conn, $sql);
                    $tables_ids = mysqli_fetch_all($query, MYSQLI_ASSOC)[0]['tables_ids'];
                    $tables_ids = explode(" ", $tables_ids);
                    $i = 1;
                    foreach ($tables_ids as $key => $name) {
                        if ($name == "") {
                            continue;
                        }
                        $nickname = explode($_COOKIE['log'], $name)[0]." лист №".(explode('list', $name)[1]+1);
                        $i = $i + 1;
                        echo '
                        <div class="file_item">
                            <label for="filename">
                                <input type="checkbox" name = "filename[]" value ="'.$name.'">
                                <span class = "name">
                                    '.$nickname.'
                                </span>
                            </label>
                            <div>
                                <a class="analysis" href = "visualize.php?name='.$name.'">
                                    Анализ
                                </a>
                                <a class="delete"  href = "delete.php?filename='.$name.'">
                                    Удалить
                                </a>
                            </div>
                        </div>
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
            </form>
        </div>
        <div class="navigation">
            <a href = "add.php" class="add_files">
                Добавить файлы
            </a>
        </div>
    </main>
</body>
</html>
