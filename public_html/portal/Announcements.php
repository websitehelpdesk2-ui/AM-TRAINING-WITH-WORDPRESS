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

function isValidDate($date, $format = 'Y-m-d')
{
	try 
	{
		$function_date = date($format, strtotime($date));
		return ($date == $function_date);
	}
	catch(Exception $e)
	{
		return false;
	}
	return true;	
}

$CURRENT_PAGE_NAME = "Announcements";
$csrf_token_tag = "";$nav_menu = $_SESSION["UserGroup"] . "_nav.php";
$error_tag = "";
$announcement_id = 0;
$announcement_title = "";
$announcement_description = "";
$announcement_start_date = null;
$announcement_end_date = null;
$announcement_active = 0;
$announcement_form = ""; 
$action = "S";
$action_form="N";
$error=false;
$result = null;
$Get_QS = array();
$array_length = 0;
$checked="checked";
$p_start_date = null;
$p_end_date = null;
$output = "";
$js = "";
$d_id = 0;
if ($_SERVER['REQUEST_METHOD']==='POST')
{
	if (isset($_POST["announcement_id"]))
	{
		$announcement_id = filter_var($_POST["announcement_id"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if (isset($_POST["announcement_title"]))
	{
		$announcement_title = filter_var($_POST["announcement_title"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if (isset($_POST["announcement_description"]))
	{	
		$announcement_description = filter_var($_POST["announcement_description"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);		
	}
	if (isset($_POST["announcement_start_date"]))
	{		
		$announcement_start_date = filter_var($_POST["announcement_start_date"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);				
		
	}
	if (isset($_POST["announcement_end_date"]))
	{
		$announcement_end_date = filter_var($_POST["announcement_end_date"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);				
			
	}	
	if (isset($_POST["announcement_active"]))
	{   
		$announcement_active = filter_var($_POST["announcement_active"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);		
		if ($announcement_active == "on")
		{
			$announcement_active = 1;
		}
		else
		{
			$announcement_active = 0;
		}
	}
	if (isset($_POST["announcement_form"]))
	{
		$announcement_form = filter_var($_POST["announcement_form"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	
	if ($announcement_form == "announcement_submit")
	{
		if ($announcement_id == "0")
		{
			$action = "A";
		}
		else
		{
			$action = "U";			
		}		
	
		$sql = "Call SP_Announcements(".$announcement_id.",'". $announcement_title . "','" . $announcement_description . "','". $announcement_start_date . "','" . $announcement_end_date . "'," . $announcement_active . ",'" . $action . "');";		
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
			$sql = "Call SP_Announcements(".$d_id.",'','','1900-01-01','1900-01-01',0,'D');";
			$result = mysqli_query($conn, $sql);
			header("Location: /portal/Announcements.php");
		}
	}
}

if ($announcement_start_date == null or $announcement_start_date == "") { $p_start_date = "1900-01-01"; } else { $p_start_date = $announcement_start_date; }
if ($announcement_end_date == null or $announcement_end_date == "") { $p_end_date = "1900-01-01"; } else { $p_end_date = $announcement_end_date; }
$action = "S";
$sql = "Call SP_Announcements(".$announcement_id.",'". $announcement_title . "','" . $announcement_description . "','". $p_start_date . "','" . $p_end_date . "'," . $announcement_active . ",'" . $action . "')";		
$result = mysqli_query($conn, $sql);	
while ($row = mysqli_fetch_row($result))
{	
   $announcement_id = $row[0];   
   $announcement_title = $row[1];   
   $announcement_description = $row[2];
   $announcement_start_date = $row[3];
   $announcement_end_date = $row[4];
   $announcement_active = $row[5];
   
   $output .= "<tr>";
   $output .= '<td><a id="a'.$announcement_id.'" href="javascript:void();" ><i class="bi bi-pencil"></i></a></td>';
   $output .= '<td>'.$announcement_title.'</td>';
   $output .= '<td>'.$announcement_description.'</td>';
   $output .= '<td>'.$announcement_start_date.'</td>';
   $output .= '<td>'.$announcement_end_date.'</td>';
   
   if ($announcement_active==1) 
   {
	   $output .= '<td>&nbsp;&nbsp;&nbsp;<i class="bi bi-check-square"></i></td>';
   }
   else
   {
	   $output .= '<td>&nbsp;&nbsp;&nbsp;<i class="bi bi-square"></i></td>';
   }
   $output .= '<td><a id="d'.$announcement_id.'" href="?D='.$announcement_id.'"><i class="bi bi-x-circle"></i></a></td>';
   $output .= "</tr>";
   
   $js .= "$('#a".$announcement_id."').click(function(){\r\n";   
   $js .= "  $('#announcement_id').val('".$announcement_id."');\r\n";
   $js .= "  $('#announcement_title').val('".$announcement_title."');\r\n";
   $js .= "  $('#announcement_description').val('".$announcement_description."');\r\n";   
   $js .= "  $('#announcement_start_date').val('".$announcement_start_date."');\r\n";
   $js .= "  $('#announcement_end_date').val('".$announcement_end_date."');\r\n";
   $js .= "  $('#announcement_active').prop('checked', ".($announcement_active==1).");\r\n";
   $js .= "  $('#announcement_submit').html('Update <i class=\"bi bi-pencil-fill\"></i>');\r\n";   
   $js .= "});\r\n\r\n";
   
   $js .= "$('#d".$announcement_id."').click(function(){\r\n"; 
   $js .= "  confirm('Are you sure you want to delete Announcement: ".$announcement_title."');\r\n";
   $js .= "});\r\n\r\n";
  
   
   
}  
	
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
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
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
	  <form class=" bg-light" method="POST">
		  <input type="hidden" name="announcement_id" class="form-control" id="announcement_id" value="0"/> 			  
		  <?= $csrf_token_tag ?>
		  <?= $error_tag ?>		  
		  <div class="form-group">
			<label for="announcement_title">Title</label>
            <input type="text" name="announcement_title" class="form-control" id="announcement_title" placeholder="Announcement Title" value="">            
          </div>
		  <div class="form-group">
			<label for="announcement_description">Description</label>
			<textarea class="form-control" id="announcement_description" name="announcement_description" rows="2"></textarea>
		  </div>
		  <div class="form-group" id="sandbox-container">
			<label for="announcement_start_date">Start Date</label>
            <input type="text" class="form-control" id="announcement_start_date" name="announcement_start_date" value="">
			<label for="announcement_end_date">End Date</label>
            <input type="text" class="form-control" id="announcement_end_date" name="announcement_end_date" value="">			
          </div>
		  <div class="form-check">			
            <input type="checkbox" class="form-check-input" id="announcement_active" name="announcement_active"  >            
			<label for="announcement_active"> Active</label>
          </div>
		  <div class="form-group">		  
			 <button type="submit" style="margin-top:10px;" class="btn btn-primary mb-2" id="announcement_submit" value="announcement_submit" name="announcement_form" >New <i class="bi bi-plus-circle-fill"></i></button> 			  
             
			 <button type="reset" style="margin-top:10px;" class="btn btn-secondary mb-2" id="announcement_reset" value="announcement_reset" name="announcement_form">Clear <i class="bi bi-stars"></i></button>            
          </div>
	  </form>
      </div>
    </nav>
	

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Announcements</h1>
        
      </div>

     
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
			  <th scope="col"><i class="bi bi-pencil-square"></i></th>
              <th scope="col">Title</th>
              <th scope="col">Description</th>
              <th scope="col">Start Date</th>
              <th scope="col">End Date</th>
              <th scope="col">Active</th>
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
			
			$('#sandbox-container input').datepicker({
			});
			
			$('#announcement_reset').click(function(){
				$('#announcement_submit').html('New <i class="bi bi-plus-circle-fill"></i>');
			});
					
			
	});		
	</script>	
  </body>
</html>
