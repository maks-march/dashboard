<?php

function to_SQL($table, $conn) {
    include 'phpExcel/PHPExcel/IOFactory.php';
    $filename = $table['filename'];
    $name = $table['name'];
    $inputFileName = './files/'.$filename;
    try {
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);
    } catch(Exception $e) {
        die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
    }

    $sheets = $objPHPExcel -> getAllSheets();
    
    foreach ($sheets as $key => $sheet) {
        $sql = "SELECT `tables_ids` FROM `users` WHERE `login` = '".$_COOKIE['log']."';";
        $ids = mysqli_fetch_all(mysqli_query($conn,$sql), MYSQLI_ASSOC);    
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();
        $columns = array_keys($sheet->getColumnDimensions());
        $params = join(" TEXT, ", $columns)." TEXT";
        if ($ids[0]['tables_ids'] == ""){
            $table_name = $_COOKIE['log']."list0";
            $sql = "UPDATE `users` SET `tables_ids` = '0 ' WHERE `login` = '".$_COOKIE['log']."';";
            mysqli_query($conn,$sql);
            $sql = "CREATE TABLE ".$table_name." (id INT PRIMARY KEY AUTO_INCREMENT, "."$params".");";
            mysqli_query($conn,$sql);
        } else {
            $tables_ids = explode(" ", $ids[0]['tables_ids']);
            $last = intval($tables_ids[count($tables_ids) - 2]) + 1;
            $table_name = $_COOKIE['log']."list".$last;
            $sql = "UPDATE `users` SET `tables_ids` = '".$ids[0]['tables_ids'].$last." ' WHERE `login` = '".$_COOKIE['log']."';";
            mysqli_query($conn,$sql);
            $sql = "CREATE TABLE ".$table_name." (id INT PRIMARY KEY AUTO_INCREMENT, "."$params".");";
            mysqli_query($conn,$sql);
        }
        
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
    }
    unlink('files/'.$filename);
}


