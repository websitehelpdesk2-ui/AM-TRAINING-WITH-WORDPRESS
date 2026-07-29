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
include('../../private/connect_sp2.php');
include('../../private/connect_sp3.php');
require('../../private/csrf_token.php');

function isValidDate($date, $format = 'Y-m-d')
{
	if ($date == null) { return false; }
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


function mdY2Ymd($date)
{	
	if (empty($date)) {  return ""; }
	$dateArray = explode('/',$date);
	$result = $dateArray[2]."-".$dateArray[0]."-".$dateArray[1];
	return $result;
}
function Ymd2mdY($date)
{		
	if (empty($date)) {  return ""; }
	$dateTimeArray = explode(' ',$date);
	$dateArray = explode('-',$dateTimeArray[0]);
	return $dateArray[1]."/".$dateArray[2]."/".$dateArray[0];
}


$CURRENT_PAGE_NAME = "Users";
$csrf_token_tag = "";$nav_menu = $_SESSION["UserGroup"] . "_nav.php";
$error_tag = "";
$user_id = 0;
$state_id = 0;
$ag_id = 0;
$user_login = "";
$user_password = "";
$user_name = "";
$user_email = "";
$user_phone = "";
$user_birthdate = null;
$user_address = "";
$user_zip = 0;
$user_active = 0;
$user_form = ""; 
$action = "S";
$action_form="N";
$error=false;
$result = null;
$Get_QS = array();
$array_length = 0;
$checked="checked";
$p_user_birthdate  = null;
$output = "";
$js = "";
$state_options = "";
$ag_options = "";
$d_id = 0;
$result2 = "";
if ($_SERVER['REQUEST_METHOD']==='POST')
{
	if (isset($_POST["user_id"]))
	{
		$user_id = filter_var($_POST["user_id"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if (isset($_POST["state_id"]))
	{
		$state_id = filter_var($_POST["state_id"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if (isset($_POST["ag_id"]))
	{
		$ag_id = filter_var($_POST["ag_id"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if (isset($_POST["user_login"]))
	{
		$user_login = filter_var($_POST["user_login"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if (isset($_POST["user_name"]))
	{	
		$user_name = filter_var($_POST["user_name"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);		
	}
	if (isset($_POST["user_phone"]))
	{
		$user_phone = filter_var($_POST["user_phone"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if (isset($_POST["user_address"]))
	{	
		$user_address = filter_var($_POST["user_address"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);		
	}
	if (isset($_POST["user_zip"]))
	{	
		$user_zip = filter_var($_POST["user_zip"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);		
	}
	if (isset($_POST["user_birthdate"]))
	{
		$user_birthdate = filter_var($_POST["user_birthdate"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);	
	
		if (isValidDate($user_birthdate,'m/d/Y'))
		{
			$user_birthdate = mdY2Ymd($user_birthdate);
		}
		else
		{
			$user_birthdate = date_create('now')->format('Y-m-d');
		}	
	}	
	if (isset($_POST["user_active"]))
	{   
		$user_active = filter_var($_POST["user_active"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);		
		if ($user_active == "on")
		{
			$user_active = 1;
		}
		else
		{
			$user_active = 0;
		}
	}
	if (isset($_POST["user_form"]))
	{
		$user_form = filter_var($_POST["user_form"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	
	if ($user_form == "user_submit")
	{
		if ($user_id == "0")
		{
			$action = "A";
		}
		else
		{
			$action = "U";			
		}
			
	    $user_email = $user_login;
		$sql = "Call SP_users(".$user_id.",'". $user_name . "','" . $user_login . "','". $user_email . "','" . $user_phone . "','" . $user_birthdate . "','" . $user_address . "','" . $user_zip . "','" . $user_active . "','" . $state_id . "','" . $ag_id . "','" . $action . "');";		
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
			$sql = "Call SP_users(".$d_id.",'','','','','1900-01-01','','',0,0,0,'D');";
			$result = mysqli_query($conn, $sql);
			header("Location: /portal/users.php");
		}
	}
}

if ($user_birthdate == null or $user_birthdate == "") { $user_birthdate = "1900-01-01"; } else { $p_user_birthdate = $user_birthdate; }
$action = "S";
$sql = "Call SP_users(".$user_id.",'". $user_name . "','" . $user_login . "','". $user_email . "','" . $user_phone . "','" . $p_user_birthdate . "','" . $user_address . "','" . $user_zip . "','" . $user_active . "','" . $state_id . "','" . $ag_id . "','" . $action . "');";		
$result2 = $sql;
$result = mysqli_query($conn, $sql);	
while ($row = mysqli_fetch_row($result))
{	
   $user_id = $row[0];   
   $state_id = $row[1];   
   $state_name = $row[2];
   $user_name = $row[3];
   $user_login = $row[4];
   $user_email = $row[5];
   $user_phone = $row[6];
   $user_birthdate = Ymd2mdY($row[7]);
   $user_address = $row[8];
   $user_zip = $row[9];
   $ag_id = $row[10];
   $ag_name = $row[11];
   $user_active = $row[12];
   
   $output .= "<tr>";
   $output .= '<td><a id="a'.$user_id.'" href="javascript:void();" ><i class="bi bi-pencil"></i></a></td>';
   $output .= '<td>'.$user_name.'</td>';
   $output .= '<td>'.$user_login.'</td>';
   $output .= '<td>'.$user_birthdate.'</td>';
   $output .= '<td>'.$user_phone.'</td>';
   $output .= '<td>'.$user_address.'</td>';
   $output .= '<td>'.$state_name.'</td>';
   $output .= '<td>'.$user_zip.'</td>';
   $output .= '<td>'.$ag_name.'</td>';
   
   if ($user_active==1) 
   {
	   $output .= '<td>&nbsp;&nbsp;&nbsp;<i class="bi bi-check-square"></i></td>';
   }
   else
   {
	   $output .= '<td>&nbsp;&nbsp;&nbsp;<i class="bi bi-square"></i></td>';
   }
   $output .= '<td><a id="d'.$user_id.'" href="?D='.$user_id.'"><i class="bi bi-x-circle"></i></a></td>';
   $output .= "</tr>";
   
   $js .= "$('#a".$user_id."').click(function(){\r\n";   
   $js .= "  $('#user_id').val('".$user_id."');\r\n";
   $js .= "  $('#user_name').val('".$user_name."');\r\n";
   $js .= "  $('#user_login').val('".$user_login."');\r\n";   
   $js .= "  $('#user_birthdate').val('".$user_birthdate."');\r\n";
   $js .= "  $('#user_phone').val('".$user_phone."');\r\n";
   $js .= "  $('#user_address').val('".$user_address."');\r\n";
   $js .= "  $('#state_id').val('".$state_id."');\r\n";
   $js .= "  $('#user_zip').val('".$user_zip."');\r\n";
   $js .= "  $('#ag_id').val('".$ag_id."');\r\n";
   $js .= "  $('#user_active').prop('checked', ".($user_active==1).");\r\n";
   $js .= "  $('#user_submit').html('Update <i class=\"bi bi-pencil-fill\"></i>');\r\n";   
   $js .= "});\r\n\r\n";
   
   $js .= "$('#d".$user_id."').click(function(){\r\n"; 
   $js .= "  confirm('Are you sure you want to delete User: ".$user_name."');\r\n";
   $js .= "});\r\n\r\n";
   
}  
mysqli_free_result($result);

$sql = "Call Get_States();";		
$result = mysqli_query($conn2, $sql);	
while ($row = mysqli_fetch_row($result))
{
	$state_options .= '<option value="'.$row[0].'">'.$row[1].'</option>';
}
mysqli_free_result($result);

$sql = "Call Get_Admin_Groups();";		
$result = mysqli_query($conn3, $sql);	
while ($row = mysqli_fetch_row($result))
{
	$ag_options .= '<option value="'.$row[0].'">'.$row[1].'</option>';
}
mysqli_free_result($result);
mysqli_close($conn);
mysqli_close($conn2);
mysqli_close($conn3);
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
		  <input type="hidden" name="user_id" class="form-control" id="user_id" value="0"/> 			  
		  <?= $csrf_token_tag ?>
		  <?= $error_tag ?>		  
		  <div class="form-group">
			<label for="user_name">Name</label>
            <input type="text" name="user_name" class="form-control" id="user_name" placeholder="Full Name" value="" required>            
          </div>
		   <div class="form-group">
			<label for="user_login">Email/Login</label>
            <input type="email" name="user_login" class="form-control" id="user_login" placeholder="Email/Login" value="" required>            
          </div>
		  <div class="form-group" id="sandbox-container">
			<label for="user_birthdate">Birthdate</label>
            <input type="text" class="form-control" id="user_birthdate" name="user_birthdate" value="" required>			
          </div>
		  <div class="form-group">
			<label for="user_phone">Phone</label>
            <input type="text" name="user_phone" class="form-control" id="user_phone" placeholder="Phone Number" value="" required>            
          </div>
		  <div class="form-group">
			<label for="user_address">Address</label>
			<textarea class="form-control" id="user_address" name="user_address" rows="2" required></textarea>
		  </div>
		  <div class="form-group">
			<label for="state_id">State</label>
			<select class="form-control" id="state_id" name="state_id" required><?=$state_options?></select>
		  </div>
		  <div class="form-group">
			<label for="user_zip">Zip</label>
			<input type="text" name="user_zip" class="form-control" id="user_zip" placeholder="#####" value="" required>            
		  </div>
		 <div class="form-group">
			<label for="ag_id">User Group</label>
			<select class="form-control" id="ag_id" name="ag_id" required><?=$ag_options?></select>
		  </div>
		  
		  
		  <div class="form-check">			
            <input type="checkbox" class="form-check-input" id="user_active" name="user_active" >            
			<label for="user_active"> Active</label>
          </div>
		  <div class="form-group">		  
			 <button type="submit" style="margin-top:10px;" class="btn btn-primary mb-2" id="user_submit" value="user_submit" name="user_form" >New <i class="bi bi-plus-circle-fill"></i></button> 			  
             
			 <button type="reset" style="margin-top:10px;" class="btn btn-secondary mb-2" id="user_reset" value="user_reset" name="user_form">Clear <i class="bi bi-stars"></i></button>            
          </div>
	  </form>
      </div>
    </nav>
	

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">users</h1>
        <a href="" class="btn btn-primary">All Users</a>
      </div>

     
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
			  <th scope="col"><i class="bi bi-pencil-square"></i></th>
              <th scope="col">Name</th>
              <th scope="col">Email/Login</th>
              <th scope="col">Birthdate</th>
              <th scope="col">Phone</th>
			  <th scope="col">Address</th>
			  <th scope="col">State</th>
			  <th scope="col">Zip</th>
			  <th scope="col">Group</th>
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
			
			$('#user_reset').click(function(){
				$('#user_submit').html('New <i class="bi bi-plus-circle-fill"></i>');
			});
					
			
	});		
	</script>	
  </body>
</html>
