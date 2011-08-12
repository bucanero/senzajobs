<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
/*****************************************************************************
/*Copyright (C) 2011 Damian Parrino [ dparrino@gmail.com ]
/*****************************************************************************

Basado en:
	* Taifa Jobs >> http://sourceforge.net/projects/taifajobs/
	* Job Finder >> http://sourceforge.net/projects/jobfinder/

/*****************************************************************************/
include_once('includes/queryfunctions.php');
include_once('includes/functions.php');
$conn = db_connect();

if(isset($_GET["submit"])) $_POST["submit"]=$_GET["submit"];

if (isset($_POST["submit"])){
	switch ($_POST["submit"]){
		case 'Login':
			$username = $_POST["username"];
			$sql = "SELECT userid,admin,email,usercategory,status,concat_ws(' ', fname, sname) AS user, pass FROM users WHERE loginname = '$username'";
			//check if user exist
			$results = mysql_query($sql,$conn);
			if (!$results)
				 die("Error." . mysql_error());

			//check if password is same
			$user = mysql_fetch_object($results);
			if ($user==0) 
				die("<center><font color=red>Invalid User.<a href=login.php?member=$_POST[member]><br>Please click here to go back.</a></font><center>");
			if ($user->usercategory!==$_POST["member"]){
				if ($user->admin!=='1') 
					die("<center><font color=red>Invalid User Category.<a href=login.php?member=$_POST[member]><br>Please click here to go back.</a></font><center>");
			}					
			if ($user->status!=='A')
				die("<center><font color=red>Account not active yet.<a href=login.php?member=$_POST[member]><br>Please click here to go back.</a></font><center>");
			if ($user->pass!=md5($_POST["password"])) 
				die("<center><font color=red>Invalid Password.<a href=login.php?member=$_POST[member]><br>Please click here to go back.</a></font><center>");
			//set session variables.
			$_SESSION["user"]=$user->user;
			$_SESSION["userid"]=$user->userid;
			$_SESSION["admin"]=$user->admin; //for rights use
			$_SESSION["email"]=$user->email; //for rights use
			$_SESSION["usercategory"]=$user->usercategory;
			header("Location: index.php");
			exit;
			break;
		case 'Logout':
			LogOut();
			break;
	}	
}

header("Location: index.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<body>
</body>
</html>
