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

if(isset($_POST["submit"])){
	$applicantid =(!empty($_SESSION["userid"])) ? $_SESSION["userid"] : 'NULL';		
	$jobid = !empty($_POST["jobid"]) ? "'" . $_POST["jobid"] . "'" : 'NULL';
	$shortlisted =(!empty($_POST["pay"])) ? $_POST["pay"] : 'NULL';
	$employersemail = !empty($_POST["employersemail"]) ? "'" . $_POST["employersemail"] . "'" : 'NULL';
	switch($_POST["submit"]){
	case "Postularse":
		$sql="INSERT INTO applications (applicantid,jobid,dateapplied,shortlisted)
			VALUES($applicantid,$jobid,now(),$shortlisted)";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido enviar la postulacion.";
		$msg[1]="Postulacion agregada correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		
		//send mail to user and employer
		$commentinfo = "$_SESSION[user],\n Su postulacion laboral ha sido enviada.\n\n". WEBSITE_NAME ."\n". WEBSITE_URL;
		sendemail($commentinfo,$employersemail,WEBSITE_EMAIL_BCC,$_SESSION["email"],"Postulacion laboral");
		break;	
	}
	$_GET["jobid"]=$_POST["jobid"]; //display the job again
}

$search = $_GET["jobid"];
$sql="SELECT job.jobid,job.employerid,jobcat.jobcategory,job.employeetype,job.city,job.countryid,job.jobtitle,job.contactinfo,
		job.summary,job.description,job.requirements,job.dateposted,job.dateclosing,job.pay,job.alias,countries.country,employer.organization,
		employer.contact,employer.website,employer.telephone,employer.fax,employer.email,careerlevel.careerlevel
	FROM job
	Left Join countries ON job.countryid = countries.countryid
	Left Join employer ON job.employerid = employer.employerid
	Left Join jobcat ON job.jobcategory = jobcat.id	
	Left Join careerlevel ON job.levelid = careerlevel.careerid
	WHERE job.jobid = $search";
$results=query($sql,$conn);
$jobs = fetch_object($results);
$today = getdate();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo WEBSITE_NAME; ?> :: B&uacute;squeda Laboral</title>
<script language="JavaScript" src="js/highlight.js" type="text/javascript"></script>
<link href="css/main.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/messagebox.css" />
<?php headericon(); ?>
</head>
<body>
<?php if (isset($resmsg)) echo $resmsg; ?>
<form action="jobdetails.php" method="POST"  name="jobdetails" id="jobdetails" enctype="multipart/form-data">
<div align="left">
<table border="0" align="left">
  <tr><td colspan="7"><input name="jobid" type="hidden" value="<?php echo $jobs->jobid; ?>">
    <input type="hidden" name="employersemail" value="<?php echo $jobs->email; ?>">
  <?php 
	$empresa = (!empty($jobs->alias)) ? $jobs->alias : $jobs->organization;
  	echo "<h3>$jobs->jobtitle</h3>
		<span class=\"boldtext\">Categor&iacute;a: </span> $jobs->jobcategory <br>
		<span class=\"boldtext\">Ciudad: </span> $jobs->city <br>
		<span class=\"boldtext\">Pa&iacute;s: </span> $jobs->country <br>
		<span class=\"boldtext\">Empresa: </span> $empresa <br><br>
		<span class=\"boldtext\">Resumen</span><br>".
		stripslashes($jobs->summary)."<br><br>
		<span class=\"boldtext\">Descripcion</span><br>".
		stripslashes($jobs->description)."<br><br>
		<span class=\"boldtext\">Requerimientos</span><br>".
		stripslashes($jobs->requirements)."<br><br>
		<span class=\"boldtext\">Nivel de Experiencia: </span> $jobs->careerlevel <br><br>
		<span class=\"boldtext\">Datos de Contacto</span><br>";
	if ($jobs->contactinfo != '') {
		echo stripslashes($jobs->contactinfo);
	} else {
		echo "<span class=\"boldtext\">Contacto: </span> $jobs->contact<br>
			<span class=\"boldtext\">Telefono: </span> $jobs->telephone<br>
			<span class=\"boldtext\">Fax: </span> $jobs->fax<br>
			<span class=\"boldtext\">E-mail: </span> <a href=\"mailto:$jobs->email\">$jobs->email</a><br>
			<span class=\"boldtext\">Sitio Web: </span> <a href=\"$jobs->website\">$jobs->website</a>";		
	}
	echo "<br><br><span class=\"boldtext\">Fecha de publicaci&oacute;n: </span>". dateconvert($jobs->dateposted,2) ."<br><span class=\"boldtext\">Fecha de cierre: </span>". dateconvert($jobs->dateclosing,2);

	if(isset($_SESSION["userid"]) && $_SESSION["usercategory"]!=='E')
		echo "<span class=\"boldtext\"><input type=\"submit\" name=\"submit\" value=\"Postularse\"></span><br>";
	else	
		echo AddInformationBox('Para postularse online, por favor asegurese de estar <a href="register.php?member=A">registrado</a>.');
?>
	</td>
  </tr>
 <tr><td colspan="7" align="center"><?php footer(); ?> <? echo WEBSITE_NAME; ?></td>
 </tr>
</table>
</div>
</form>
</body>
</html>