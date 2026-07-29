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

$PATH = $_SERVER["DOCUMENT_ROOT"]."/porta/TrainingMaterial/";
$anchor = "/porta/TrainingMaterial/";
$class_id = 0;
$tm_id = 0;
$tmt_id = 0;
$tm_name = "";
$tm_path = "";
$target_file = "";
$uploadOk = 1;
$sql = "";
$result = null;
$row = null;
if ($_SERVER['REQUEST_METHOD']==='POST')
{
	if (isset($_POST["class_id"]))
	{
		$class_id = filter_var($_POST["class_id"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$PATH .= $class_id;
		$anchor .= $class_id . "/";
		if (!file_exists($PATH)) 
		{
		   mkdir($PATH, 0755, true);
		}
		$PATH .= "/";
	}
	if (isset($_POST["tm_id"]))
	{
		$tm_id = filter_var($_POST["tm_id"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if (isset($_POST["tmt_id"]))
	{
		$tmt_id = filter_var($_POST["tmt_id"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	if (isset($_POST["tm_name"]))
	{
		$tm_name = filter_var($_POST["tm_name"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	$target_file = $PATH . basename($_FILES["tm_path"]["name"]);
	$anchor .= basename($_FILES["tm_path"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$check = getimagesize($_FILES["tm_path"]["tmp_name"]);
	  
		
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) {
	  echo "Sorry, your file is too large.";
	  $uploadOk = 0;
	}
	if ($uploadOk == 0) 
	{
	  echo "Sorry, your file was not uploaded.";
	
	} 
	else 
	{
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
		{
			$sql = "Call SP_TM(0,".$tmt_id.",'". $tm_name . "','" . $$anchor . "','U');";		
			$result = mysqli_query($conn, $sql);	
			$row = mysqli_fetch_row($result));
			echo $row[0];
			
		} 
		else 
		{
			echo "Sorry, there was an error uploading your file.";
		}
	}
	/*
	foreach($_FILES["tm_path"] as $key => $val )
	{
		
		echo "<p>$key => $val</p>";
	}
	*/
	//$check = getimagesize($_FILES["tm_path"]["tmp_name"]);
	
}
mysqli_close($conn);
?>