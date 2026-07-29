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
if(!isset($_SESSION["UserID"]))
{
	header("Location: /login/");
}
$CURRENT_PAGE_NAME = "register";
$csrf_token_tag = "";
$error_tag = '';
$user_email = "";
$email = "";
$output = "";
$output_replace = "";
$sent = false;
$registration_id = base64_url_decode($_SERVER['QUERY_STRING']);

$from = 'no-reply@amtraininginstitute.org';
$to = '';
$subject = 'AM Training Institute - Student Admission';
$headers = 'From: '. $from . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= 'X-Mailer: PHP/' . phpversion();
$message = '';	

$sql = "Call Activate_Registration(".$registration_id .");";	
$result = mysqli_query($conn, $sql);	
if ($row = mysqli_fetch_row($result))
{
	$email = $row[0];	
    $to	= $row[1];
}
mysqli_close($conn);
if ($email != '') 
{		
    
	$message .= $email;
	$message .= '        <hr/><h5>Contact</h5>';
	$message .= '          Name: AM Training Institute<br/>';
	$message .= '          Phone: 480.975.8810<br/>';
	$message .= '          Email: <a href="mailto:info@amtraininginstitute.com">info@amtraininginstitute.com</a><br/>';
	$message .= '          Address: 610 North Alma School Rd., STE 4, Chandler, AZ 85224<br/>';
	$message .= '          Website: <a href="https://amtraininginstitute.com">amtraininginstitute.com</a>';
	$message = wordwrap(str_replace("\n.", "\n..",$message), 70);
	$sent = mail($to, $subject, $message, $headers);
	
	if (!$sent)
	{
		$error_tag = ' without email';
	}
	
}		
else
{
	echo "Email not sent!  ";	
}
?>
Activated<?=$error_tag?>! <i class="bi bi-star-fill"></i>
