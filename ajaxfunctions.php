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
include_once ("includes/queryfunctions.php");
include_once ("includes/functions.php");
$conn = db_connect();

switch ($_POST["button"]){
	case "CheckPass":
		$sql="SELECT pass FROM users WHERE userid=$_SESSION[userid]";
		$resultsuser=query($sql,$conn);
		$users = fetch_object($resultsuser);
		if(md5($_POST["oldpass"])!==$users->pass)
			echo "La clave ingresada no coincide.";
		free_result($resultsuser);
		break;
	case "CheckMail":
		if(!EmailExists($_POST["email"]))
			echo "La direccion no existe en el sistema.";
		break;
	case "CheckLoginname":
		if(UserExists($_POST["loginname"]))
			echo "El usuario ingresado ya existe.";
		break;		
}
?>