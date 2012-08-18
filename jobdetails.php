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

<?php ShowHeader(WEBSITE_NAME ." :: B&uacute;squeda laboral"); ?>

<?php ShowDropMenu(); ?>

</div>

<div id="content" >

<div id="contentdiv">

<div id="searchtable" >
<img src="images/people.jpg" alt="people" width="575" height="100" border="0" />
</div>


<div id="list">

<h2 class="title-bar"><?php echo $jobs->jobtitle; ?></h2>
<p class="Lastnews" align="justify">

<?php if (isset($resmsg)) echo $resmsg; ?>
<form action="jobdetails.php" method="POST"  name="jobdetails" id="jobdetails" enctype="multipart/form-data">
<input type="hidden" name="jobid" value="<?php echo $jobs->jobid; ?>" />
<input type="hidden" name="employersemail" value="<?php echo $jobs->email; ?>" />
<table border="0" class="Box-table">
  <tr><td><b>Detalles</b></td><td>
  <?php 
	$empresa = (!empty($jobs->alias)) ? $jobs->alias : $jobs->organization;
  	echo "<b>Categor&iacute;a: </b> $jobs->jobcategory <br>
		<b>Ciudad: </b> $jobs->city <br>
		<b>Pa&iacute;s: </b> $jobs->country <br>
		<b>Empresa: </b> $empresa <br>
		<b>Nivel de Experiencia: </b> $jobs->careerlevel <br>
		<b>Fecha de publicaci&oacute;n: </b>". dateconvert($jobs->dateposted,2) ."<br>
		<b>Fecha de cierre: </b>". dateconvert($jobs->dateclosing,2) ."</td></tr>
	<tr><td><b>Resumen</b></td><td>".
		stripslashes($jobs->summary)."</td></tr>
	<tr><td><b>Descripci&oacute;n</b></td><td>".
		stripslashes($jobs->description)."</td></tr>
	<tr><td><b>Requerimientos</b></td><td>".
		stripslashes($jobs->requirements)."</td></tr>
	<tr><td><b>Datos de Contacto</b></td><td>";
	if ($jobs->contactinfo != '') {
		echo stripslashes($jobs->contactinfo);
	} else {
		echo "<b>Contacto: </b> $jobs->contact<br>
			<b>Telefono: </b> $jobs->telephone<br>
			<b>Fax: </b> $jobs->fax<br>
			<b>E-mail: </b> <a href=\"mailto:$jobs->email\">$jobs->email</a><br>
			<b>Sitio Web: </b> <a href=\"$jobs->website\">$jobs->website</a>";		
	}
?>
	</td>
  </tr>
</table>

<?php
if(isset($_SESSION["userid"]) && !isEmployer())
	echo '<input type="submit" name="submit" value="Postularse" class="button" />';
else	
	echo AddInformationBox('Para postularse online, por favor asegurese de estar <a href="register.php?member=A">registrado</a>.');
?>

</form>

</p>
</div>
</div>

<?php ShowLoginBox(); ?>

</div>

<?php ShowFooter(); ?>
