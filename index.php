<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('memory_limit', '256M');
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

require_once 'includes/processDbClass.php';
require_once("Classes/PHPExcel.php");
$dbobj = new processDbClass();



$anError = true;
if (isset($_POST['submitform'])) {
    try {
        $dbobj->uploadDB();
        $dbobj->setPostvars();
        $dbobj->processVendorExceptions();
        $dbobj->processQueries();
        $anError = false;
        $dbobj->doStep4();
    } catch (Exception $e) {
        $exceptionmsg = 'Caught exception: ' . $e->getMessage() . "\n";
        $anError = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>test</title>

        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/custum.css" rel="stylesheet">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>

        <h1>Step 3 Beta</h1>
        <ul class="nav nav-tabs">
            <li class="active"><a href="#">Home</a></li>

        </ul>

        <div style="padding:6%">
            <?php if (!$anError) { ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Step-3 Report</h3>
                    </div>
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Download Files</h3>
                            </div>
                            <div class="panel-body">
                                <div class="btn-group btn-group-justified" style="padding-bottom: 15px;">
                                    <div class="btn-group">
                                        <a href="<?php echo $dbobj->step3dblink; ?>" download> <button type="button" class="btn btn-default">Step-3 Database Download</button></a>
                                    </div>
                                    <div class="btn-group">
                                        <a href="<?php echo $dbobj->step4excellink ?>" download> <button type="button" class="btn btn-default">Step4 Excel Download</button></a>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Query Report</h3>
                                </div>
                                <div class="panel-body">
                                    <ul class="list-group">

                                        <?php $i = 0;
                                        foreach ($dbobj->QueriesList as $val) {
                                            ?>
                                            <li class="list-group-item">
                                                <span class="badge"><?php echo $dbobj->querycount[$i] . " Effected"; ?></span>
                                            <?php echo $val; ?>
                                            </li>
                                            <?php $i++;
                                        }
                                        ?>

                                    </ul>
                                </div>
                            </div>
    <?php if (isset($dbobj->VendorExceptionslist)) { ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Vendor Report</h3>
                                    </div>
                                    <div class="panel-body">
                                        <ul class="list-group">

        <?php $i = 0;
        foreach ($dbobj->VendorExceptionslist as $val) {
            ?>
                                                <li class="list-group-item">
                                                    <span class="badge"><?php echo $dbobj->vendorcount[$i] . " Effected"; ?></span>
                                                <?php echo $val; ?>
                                                </li>
            <?php $i++;
        }
        ?>

                                        </ul>
                                    </div>
                                </div>
    <?php } ?>
                        </div>
                    </div>
<?php } ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Step 3 Form</h3>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-danger" style="<?php if (!isset($exceptionmsg)) { ?>display: none<?php } ?>" ><?php echo $exceptionmsg; ?></div>
                        <form class="navbar-form navbar-left" id="procform" role="search" method="post" enctype="multipart/form-data">
                            <input type="file" id="inp_file" name="dbfile" />
                            <div class="input-group queryinput">
                                <span class="input-group-addon">Query 1</span>
                                <input type="text" class="form-control" name="queryarr[]" placeholder="Enter Query" value="DELETE from Final_Table where  AID_LastUpdate is null AND SLD_LastUpdate is null">
                            </div>
                            <div class="input-group queryinput">
                                <span class="input-group-addon">Query 2</span>
                                <input type="text" class="form-control" name="queryarr[]" placeholder="Enter Query" value="delete  from Final_Table where  (SLD_COST <50 and AID_COST is null) OR  (AID_COST<50 AND SLD_COST is null) OR   (SLD_COST <50 AND AID_COST<50)">
                            </div>
                            <div class="input-group queryinput">
                                <span class="input-group-addon">Query 3</span>
                                <input type="text" class="form-control" name="queryarr[]" placeholder="Enter Query" value="Update Final_Table set SLD_NewINV=SLD_INV,AID_NewINV=AID_INV">
                            </div>
                            <div class="input-group queryinput">
                                <span class="input-group-addon">Query 4</span>
                                <input type="text" class="form-control" name="queryarr[]" placeholder="Enter Query" value="delete from Final_Table where (SLD_NewINV<10 AND AID_NewINV<10) OR (SLD_NewINV<10 AND  AID_NewINV is null) OR  (AID_NewINV<10 AND SLD_NewINV is null)">
                            </div>
                            <div class="input-group queryinput">
                                <span class="input-group-addon">Query 5</span>
                                <input type="text" class="form-control" name="queryarr[]" placeholder="Enter Query" value="update Final_Table set  SLD_NewINV=SLD_NewINV/2,AID_NewINV=AID_NewINV/2 ">
                            </div> <div class="input-group queryinput">
                                <span class="input-group-addon">Query 6</span>
                                <input type="text" class="form-control" name="queryarr[]" placeholder="Enter Query" value="update Final_Table set IS_AID='1' where AID_COST<SLD_COST AND AID_NewINV<>0 AND AID_NewINV is not null  ">
                            </div>
                            <div class="input-group queryinput">
                                <span class="input-group-addon">Query 7</span>
                                <input type="text" class="form-control" name="queryarr[]" placeholder="Enter Query" value="update Final_Table set IS_SLD='1' where SLD_COST<AID_COST AND SLD_NewINV<>0 AND SLD_NewINV is not null ">
                            </div>
                            <div class="input-group queryinput">
                                <span class="input-group-addon">Query 8</span>
                                <input type="text" class="form-control" name="queryarr[]" placeholder="Enter Query" value="update Final_Table set IS_SLD='1' where AID_NewINV=0 OR  AID_NewINV is null">
                            </div>
                            <div class="input-group queryinput">
                                <span class="input-group-addon">Query 9</span>
                                <input type="text" class="form-control" name="queryarr[]" placeholder="Enter Query" value="update Final_Table set IS_AID='1' where SLD_NewINV=0 OR  SLD_NewINV is null">
                            </div>
                            <div class="input-group queryinput">
                                <span class="input-group-addon">Query 10</span>
                                <input type="text" class="form-control" name="queryarr[]" placeholder="Enter Query" value="DELETE *
                                       FROM final_table 
                                       WHERE TRIMMED_PART In ('ZWSTAT','VLB7IR','VISTAGSM4G','TUXWIFIS','TUXW','TUXS','PX4H25','PRO22R2','PC2RK','P2WK','OP40HONR','OP30HONR','OP10HONR','L65UX32D','L3000SIA','IGSMV4G','GSMX4GTC2','GSMVLP4G','EPDD','EIDC2B','E270','DWCV4363D','DTK2MHLP12BWB','DS301SET','CM9080','CM420PTE','BATTBOX','908REXMOX32','8310DSS28','5802WXT','5800RPS','520030404','5140DLM','449CSRT','301410','1508AQN5','HD3HRS','SCD2010','HD3US','ERLY','PSN106BLACK','36103','285001','FR-EY2T-MAWB','N750','16PS42PVD','5502491','DHFM124120','EBPS6A','WG4RFHVMC','1505AQN5','DWCD2365T','MAX3MODEX','PRX2','SCV2060','UD1000','YRL220ZW605','296006','70003042','AL600ULXPD8','EFSC1004R','EFSC502R','ES4200K4T1','OM30BHONC','PFC6030','TVP12DN','VISTAKEY','5395C1100','EBPS10A','ES4200K1T1','445004','232CVS','8100P','837528','CC3008','CXEPD2010L','DBPSAKIT','RLPPL65UFB','SL5AB','125XBRIRGA120AB','RP600PG20100972','295097','KC10X50','NXTC50','NXTK50','205671','116EXC','CM5020RE','EFSC302R','SPSCWHKP','AD102S','AD102SF','DHR24120C','2011DSMUS28','55ABCU','CZP36','DCM4','DHS1224C','FSRA10C','VD600TDNW','8100T','CELLULARBINDER','CM120WV2','OM40BHONC','5700371','CHVD37PB','FSRRM24','406001','C4WITARBA','CXEL3015','NCPCI3','515001','CXEPD2850L','DWCV4365T','EFSA250RD','EFSA64RD','371001','411001','5502791','PKT10X50','R990RMO8','SA232','UAS20826','512001','ZCD8312NBA','36102','125101','SL5AR','230219P','SAETH','302244W','SD4WJ','CHVD37PS','RRDP3BRLLWH','1501AQN5','6520','8100PY','DWCD362D','P2RHK','SD2W','PC2RHK','6536G5','SPSCWH','VISTA50P','OP10GENR','SVWMT','351001','ILP5','NXT5R','930719KFB','DWCV4382TIR','ENAC','NX6KIT','5395CB100','ASB','DUS97BP','EISO','352004','YRD240ZW619','36101','524001','BST2MOC','84009','CM5085GPTE','369001','84018','BSSE2','ERLCDC','PC2WK','YRL210ZW0BP','VSR6','YRD240ZW0BP','DWCBL352IR','PTSC','8371SCS28','PTPROX25','EPD','PSDALOB','SEPSPSW','YRL210ZW619','84016','DUS9710BP','YRD210ZW0BP','EPHD','TVCPIR2HR','WPS3','84017','TK3400U4P','84008','7114X05DX32D','8310X28','SIO2','YRL220ZW619','200701','EIDCWS','S6514X32D','YRL220ZW0BP');
                                       ">
                            </div>
                            <div class="input-group queryinput">
                                <span class="input-group-addon">Query 10</span>
                                <input type="text" class="form-control" name="queryarr[]" placeholder="Enter Query" value="delete from final_table where AMZ_ASIN_1 is null">
                            </div>





                            <div class="panel panel-default vendorpane" style="display: block">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Vendor Exceptions</h3>
                                </div>
                                <div class="panel-body">
                                    <div class='input-group vendorinput'>
                                        <span class='input-group-addon'>Vendor 1</span>
                                        <input type='text' name='vendorarr[]' class='form-control' value="Klipsch Group" placeholder='Enter Vendor'>
                                    </div>
                                    <div class='input-group vendorinput'>
                                        <span class='input-group-addon'>Vendor 2</span>
                                        <input type='text' name='vendorarr[]' class='form-control' value="Luxul Wireless" placeholder='Enter Vendor'>
                                    </div>
                                    <div class='input-group vendorinput'>
                                        <span class='input-group-addon'>Vendor 2</span>
                                        <input type='text' name='vendorarr[]' class='form-control' value="Mi Casa Verde" placeholder='Enter Vendor'>
                                    </div>
                                </div> 
                            </div>
                            <div class="btn-group" style="padding-top: 11px;">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#" id ="submitbtn">Submit</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#" id ="addQuery" >Add Query</a></li>
                                    <li><a href="#" id ="addVendorExp" >Add Vendor Exception</a></li>

                                </ul>
                            </div>
                            <input type="hidden" name="submitform" value="submit" />
                        </form>
                    </div>
                </div>


            </div>




            <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
            <!-- Include all compiled plugins (below), or include individual files as needed -->
            <script src="js/bootstrap.min.js"></script>
            <script src="js/functions.js"></script>
    </body>
</html>

