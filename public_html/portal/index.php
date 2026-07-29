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
$class_id = 0;
$program_id = 0;
$tm_id = 0;
$tmt_id = 0;
$class_name = ""; 
$class_code = ""; 
$class_form = ""; 
$tm_name = "";
$tm_path = "";
$tmt_name = "";
$announcements = "";
$output = "";
$previous_class_id = 0;
$previous_program_id = 0;
$count = 0;
$sql = "Call Get_Announcements();";		
$result = mysqli_query($conn, $sql);
$rowcount=mysqli_num_rows($result);	
while ($row = mysqli_fetch_row($result))
{
	$announcements .= '<h4 class="alert-heading">'.$row[0].'</h4><p>'.$row[1].'</p>'; 
	$count++;
    if ($count < $rowcount)
	{
		$announcements .= '<hr>';
	}
}
if ($announcements <> "")
{
	$announcements = '<div class="alert alert-info" role="alert">'.$announcements.'</div>';
}

mysqli_free_result($result);
$sql = "Call Get_Classes(".$_SESSION["UserID"].");";		
$result = mysqli_query($conn2, $sql);	
while ($row = mysqli_fetch_row($result))
{	
   $class_id = $row[0];    
   $program_id = $row[1];      
   $program_name = $row[2];
   $class_code = $row[3];   
   $class_name = $row[4];
   $tm_id = $row[5];
   $tmt_id = $row[6];
   $tmt_name = $row[7];
   $tm_name = $row[8];
   $tm_path = $row[9];
   
   if ($previous_program_id <> $program_id )
   {
	   $output .= '<tr><th colspan="3"><h5>'.$program_name.'</h5></th></tr>';
	   
       $previous_program_id = $program_id;
   }
   
   if ($previous_class_id <> $class_id )
   {	   
	   $output .= '<tr class="table-primary">';	   
	   $output .= '<td><h6>'.$class_name.'</h6></td>';
	   $output .= '<td></td>';	
	   $output .= '<td></td>';		   
	   $output .= '</tr>';
	   $output .= "<tr>";	   
	   $output .= '<th>Type</th>';
	   $output .= '<th>Training Material</th>'; 
	   $output .= '<th>Link</th>';  	    	   	   
	   $output .= "</tr>";  	   
	   $previous_class_id = $class_id;	   
   }

   $output .= "<tr>";	   
   $output .= '<td>'.$tmt_name.'</td>';
   $output .= '<td>'.$tm_name.'</td>'; 
   $output .= '<td><a href="'.$tm_path.'">'.$tm_name.'</a></td>';  	    	   	   
   $output .= "</tr>";  
}   
mysqli_free_result($result);
mysqli_close($conn);
mysqli_close($conn2);		
$nav_menu = $_SESSION["UserGroup"] . "_nav.php";
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">      
    <title>AM Training Institute | Portal</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/navbar-fixed/">
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/dropdowns.css" rel="stylesheet">
<link href="../css/sidebars.css" rel="stylesheet">
<link href="../css/default.css" rel="stylesheet">
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
  </head>
  <body>
<?php include $nav_menu; ?>    
<div class="alert alert-light" role="alert"><img src="" height="20" width="1"></div>
<?=$announcements?>

<div class="row g-5">
<div class="col-md-8">
<div class="table-responsive">
    <table class="table text-center">      
	  <tbody>
	  <?=$output?>	  
    </tbody>
	</table>
  </div>
  <!--------------------------->
 </div>
 
  
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/sidebars.js"></script>
      
  </body>
</html>
