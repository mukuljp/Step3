<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of processDbClass
 *
 * @author mukul.jayaprakash
 */
class processDbClass {

    //put your code here
    public $VendorExceptionslist;
    public $QueriesList;
    private $dbname;
    public $querycount;
    public $vendorcount;
    public $step3dblink;
    public $step4excellink;

    function processVendorExceptions() {
        if (isset($this->VendorExceptionslist)) {
            $dbpath = dirname(dirname(__FILE__)) . "/uploads/" . $this->dbname . "";
            $connectionString = 'odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=' . $dbpath . ';Uid=; Pwd=;';
            $db = new PDO($connectionString);
            foreach ($this->VendorExceptionslist as $vendor) {
                $this->vendorcount[] = $db->exec("delete from Final_Table where VENDOR_TITLE_NAME='$vendor'");
            }
            $db = null;
        }
    }

    function processQueries() {
        $dbpath = dirname(dirname(__FILE__)) . "/uploads/" . $this->dbname . "";
        $connectionString = 'odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=' . $dbpath . ';Uid=; Pwd=;';
        $db = new PDO($connectionString);
        foreach ($this->QueriesList as $query) {
            $this->querycount[] = $db->exec($query);
        }
        $db = null;
    }

    function uploadDB() {
        $allowedExts = array("accdb", "mdb");
        $temp = explode(".", $_FILES["dbfile"]["name"]);
        $extension = end($temp);
        if ($_FILES["dbfile"]["error"] > 0) {
            throw new Exception('Error while uploading Database');
        } else if (in_array($extension, $allowedExts)) {
            $this->emptyUploads();
            move_uploaded_file($_FILES["dbfile"]["tmp_name"], "uploads/" . $_FILES["dbfile"]["name"]);
            $this->dbname = $_FILES["dbfile"]["name"];
        } else {
            throw new Exception('Invalid file format');
        }
    }

    function emptyUploads() {
        $files = glob('uploads/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file))
                @unlink($file); // delete file
        }
    }

    function doStep3() {
        
    }

    function setPostvars() {
        $this->QueriesList = $_POST['queryarr'];
        if (isset($_POST['vendorarr'])) {
            $this->VendorExceptionslist = $_POST['vendorarr'];
        }
        //print_r($this->VendorExceptionslist);
        //print_r($this->QueriesList);
    }

    function doStep4() {

        $objPHPExcel = new PHPExcel();
$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
// Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");


// Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'warehouse id')
                ->setCellValue('B1', 'warehouse name')
                ->setCellValue('C1', 'qty')
                ->setCellValue('D1', 'cost')
                ->setCellValue('E1', 'product attribute:search group')
                ->setCellValue('F1', 'market auto rule')
                ->setCellValue('G1', 'amazon sale price exp')
                ->setCellValue('H1', 'list name')
                ->setCellValue('I1', 'po sources')
                ->setCellValue('J1', 'run amazon')
                ->setCellValue('K1', 'product custom sku')
                ->setCellValue('L1', 'product name')
                ->setCellValue('M1', 'asin')
                ->setCellValue('N1', 'amazon qty exp')
                ->setCellValue('O1', 'Amazon Comments')
                ->setCellValue('P1', 'Amazon Condition');

// Miscellaneous glyphs, UTF-8
        /*  $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A4', 'Miscellaneous glyphs')
          ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç'); */

// Rename worksheet
        $dbpath = dirname(dirname(__FILE__)) . "/uploads/" . $this->dbname . "";
        $connectionString = 'odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=' . $dbpath . ';Uid=; Pwd=;';
        $db = new PDO($connectionString);
        $result = $db->query("select * from Final_Table");
        $i = 2;
        while ($row = $result->fetch()) {
            // print_r($row);
            if ($row["IS_AID"] == "1") {
                $cost = $row['AID_COST'];
                $qty = $row['AID_NewINV'];
            } else {
                $cost = $row['SLD_COST'];
                $qty = $row['SLD_NewINV'];
            }

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueExplicit("A$i", $row['TRIMMED_PART'],PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValue("B$i", "Main")
                    ->setCellValue("C$i", $qty)
                    ->setCellValue("D$i", $cost)
                    ->setCellValue("E$i", "searcher_" . date('Y-m-d'))
                    ->setCellValue("F$i", "Amerisponse rule")
                    ->setCellValue("G$i", "cost")
                    ->setCellValue("H$i", "amazon")
                    ->setCellValue("I$i", "Main")
                    ->setCellValue("J$i", "Yes")
                    ->setCellValueExplicit("K$i", $row['TRIMMED_PART'],PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValue("L$i", $row['VENDOR_TITLE_NAME'] . " - " . $row['TRIMMED_PART'])
                    ->setCellValueExplicit("M$i", $row['AMZ_ASIN_1'],PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValue("N$i", "lq")
                    ->setCellValue("O$i", " ")
                    ->setCellValue("P$i", "New");
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('Step4-'.date('Y-m-d'));


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$objPHPExcel->setActiveSheetIndex(0);

/*
// Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Step4-'.date('Y-m-d').'"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
*/
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        //$objWriter->save($objWriter->save(str_replace('.php', '.xls', __FILE__)););
        $objWriter->save(dirname(dirname(__FILE__)) . "/uploads/Step4-".date('Y-m-d').".xlsx" );
        $this->step4excellink="uploads/Step4-".date('Y-m-d').".xlsx";
        $this->step3dblink =  "uploads/".$this->dbname;
        //exit;
    }

}
?>
