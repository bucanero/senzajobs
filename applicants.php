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

//check if user is logged in
SignedInEmployer();

//check if user has clicked on logout button
if(isset($_POST["submit"]) && $_POST["submit"]=='Logout') LogOut();

if(isset($_GET["search"]) && !empty($_GET["search"])){
	$id=$_GET["search"];
	$_POST["submit"]=$_GET["action"];
}

if(isset($_POST["submit"])){
	switch($_POST["submit"]){
	case "Delete":
		$sql = "DELETE FROM applications WHERE id=$id";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido eliminar el registro.";
		$msg[1]="Registro eliminado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Find":
		$sql = "SELECT * FROM applications WHERE id=$id";
		$results=query($sql,$conn);
		$job = fetch_object($results);		
		break;
	}
}
?>

<?php ShowHeader(WEBSITE_NAME ." :: Mis Postulantes"); ?>

<?php ShowDropMenu(); ?>

</div>

<div id="content" >

<div id="contentdiv">

<div id="searchtable" >
<img src="images/people.jpg" alt="people" width="575" height="100" border="0" />
</div>

<div id="list">

<h2 class="title-bar">Mis Postulantes</h2>

<?php if (isset($resmsg)) echo $resmsg; ?>

<p class="Lastnews">
<?php
	$querystr="SELECT applications.id,applications.applicantid,applications.jobid,applications.dateapplied,
			applications.shortlisted,concat_ws(' ',applicant.salutation,applicant.fname,applicant.mname,applicant.surname) as applicant,job.jobtitle,
			employer.organization,job.dateposted,job.dateclosing
		FROM applications
		Left Join job ON applications.jobid = job.jobid
		Left Join employer ON job.employerid = employer.employerid
		Left Join applicant ON applications.applicantid = applicant.applicantid
		WHERE employer.employerid =  $_SESSION[userid]";
	$results=query($querystr,$conn);
	//check if data is returned
	echo "<table border=\"0\" width=\"100%\">";  		
	echo "<tr class=\"boldtext\"><td>Postulante</td><td>B&uacute;squeda</td><td>Fecha</td><td>Fecha de cierre</td><td>Ver/Borrar</td></tr>";
	while ($myjobs = fetch_object($results)){
		//alternate row colour
		$j++;
		if($j%2==1){
			echo "<tr id=\"row$j\">";
		}else{
			echo "<tr id=\"row$j\" class=\"odd\">";
		}	  
			echo "<td align=\"left\">$myjobs->applicant</td>
				<td align=\"left\">$myjobs->jobtitle</td>
				<td align=\"left\">$myjobs->dateapplied</td>
				<td align=\"left\">$myjobs->dateclosing</td>
				<td align=\"left\"><a name=\"editexperience\" href=\"viewcv.php?applicant=$myjobs->applicantid\" target='_blank'><img src=\"images/button_view.png\" alt=\"Ver CV\" border=\"0\" width=\"16\" height=\"16\"/></a>";
			echo "<a name=\"deleteexperience\" href=\"applicants.php?search=$myjobs->id&action=Delete\" onClick=\"Javascript:return confirm('Confirma que desea Borrar el Registro?','Confirmar Eliminar')\"><img src=\"images/button_remove.png\" alt=\"Borrar\" border=\"0\" width=\"16\" height=\"16\"/></a></td>
			</tr>";
	}
	echo "</table>";
?>

</p>
</div>
</div>

<?php ShowLoginBox(); ?>

</div>

<?php ShowFooter(); ?>
