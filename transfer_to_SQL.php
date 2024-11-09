<?php
//  Include PHPExcel_IOFactory
include 'phpExcel/PHPExcel/IOFactory.php';

$inputFileName = './files/0_0.xls';

//  Read your Excel workbook
try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
} catch(Exception $e) {
    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}

//  Get worksheet dimensions
$sheets = $objPHPExcel -> getAllSheets();

foreach ($sheets as $key => $sheet) {
    $highestRow = $sheet->getHighestRow(); 
    $highestColumn = $sheet->getHighestColumn();
    for ($row = 1; $row <= $highestRow; $row++){ 
        //  Read a row of data into an array
        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
        echo var_dump($rowData);
    }
}

