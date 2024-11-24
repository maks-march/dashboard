<?php

function transfer_to_SQL($files, $conn) {
    include 'phpExcel/PHPExcel/IOFactory.php';
    $files = toArrayFiles($files);
    for ($i=0; $i < count($files); $i++) {
        $result = upload_file($i, $_COOKIE['log'], $files[$i]);
        if(isset($result['error'])){
            echo $result['error'];
        }else{
            to_SQL($result, $conn);
        }
    }
}

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

function upload_file($id, $user, $file, $upload_dir= 'files', $allowed_types= array('xls', 'xlsx')){
    
    $filename = $user."_".$id;
    $n = explode('.', $file['name']);
    $filetype = array_pop($n);
    $blacklist = array(".php", ".phtml", ".php3", ".php4", ".py", ".zip");
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
    
        return Array('filename' =>  $filename.".".$filetype, 'name' => explode('.', $file['name'])[0]);
}


function to_SQL($table, $conn) {
    $filename = $table['filename'];
    $name = str_replace(" ", "_", $table['name']);
    $inputFileName = './files/'.$filename;
    try {
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);
    } catch(Exception $e) {
        die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
    }

    $sheets = $objPHPExcel -> getAllSheets();
    $i = 0;
    foreach ($sheets as $key => $sheet) {
        $sql = "SELECT `tables_ids` FROM `users` WHERE `login` = '".$_COOKIE['log']."';";
        $ids = mysqli_fetch_all(mysqli_query($conn,$sql), MYSQLI_ASSOC);    

        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();
        $columns = array_keys($sheet->getColumnDimensions());

        $params = join(" TEXT, ", $columns)." TEXT";

        $table_name = $name.$_COOKIE['log']."list".$i;
        $sql = "UPDATE `users` SET `tables_ids` = '".$ids[0]['tables_ids']." ".$table_name." ' WHERE `login` = '".$_COOKIE['log']."';";
        mysqli_query($conn,$sql);
        $sql = "CREATE TABLE ".$table_name." (id INT PRIMARY KEY AUTO_INCREMENT, "."$params".");";
        mysqli_query($conn,$sql);
        
        for ($row = 1; $row <= $highestRow; $row++){
            try {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                "",
                TRUE,
                FALSE);
                $values = '"'.join('", "', $rowData[0]).'"';
                $keys= '`'.join('`, `', $columns).'`';
                $sql = 'INSERT INTO `'.$table_name.'` ('.$keys.') Values ('.$values.')';
                mysqli_query($conn,$sql);
            } catch(Exception $e) {
                $var = $e;
            }
        }
        $i = $i + 1;
    }
    unlink('files/'.$filename);
}


