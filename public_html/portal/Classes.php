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

$CURRENT_PAGE_NAME = "Classes";
$csrf_token_tag = "";$nav_menu = $_SESSION["UserGroup"] . "_nav.php";
$error_tag = "";
$previous_class_id = 0;
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
$action = "S";
$action_form="N";
$error=false;
$result = null;
$Get_QS = array();
$array_length = 0;
$output = "";
$program_options="";
$tmt_options="";
$js = "";
$d_id = 0;
if ($_SERVER['REQUEST_METHOD']==='POST')
{	
	if (isset($_POST["class_id"]))
	{
		$class_id = filter_var($_POST["class_id"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if (isset($_POST["program_id"]))
	{
		$program_id = filter_var($_POST["program_id"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if (isset($_POST["class_name"]))
	{
		$class_name = filter_var($_POST["class_name"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if (isset($_POST["class_code"]))
	{
		$class_code = filter_var($_POST["class_code"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	
	if (isset($_POST["class_form"]))
	{
		$class_form = filter_var($_POST["class_form"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if ($class_form == "class_submit")
	{
		if ($class_id == "0")
		{
			$action = "A";
		}
		else
		{
			$action = "U";			
		}
				    
		$sql = "Call SP_Classes(".$class_id.",".$program_id.",'". $class_name . "','" . $class_code . "','" . $action . "');";		
		$result = mysqli_query($conn, $sql);
        echo "<br/><br/><br/><br/>".$sql;
	}
}
else
{	
    if (isset($_GET["D"]))
	{
		$d_id = htmlspecialchars($_GET["D"] ?? '');			
		if (is_numeric($d_id))
		{
			$sql = "Call SP_Classes(".$d_id.",0,'','','D');";
			$result = mysqli_query($conn, $sql);
			header("Location: /portal/Classes.php");
		}
	}
}
$sql = "Call Get_Training_Material_Type();";		
$result = mysqli_query($conn3, $sql);	
while ($row = mysqli_fetch_row($result))
{
	$tmt_options .= '<option value="'.$row[0].'">'.$row[1].'</option>';
}
mysqli_free_result($result);
$action = "S";
$sql = "Call SP_Classes(".$class_id.",".$program_id.",'". $class_name . "','" . $class_code . "','" . $action . "');";		
$result = mysqli_query($conn, $sql);	
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
   
   if ($previous_class_id <> $class_id )
   {	   
	   $output .= '<tr>';
	   $output .= '<td><a id="a'.$class_id.'" href="javascript:void();" ><i class="bi bi-pencil"></i></a></td>';
	   $output .= '<td><strong>'.$program_name.'<stron></td>';
	   $output .= '<td>'.$class_name.'</td>';
	   $output .= '<td>'.$class_code.'</td>';   
	   $output .= '<td><a id="d'.$class_id.'" href="?D='.$class_id.'"><i class="bi bi-x-circle"></i></a></td>';
	   $output .= '</tr>';	   
	   $output .= '<tr id="tr'.$class_id.'">';
	   $output .= '<form id="form'.$class_id.'" method="POST" enctype="multipart/form-data">';
	   $output .= '<input type="hidden" id="class_id" name="class_id" value="'.$class_id.'"/>';
	   $output .= '<td><a id="a'.$class_id.'_tm" href="javascript:void();" ><i class="bi bi-node-plus-fill"></i></a></td>';
	   $output .= '<td><select class="form-control" id="tmt_id'.$class_id.'" name="tmt_id" >'.$tmt_options.'</select></td>';
	   $output .= '<td><input type="text" name="tm_name" class="form-control" id="tm_name'.$class_id.'" placeholder="Training Material Name" value=""> </td>';  
	   $output .= '<td><input type="file" name="tm_path" class="form-control" id="tm_path'.$class_id.'" placeholder="Training Material Path" value=""> </td>';  
	   $output .= '<td><button type="submit" id="s_tm'.$class_id.'" class="btn btn-primary"><i class="bi bi-cloud-arrow-up-fill"></i></button></td>';
	   $output .= '</form>';
	   $output .= '</tr>';
	   if ($tmt_name <> '')
	   {	
	   $output .= "<tr>";
	   $output .= '<td><a id="a_tm'.$tm_id.'" href="javascript:void();" ><i class="bi bi-pencil"></i></a></td>';
	   $output .= '<td>'.$tmt_name.'</td>';
	   $output .= '<td>'.$tm_name.'</td>'; 
	   $output .= '<td><a href="'.$tm_path.'">'.$tm_name.'</a></td>';  	    	   
	   $output .= '<td><a id="d_tm'.$tm_id.'" href="?D='.$tm_id.'"><i class="bi bi-x-circle"></i></a></td>';
	   $output .= "</tr>";
	   }
	   $previous_class_id = $class_id;
   }
   else
   {
	   $output .= "<tr>";
	   $output .= '<td><a id="a_tm'.$tm_id.'" href="javascript:void();" ><i class="bi bi-pencil"></i></a></td>';
	   $output .= '<td>'.$tmt_name.'</td>';
	   $output .= '<td>'.$tm_name.'</td>';  
	   $output .= '<td><a href="'.$tm_path.'">'.$tm_name.'</a></td>';  	   	   
	   $output .= '<td><a id="d_tm'.$tm_id.'" href="?D='.$tm_id.'"><i class="bi bi-x-circle"></i></a></td>';
	   $output .= "</tr>";
   }
   
   $js .= "$('#a".$class_id."').click(function(){\r\n";   
   $js .= "  $('#class_id').val('".$class_id."');\r\n";
   $js .= "  $('#program_id').val('".$program_id."');\r\n";
   $js .= "  $('#class_name').val('".$class_name."');\r\n";
   $js .= "  $('#class_code').val('".$class_code."');\r\n";      
   $js .= "  $('#class_submit').html('Update <i class=\"bi bi-pencil-fill\"></i>');\r\n";   
   $js .= "});\r\n\r\n";
   
   $js .= "$('#d".$class_id."').click(function(){\r\n"; 
   $js .= "  confirm('Are you sure you want to delete User: ".$class_name."');\r\n";
   $js .= "});\r\n\r\n";
   
   $js .= "$('#form".$class_id."').on('submit', function(e){\r\n"; 
   $js .= "   var dataString = $(this).serialize();\r\n";
   $js .= "   $.ajax({ \r\n";
   $js .= "      type: 'POST', \r\n";
   $js .= "      url: 'tm.php', \r\n";
   $js .= "      data: dataString,\r\n";
   $js .= "      success: function () {\r\n";
   $js .= "         $('<tr><td colspan=5>new td</td></tr>').insertAfter($(this).closest('tr'));\r\n";
   $js .= "      }\r\n";
   $js .= "   });\r\n";
   $js .= "   e.preventDefault();\r\n";
   $js .= "});\r\n\r\n";
   
}  
mysqli_free_result($result);

$sql = "Call Get_Programs();";		
$result = mysqli_query($conn2, $sql);	
while ($row = mysqli_fetch_row($result))
{
	$program_options .= '<option value="'.$row[0].'">'.$row[1].'</option>';
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
        -webkit-class-select: none;
        -moz-class-select: none;
        class-select: none;
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
    <link href="../css/navbar-top-fixed.css" rel="stylesheet">
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
		  <input type="hidden" name="class_id" class="form-control" id="class_id" value="0"/> 			  
		  <?= $csrf_token_tag ?>
		  <?= $error_tag ?>		  
		  <div class="form-group">
			<label for="program_id">Program *</label>
			<select class="form-control" id="program_id" name="program_id" required><?=$program_options?></select>
		  </div>
		  <div class="form-group">
			<label for="class_name">Name *</label>
            <input type="text" name="class_name" class="form-control" id="class_name" placeholder="class Name" value="" required>            
          </div>		   
		  <div class="form-group">
			<label for="class_code">Code</label>
			<input type="text" name="class_code" class="form-control" id="class_code" placeholder="class code" value="">            
		  </div>
		  
		  <div class="form-group">		  
			 <button type="submit" style="margin-top:10px;" class="btn btn-primary mb-2" id="class_submit" value="class_submit" name="class_form" >New <i class="bi bi-plus-circle-fill"></i></button> 			  
             
			 <button type="reset" style="margin-top:10px;" class="btn btn-secondary mb-2" id="class_reset" value="class_reset" name="class_form">Clear <i class="bi bi-stars"></i></button>            
          </div>
	  </form>
      </div>
    </nav>
	

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Classes</h1>
        <a href="/portal/Classes.php" class="btn btn-primary">All Classes</a>
      </div>

     
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
			  <th scope="col"><i class="bi bi-pencil-square"></i></th>
              <th scope="col">Program</th>   
			  <th scope="col">Name</th>       
			  <th scope="col">Code</th>       			  		 
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
				
			$('#class_reset').click(function(){
				$('#class_submit').html('New <i class="bi bi-plus-circle-fill"></i>');
			});
	});		
	</script>	
  </body>
</html>
