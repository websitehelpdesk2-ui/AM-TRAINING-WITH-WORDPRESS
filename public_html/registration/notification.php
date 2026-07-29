<?php
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
session_start();
include('../../private/connect_sp.php');
require('../../private/csrf_token.php');
function base64_url_encode($input)
{
    return strtr(base64_encode($input), '+/=', '-_,');
}

function base64_url_decode($input)
{
    return base64_decode(strtr($input, '-_,', '+/='));
}
$CURRENT_PAGE_NAME = "notification";
$csrf_token_tag = "";
$error_tag = '';
$program_id = 0;
$user_email = "";
$email = "";
$user_id = 0;
$output = "";
$output_replace = "";
$options = null;
$url = base64_url_decode($_SERVER['QUERY_STRING']);

$from = 'no-reply@amtraininginstitute.org';
$to = '';
$subject = 'AM Training Institute - Student Admission';
$headers = 'From: '. $from . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= 'X-Mailer: PHP/' . phpversion();
$message = '';	
if(isset($_SESSION["program_id"]))
{
	$program_id = $_SESSION["program_id"];
}
else
{
	header("Location: /registration/");
}

if(isset($_SESSION["Registration-Email"]))
{
	$user_email = $_SESSION["Registration-Email"];
	$to = $user_email;	
}

if(isset($_SESSION["UserID"]))
{
	$user_id = $_SESSION["UserID"];
}


$sql = "Call Notify_Registration(".$program_id.",".$user_id.",'".$user_email."');";	

$result = mysqli_query($conn, $sql);	
if ($row = mysqli_fetch_row($result))
{
	$email = $row[0];		
}

if ($email != '') 
{		
    
	
	$message .= '<!doctype html>';
	$message .= '<html lang="en">';
	$message .= '  <head>';
	$message .= '    <meta charset="utf-8">';
	$message .= '    <meta name="viewport" content="width=device-width, initial-scale=1">';		
	$message .= '    <title>AM Training Institute | Portal</title>';
	$message .= '    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/navbar-fixed/">';	
	$message .= '    <link href="https://amtraininginstitute.org/css/navbar-top-fixed.css" rel="stylesheet">';	
	$message .= '    <link href="https://amtraininginstitute.org/css/bootstrap.min.css" rel="stylesheet">';
	$message .= '	 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">';
	$message .= '		 <style>';
	$message .= '      .bd-placeholder-img {';
	$message .= '        font-size: 1.125rem;';
	$message .= '        text-anchor: middle;';
	$message .= '        -webkit-user-select: none;';
	$message .= '        -moz-user-select: none;';
	$message .= '        user-select: none;';
	$message .= '      }';
	$message .= '      .b-example-divider {';
	$message .= '        height: 3rem;';
	$message .= '        background-color: rgba(0, 0, 0, .1);';
	$message .= '        border: solid rgba(0, 0, 0, .15);';
	$message .= '        border-width: 1px 0;';
	$message .= '        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);';
	$message .= '      }';

	$message .= '      .b-example-vr {';
	$message .= '        flex-shrink: 0;';
	$message .= '        width: 1.5rem;';
	$message .= '        height: 100vh;';
	$message .= '      }';

	$message .= '      .bi {';
	$message .= '        vertical-align: -.125em;';
	$message .= '        fill: currentColor;';
	$message .= '      }';

	$message .= '      .nav-scroller {';
	$message .= '        position: relative;';
	$message .= '        z-index: 2;';
	$message .= '        height: 2.75rem;';
	$message .= '        overflow-y: hidden;';
	$message .= '      }';

	$message .= '      .nav-scroller .nav {';
	$message .= '        display: flex;';
	$message .= '        flex-wrap: nowrap;';
	$message .= '        padding-bottom: 1rem;';
	$message .= '        margin-top: -1px;';
	$message .= '        overflow-x: auto;';
	$message .= '        text-align: center;';
	$message .= '        white-space: nowrap;';
	$message .= '        -webkit-overflow-scrolling: touch;';
	$message .= '      }';
	$message .= '      .fs-18 {';
	$message .= '        font-size: 18pt;	';
	$message .= '      }';	
	$message .= '    </style>';
	$message .= '  </head>';
	$message .= '  <body>';   
	
	$message .= '<div class="container py-3">';
	$message .= '  <header>';
	$message .= '    <div class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom">';
	$message .= '      <a href="https://amtraininginstitute.org/" class="d-flex align-items-center text-dark text-decoration-none">';
	$message .= '        <img src="data:image/jpeg;base64,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" alt="AMTRAINING INSTITUTE" width="48" height="48">';
	$message .= '        <span class="fs-4"> AM Training Institute</span>';
	$message .= '      </a>';
    
	$message .= '      <nav class="d-inline-flex mt-2 mt-md-0 ms-md-auto">';
	$message .= '      </nav>';
	$message .= '    </div>';
    
	$message .= '    <div class="pricing-header p-3 pb-md-4 mx-auto text-center">';
	$message .= '      <h2 class="fs-18 fw-normal">Thank you for choosing us!</h2>';
	
	$message .= '    </div>';
	$message .= '  </header>';

	$message .= '<main>';
	$message .= $email;
	$message .= '</main>';
	
	$message .= '<footer class="pt-4 my-md-5 pt-md-5 border-top">';
	$message .= '    <div class="row">';
	$message .= '      <div class="col-12 col-md">';
	$message .= '        <img class="mb-2 float-left" src="data:image/jpeg;base64,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" alt="" width="24" height="24">';
	$message .= '        <small class="d-block mb-3 text-muted">&copy; 2024</small>';
	$message .= '      </div>';
	$message .= '      <div class="col-6 col-md">';
	$message .= '        <h5>Location</h5>';
	$message .= '        <ul class="list-unstyled text-small">';
	$message .= '          <li class="mb-1">7300 Westown Parkway Ste 120<br/>West Des Moines, IA<br/>United States</li>';
	$message .= '        </ul>';
	$message .= '      </div>';
	$message .= '      <div class="col-6 col-md">';
	$message .= '        <h5>Contact</h5>';
	$message .= '        <ul class="list-unstyled text-small">';
	$message .= '          <li class="mb-1">(515) 207-5119</li>';          
	$message .= '        </ul>';
	$message .= '      </div>';
	$message .= '      <div class="col-6 col-md">';
	$message .= '        <h5>Email</h5>';
	$message .= '        <ul class="list-unstyled text-small">';
	$message .= '          <li class="mb-1"><a href="mailto:info@amtraininginstitute.org">info@amtraininginstitute.org</span></a></li>';          
	$message .= '        </ul>';
	$message .= '      </div>';
	$message .= '    </div>';
	$message .= '  </footer>';
	$message .= '</div>';
	
	$message .= '</body>';
	$message .= '</html>';
	$message = wordwrap($email, 70);
	mail($to, $subject, $message, $headers);
	
	
}		
mysqli_close($conn);
header('Location: '.$url);
?>

