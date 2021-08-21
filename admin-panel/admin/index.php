<?php
session_start();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT');

require '../config.php';
require_once (INCLUDES.'Floral.php');

$params = array_merge($_GET,$_POST);
$obj    = new Floral();
ob_start();

$case = (!empty($_GET['case']) ? trim($_GET['case']) : 'home');
	   
include(TEMPLATE_PATH.'header.php');

/* Redirect page on email session */
if(empty($_GET['case'])){
	header("location: index.php?case=home");
}

?>
 
<body>
  <div id="wrapper" class="<?php echo $case; ?>">
    <?php
       switch($case)
        {
			case 'home':

				require_once(TEMPLATE_PATH.'home.php');

			break;

			default:

		}
        ?>
        <!-- /#page-wrapper -->
 </div>
    <!-- /#wrapper -->
 
  <?php  include(TEMPLATE_PATH.'footer.php');?>

    
