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
require('../../private/csrf_token.php');
function base64_url_encode($input)
{
    return strtr(base64_encode($input), '+/=', '-_,');
}

function base64_url_decode($input)
{
    return base64_decode(strtr($input, '-_,', '+/='));
}
$CURRENT_PAGE_NAME = "Registration";
$csrf_token_tag = "";$nav_menu = $_SESSION["UserGroup"] . "_nav.php";
$error_tag = "";
$registration_id = 0;
$program_id = 0;
$program_name = "";
$program_option = "";
$user_name = "";
$user_email = "";
$user_phone = "";
$registration_date = null;
$registration_activated = null;
$sql = "";
$error=false;
$result = null;
$result2 = null;
$output = "";
$output2 = "";
$js = "";
$position = 0;
$position2 = 0;
if ($_SERVER['REQUEST_METHOD']==='POST')
{
	
	if (isset($_POST["user_email"]))
	{	
		$user_email = filter_var($_POST["user_email"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);		
	}
				
	$sql = "Call Get_Pending_Registration_Users('". $user_email . "');";		
	$result = mysqli_query($conn, $sql);	
	$sql = "Call Get_Registrated_Users('". $user_email . "');";		
	$result2 = mysqli_query($conn2, $sql);	
	
}
else
{	    
	$sql = "Call Get_Pending_Registration_Users('');";
	$result = mysqli_query($conn, $sql);
	$sql = "Call Get_Registrated_Users('');";
	$result2 = mysqli_query($conn2, $sql);
}

	
while ($row = mysqli_fetch_row($result))
{	
   $registration_id = $row[0];   
   $program_id = $row[1];   
   $program_name = $row[2];   
   $program_option = $row[3];
   $user_name = $row[4];
   $registration_date = $row[5];
   $user_email = $row[6];
   $user_phone = $row[7];
   
   $position = strpos($program_option, "cost:") + 5;
   $position2 = strpos($program_option, "|");
   $program_option = substr($program_option, $position, $position2-$position);
   $output .= "<tr>";  
   $output .= '<td>'.$registration_date.'</td>';
   $output .= '<td>'.$program_name.'</td>';
   $output .= '<td>$'.$program_option.'</td>';
   $output .= '<td>'.$user_name.'</td>';
   $output .= '<td>'.$user_email.'</td>';
   $output .= '<td>'.$user_phone.'</td>';
   
   $output .= '<td id="td'.$registration_id.'"><button type="button" class="btn btn-primary" id="a'.$registration_id.'" value="'.$registration_id.'" name="activate_program" >Activate <i class="bi bi-star-fill"></i></button></td>';
   $output .= "</tr>";
   
   $js .= "$('#a".$registration_id."').click(function(){\r\n";   
   $js .= "   $('#td".$registration_id."').html('<img src=loading.gif width=32 height=32>');\r\n";  
   $js .= "   $.ajax({url:'register.php?".base64_url_encode($registration_id)."', success: function(result){\r\n";
   $js .= "    $('#td".$registration_id."').html(result);\r\n";   
   $js .= "   }});\r\n\r\n";
   $js .= "});\r\n\r\n";   
} 

while ($row = mysqli_fetch_row($result2))
{	
   $registration_id = $row[0];   
   $program_id = $row[1];   
   $program_name = $row[2];   
   $program_option = $row[3];
   $user_name = $row[4];
   $registration_date = $row[5];
   $user_email = $row[6];
   $user_phone = $row[7];
   $registration_activated = $row[8];
   
   $position = strpos($program_option, "cost:") + 5;
   $position2 = strpos($program_option, "|");
   $program_option = substr($program_option, $position, $position2-$position);
   $output2 .= "<tr>";  
   $output2 .= '<td>'.$registration_date.'</td>';
   $output2 .= '<td>'.$program_name.'</td>';
   $output2 .= '<td>$'.$program_option.'</td>';
   $output2 .= '<td>'.$user_name.'</td>';
   $output2 .= '<td>'.$user_email.'</td>';
   $output2 .= '<td>'.$user_phone.'</td>';
   $output2 .= '<td>'.$registration_activated.'</td>';
      
   $output .= "</tr>";
   
    
}  
mysqli_free_result($result);
mysqli_free_result($result2);
mysqli_close($conn);
mysqli_close($conn2);

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
    
	<script src="../js/jquery-3.7.1.min.js"></script>
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
			<label for="user_email">Student Email</label>
            <input type="text" name="user_email" class="form-control" id="user_email" placeholder="Email" value="" required>            
          </div>
		
		  <div class="form-group">		  
			 <button type="submit" style="margin-top:10px;" class="btn btn-primary mb-2" id="user_submit" value="user_submit" name="user_form" >Search <i class="bi bi-search"></i></button> 			  
             
			 <button type="reset" style="margin-top:10px;" class="btn btn-secondary mb-2" id="user_reset" value="user_reset" name="user_form">Clear <i class="bi bi-stars"></i></button>            
          </div>
	  </form>
      </div>
    </nav>
	

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Registration</h1>
        <a href="" class="btn btn-primary">All</a>
      </div>

     
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>			  
			  <th scope="col">Registration Date</th>
              <th scope="col">Program</th>
              <th scope="col">Options</th>
              <th scope="col">Student</th>              
			  <th scope="col">Email</th>
			  <th scope="col">Phone</th>			  
			  <th scope="col"><i class="bi bi-backpack4-fill"></i>Activate</th>
            </tr>
          </thead>		  
          <tbody>				 
		  <?=$output?> 			
          </tbody>
        </table>		
      </div>
	  
	  <h1 class="h2">Registered</h1>
	  <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>			  
			  <th scope="col">Registration Date</th>
              <th scope="col">Program</th>
              <th scope="col">Options</th>
              <th scope="col">Student</th>              
			  <th scope="col">Email</th>
			  <th scope="col">Phone</th>	
			  <th scope="col">Activated</th>			  
            </tr>
          </thead>		  
          <tbody>				 
		  <?=$output2?> 			
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
			
			
					
			
	});		
	</script>	
  </body>
</html>
