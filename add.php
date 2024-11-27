<?php
    include "conn.php";
    session_start();
    if (!isset($_COOKIE['log'])) {
        header('Location:index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JustImport</title>
    <link rel="stylesheet" href="css/add_style.css">
</head>
<body>
    <header>
        <h1>JustImport</h1>
    </header>
    <main id = "add" class = "unactive_block">
        <div class="container">
            
            <div class="scroller" id="files_list">
                <div class="title">
                    Загруженные файлы
                </div>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <label class = "upload_zone" for="file_input">
                    <span id ='hint'>Перетащите элементы сюда</span>
                </label>
                <input type="file"  required id="file_input" name="uploadfile[]" multiple>
                <div class="controls">
                    <button class="check" id = "ready">
                        Готово
                    </button>
                    <label for="file_input">
                        Загрузить файлы
                    </label>
                </div>
            </form>
        </div>
        <?php            
            if(isset($_FILES['uploadfile']) && !empty($_FILES['uploadfile']['name'])){
                include "transfer_to_SQL.php";
                transfer_to_SQL($_FILES['uploadfile'], $conn);
            }
        ?>
        <div class="navigation">
            <a href = "index.php?no_start" class="add_files">
                К файлам
            </a> 
        </div>
    </main>
    <script src="js/add_script.js"></script>
</body>
</html>