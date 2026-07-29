<?php
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
session_start();
$_SESSION["Source"]="registration";
include('../../private/connect_sp.php');
require('../../private/csrf_token.php');
$CURRENT_PAGE_NAME = "Registration";
$csrf_token_tag = "";
$program_id = 0;
$program_name = "";
$option1 = "";
$option1_cost = 0;
$option2 = "";
$option2_cost = 0;
$option3 = "";
$option3_cost = 0;
$output = "";
$js = "";
$nav_menu = '<a class="py-2 text-dark text-decoration-none" href="/login/">Login</a>';
if(isset($_SESSION["UserName"]))
{
	$nav_menu = '<a class="py-2 text-dark text-decoration-none">'.$_SESSION["UserName"].'</a>';
}


$sql = "Call Get_Program_List();";		
$result = mysqli_query($conn, $sql);	
while ($row = mysqli_fetch_row($result))
{	
	$program_id = $row[0];   
	$program_name = $row[1]; 
	$program_code = $row[2];
	$option1 = $row[3]; 
	$option1_cost = $row[6]; 
	$option2 = $row[4]; 
	$option2_cost = $row[7]; 
	$option3 = $row[5]; 
	$option3_cost = $row[8];
     
	$js .= '$("#'.$program_id.'-1").click(function(){ ';   
    $js .= "  $('#program_id').val('".$program_id."');\r\n ";
    $js .= "  $('#_option').val('1');\r\n ";	
	$js .= "});\r\n\r\n";
	$js .= '$("#'.$program_id.'-2").click(function(){ ';   
    $js .= "  $('#program_id').val('".$program_id."');\r\n ";
    $js .= "  $('#_option').val('2');\r\n ";	
	$js .= "});\r\n\r\n";
	$js .= '$("#'.$program_id.'-3").click(function(){ ';   
    $js .= "  $('#program_id').val('".$program_id."');\r\n ";
    $js .= "  $('#_option').val('3');\r\n ";	
	$js .= "});\r\n\r\n";
	
	
	$output .= '<div class="row row-cols-1 row-cols-md-3 mb-3 text-center">';
	$output .= '      <div class="col">';
	$output .= '        <div class="card mb-4 rounded-3 shadow-sm">';
	$output .= '          <div class="card-header py-3">';
	$output .= '            <h4 class="my-0 fw-normal">'.$program_code.'</h4>';
	$output .= '          </div>';
	$output .= '          <div class="card-body">';
	$output .= '            <h1 class="card-title pricing-card-title">$'.$option1_cost.'</h1>';
	$output .= '            <ul class="list-unstyled mt-3 mb-4">';
	$output .= '              <li><strong>'.$program_name.'</strong></li>';
	$output .= '              '.$option1;
	$output .= '            </ul>';
	$output .= '            <button type="submit" class="w-100 btn btn-lg btn-outline-primary" id="'.$program_id.'-1">Register</button>';
	$output .= '          </div>';
	$output .= '        </div>';
	$output .= '      </div>';
	
    if (trim($option2) != "") 
	{
	$output .= '      <div class="col">';
	$output .= '        <div class="card mb-4 rounded-3 shadow-sm">';
	$output .= '          <div class="card-header py-3">';
	$output .= '            <h4 class="my-0 fw-normal">'.$program_code.'</h4>';
	$output .= '          </div>';
	$output .= '          <div class="card-body">';
	$output .= '            <h1 class="card-title pricing-card-title">$'.$option2_cost.'</h1>';
	$output .= '            <ul class="list-unstyled mt-3 mb-4">';
	$output .= '              <li><strong>'.$program_name.'</strong></li>';
	$output .= '              '.$option2;
	$output .= '            </ul>';
	$output .= '            <button type="submit" class="w-100 btn btn-lg btn-primary" id="'.$program_id.'-2">Register</button>';
	$output .= '          </div>';
	$output .= '        </div>';
	$output .= '      </div>';
	}
	
	if (trim($option3) != "" )
	{
	$output .= '      <div class="col">';
	$output .= '        <div class="card mb-4 rounded-3 shadow-sm border-primary">';
	$output .= '          <div class="card-header py-3 text-bg-primary border-primary">';
	$output .= '            <h4 class="my-0 fw-normal">'.$program_code.'</h4>';
	$output .= '          </div>';
	$output .= '          <div class="card-body">';
	$output .= '            <h1 class="card-title pricing-card-title">$'.$option3_cost.'</h1>';
	$output .= '            <ul class="list-unstyled mt-3 mb-4">';
	$output .= '              <li><strong>'.$program_name.'</strong></li>';
	$output .= '              '.$option3;
	$output .= '            </ul>';
	$output .= '            <button type="submit" class="w-100 btn btn-lg btn-primary" id="'.$program_id.'-3">Register</button>';
	$output .= '          </div>';
	$output .= '        </div>';
	$output .= '      </div>';
	}
	$output .= '    </div>';
	
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
<div class="container py-3">
  <header>
    <div class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom">
      <a href="/" class="d-flex align-items-center text-dark text-decoration-none">
        <img src="https://amtraininginstitute.org/wp-content/uploads/2024/02/cropped-logo1-1.jpg" alt="AMTRAINING INSTITUTE" width="48" height="48">
        <span class="fs-4"> AM Training Institute</span>
      </a>

      <nav class="d-inline-flex mt-2 mt-md-0 ms-md-auto">
        <a class="me-3 py-2 text-dark text-decoration-none" href="#"></a>
        <a class="me-3 py-2 text-dark text-decoration-none" href="#"></a>
        <a class="me-3 py-2 text-dark text-decoration-none" href="#"></a>
        <?=$nav_menu?>
      </nav>
    </div>

    <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
      <h1 class="display-4 fw-normal">Student Admission</h1>
      <p class="fs-5 text-muted">Please enter your information for the Certified Nursing Assistant Enrollment Agreement </p>
    </div>
  </header>

<main>
	<form method="POST" action="new.php" >		
		<input type="hidden" name="program_id" id="program_id" value="0" />
		<input type="hidden" name="_option" id="_option" value="0" />
		<?= $csrf_token_tag ?>
		<?=$output?>
	</form>
</main>


<footer class="pt-4 my-md-5 pt-md-5 border-top">
    <div class="row">
      <div class="col-12 col-md">
        <img class="mb-2 float-left" src="https://amtraininginstitute.org/wp-content/uploads/2024/02/cropped-logo1-1.jpg" alt="" width="24" height="24">
        <small class="d-block mb-3 text-muted">&copy; 2024</small>
      </div>
      <div class="col-6 col-md">
        <h5>Location</h5>
        <ul class="list-unstyled text-small">
          <li class="mb-1">610 North Alma School Rd., STE 4, Chandler, AZ 85224</li>          
        </ul>
      </div>
      <div class="col-6 col-md">
        <h5>Contact</h5>
        <ul class="list-unstyled text-small">
          <li class="mb-1">Name: AM Training Institute</li>
          <li class="mb-1">Phone: 480.975.8810</li>
          <li class="mb-1">Email: <a href="mailto:info@amtraininginstitute.com">info@amtraininginstitute.com</a></li>
          <li class="mb-1">Address: 610 North Alma School Rd., STE 4, Chandler, AZ 85224</li>
          <li class="mb-1">Website: <a href="https://amtraininginstitute.com">amtraininginstitute.com</a></li>
        </ul>
      </div>
      <div class="col-6 col-md">
        <h5>Email</h5>
        <ul class="list-unstyled text-small">
          <li class="mb-1"><a href="mailto:info@amtraininginstitute.com">info@amtraininginstitute.com</a></li>          
        </ul>
      </div>
    </div>
  </footer>
</div>
     <script src="../js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
$(document).ready(function() {
		    <?=$js ?>	
});
</script>
    
  </body>
</html>
