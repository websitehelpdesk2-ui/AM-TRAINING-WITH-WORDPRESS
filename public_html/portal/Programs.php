<?php
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
session_start();
if(!isset($_SESSION["UserID"]))
{
	header("Location: /login/");
}
include('../../private/connect_sp.php');
require('../../private/csrf_token.php');

$CURRENT_PAGE_NAME = "Programs";
$csrf_token_tag = "";$nav_menu = $_SESSION["UserGroup"] . "_nav.php";
$error_tag = "";
$program_id = 0;
$program_name = ""; 
$program_description = ""; 
$program_form = ""; 
$action = "S";
$action_form="N";
$error=false;
$result = null;
$Get_QS = array();
$array_length = 0;
$output = "";
$js = "";
$d_id = 0;
if ($_SERVER['REQUEST_METHOD']==='POST')
{	
	if (isset($_POST["program_id"]))
	{
		$program_id = filter_var($_POST["program_id"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if (isset($_POST["program_name"]))
	{
		$program_name = filter_var($_POST["program_name"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if (isset($_POST["program_description"]))
	{
		$program_description = filter_var($_POST["program_description"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	
	if (isset($_POST["program_form"]))
	{
		$program_form = filter_var($_POST["program_form"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if ($program_form == "program_submit")
	{
		if ($program_id == "0")
		{
			$action = "A";
		}
		else
		{
			$action = "U";			
		}
				    
		$sql = "Call SP_programs(".$program_id.",'". $program_name . "','" . $program_description . "','" . $action . "');";		
		$result = mysqli_query($conn, $sql);				
	}
}
else
{	
    if (isset($_GET["D"]))
	{
		$d_id = htmlspecialchars($_GET["D"] ?? '');			
		if (is_numeric($d_id))
		{
			$sql = "Call SP_programs(".$d_id.",'','','D');";
			$result = mysqli_query($conn, $sql);
			header("Location: /portal/programs.php");
		}
	}
}

$action = "S";
$sql = "Call SP_programs(".$program_id.",'". $program_name . "','" . $program_description . "','" . $action . "');";		
$result = mysqli_query($conn, $sql);	
while ($row = mysqli_fetch_row($result))
{	
   $program_id = $row[0];      
   $program_name = $row[1];
   $program_description = $row[2];   
   
   $output .= "<tr>";
   $output .= '<td><a id="a'.$program_id.'" href="javascript:void();" ><i class="bi bi-pencil"></i></a></td>';
   $output .= '<td>'.$program_name.'</td>';
   $output .= '<td>'.$program_description.'</td>';   
   $output .= '<td><a id="d'.$program_id.'" href="?D='.$program_id.'"><i class="bi bi-x-circle"></i></a></td>';
   $output .= "</tr>";
   
   $js .= "$('#a".$program_id."').click(function(){\r\n";   
   $js .= "  $('#program_id').val('".$program_id."');\r\n";
   $js .= "  $('#program_name').val('".$program_name."');\r\n";
   $js .= "  $('#program_description').val('".$program_description."');\r\n";      
   $js .= "  $('#program_submit').html('Update <i class=\"bi bi-pencil-fill\"></i>');\r\n";   
   $js .= "});\r\n\r\n";
   
   $js .= "$('#d".$program_id."').click(function(){\r\n"; 
   $js .= "  confirm('Are you sure you want to delete User: ".$program_name."');\r\n";
   $js .= "});\r\n\r\n";
   
}  

mysqli_free_result($result);
mysqli_close($conn);

$csrf_token_tag = csrf_token_tag($CURRENT_PAGE_NAME);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">      
    <title>AM Training Institute | Portal</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/navbar-fixed/">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
	
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/dropdowns.css" rel="stylesheet">
<link href="../css/form-validation.css" rel="stylesheet">
<link id="bs-css" href="../css/bootstrap.min.css" rel="stylesheet">
<link id="bsdp-css" href="../css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-program-select: none;
        -moz-program-select: none;
        program-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      .b-example-divider {
        height: 3rem;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
      }

      .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
      }

      .bi {
        vertical-align: -.125em;
        fill: currentColor;
      }

      .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
      }

      .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
      }
    </style>
    <link href="navbar-top-fixed.css" rel="stylesheet">
	<script src="../js/jquery-3.4.1.slim.min.js"></script>
	<script src="../js/bootstrap-datepicker.min.js"></script>
  </head>
  <body>
<?php include $nav_menu; ?>   
<div class="alert alert-light" role="alert"><img src="" height="20" width="1"></div> 
<div class="container-fluid ">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3 sidebar-sticky" style="margin-top:50px;">
	  <form class="bg-light" method="POST">
		  <input type="hidden" name="program_id" class="form-control" id="program_id" value="0"/> 			  
		  <?= $csrf_token_tag ?>
		  <?= $error_tag ?>		  
		  <div class="form-group">
			<label for="program_name">Name</label>
            <input type="text" name="program_name" class="form-control" id="program_name" placeholder="Program Name" value="" required>            
          </div>		   
		  <div class="form-group">
			<label for="program_description">Description</label>
			<textarea class="form-control" id="program_description" name="program_description" rows="2"></textarea>
		  </div>
		  
		  <div class="form-group">		  
			 <button type="submit" style="margin-top:10px;" class="btn btn-primary mb-2" id="program_submit" value="program_submit" name="program_form" >New <i class="bi bi-plus-circle-fill"></i></button> 			  
             
			 <button type="reset" style="margin-top:10px;" class="btn btn-secondary mb-2" id="program_reset" value="program_reset" name="program_form">Clear <i class="bi bi-stars"></i></button>            
          </div>
	  </form>
      </div>
    </nav>
	

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Programs</h1>
        <a href="/portal/Programs.php" class="btn btn-primary">All Programs</a>
      </div>

     
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
			  <th scope="col"><i class="bi bi-pencil-square"></i></th>
              <th scope="col">Name</th>       
			  <th scope="col">Description</th>			  
			  <th scope="col"><i class="bi bi-x-circle-fill"></i></th>
            </tr>
          </thead>		  
          <tbody>				 
		  <?=$output?> 			
          </tbody>
        </table>
		
      </div>
    </main>
	</div>
</div>
  
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/sidebars.js"></script>
    <script src="../js/form-validation.js"></script> 
    <script type="text/javascript">
	$( document ).ready(function() {
		    <?=$js ?>	
				
			$('#program_reset').click(function(){
				$('#program_submit').html('New <i class="bi bi-plus-circle-fill"></i>');
			});
	});		
	</script>	
  </body>
</html>
