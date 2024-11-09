<?php
    include "conn.php";
    session_start();
    setcookie('auth', true, time() + 3600);
    setcookie('log', 'user1', time() + 3600);
    setcookie('id', '0', time() + 3600);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            function toArrayFiles(&$file_post) {

                $file_array = array();
                $file_count = count($file_post['name']);
                $file_keys = array_keys($file_post);
            
                for ($i=0; $i<$file_count; $i++) {
                    foreach ($file_keys as $key) {
                        $file_array[$i][$key] = $file_post[$key][$i];
                    }
                }
            
                return $file_array;
            }

            function upload_file($id, $user, $file, $upload_dir= 'files', $allowed_types= array('xls', 'xlsm', 'xlsx')){
                
                $filename = $user."_".$id;
                $n = explode('.', $file['name']);
                $filetype = array_pop($n);
                $blacklist = array(".php", ".phtml", ".php3", ".php4", ".py");
                $ext = substr($filename, strrpos($filename,'.'), strlen($filename)-1);
                if(in_array($ext,$blacklist)){
                    return array('error' => 'Запрещено загружать исполняемые файлы');}
                
                $upload_dir = dirname(__FILE__).DIRECTORY_SEPARATOR.$upload_dir.DIRECTORY_SEPARATOR;
                $max_filesize = 43886080;
                
                if(!is_writable($upload_dir)) 
                    return array('error' => 'Невозможно загрузить файл в папку "'.$upload_dir.'". Установите права доступа - 777.');
                
                if(!in_array($filetype, $allowed_types))
                    return array('error' => 'Данный тип файла не поддерживается.');
                
                if(filesize($file['tmp_name']) > $max_filesize)
                    return array('error' => 'файл слишком большой. максимальный размер '.intval($max_filesize/(1024*1024)).'мб');
                
                if(!move_uploaded_file($file['tmp_name'],$upload_dir.$filename.".".$filetype))
                    return array('error' => 'При загрузке возникли ошибки. Попробуйте ещё раз.');
                
                    return Array('filename' => $filename.".".$filetype);
            }

            function transfer_files($names) {
                
            }

            if(isset($_FILES['uploadfile']) && !empty($_FILES['uploadfile']['name'])){
                $files = toArrayFiles($_FILES['uploadfile']);
                $tables = array();
                for ($i=0; $i < count($files); $i++) { 
                    $result = upload_file($i, $_COOKIE['id'], $files[$i]);
                    if(isset($result['error'])){
                        echo $result['error'];
                    }else{
                        array_push($tables, $result['filename']);
                    }
                }
                transfer_files($tables);
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