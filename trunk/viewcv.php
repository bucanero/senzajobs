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

function getApplicant($appid) {
	global $conn;
	$sql="SELECT applicant.id,applicant.cvviews,applicant.applicantid,concat_ws(' ',salutation,applicant.fname,applicant.mname,applicant.surname) AS applicant,
			applicant.sex,applicant.mstatus,applicant.dob,applicant.hbox,applicant.htown,
			applicant.hzip_postal,applicant.hcountry,applicant.hphone,applicant.hmobile,applicant.hemail,applicant.obox,
			applicant.otown,applicant.ozip_postal,applicant.ocountry,applicant.ophone,applicant.omobile, applicant.oemail,applicant.qualsumm, applicant.driverlic, applicant.tipodoc, applicant.documento,
	    country.country AS ctoforigin,nationality.country AS nationality,citizenship.country AS citizenship, dircountry.country AS chomeadd
		FROM applicant
			Left Join countries AS country ON applicant.ctoforigin = country.countryid
			Left Join countries AS nationality ON applicant.nationality = nationality.countryid
			Left Join countries AS citizenship ON applicant.citizenship = citizenship.countryid
			Left Join countries AS dircountry ON applicant.hcountry = dircountry.countryid
		WHERE applicant.applicantid = $appid";
	$results=query($sql,$conn);
	return (fetch_object($results));
}

function getEducation($appid) {
	global $conn;
   	$querystr="SELECT education.id,education.applicantid,education.highestlevel,education.award,
		education.fieldofstudy,education.institution,education.city,education.yearofgraduation,countries.country, degree.degree
	FROM education
		Left Join countries ON education.countryid = countries.countryid
		LEFT JOIN degree ON education.awardcategory = degree.id
	WHERE education.applicantid =  $appid
	ORDER BY yearofgraduation ASC";
	return (query($querystr,$conn));
}

function getProfExp($appid) {
	global $conn;
   	$querystr="SELECT id,applicantid,organization,startmonth,startyear,endmonth,endyear,startsalarymonth,
			currentsalarymonth,jobtitle,manager_supervisor,duties_responsibilities
		FROM experience
		WHERE experience.applicantid =  $appid
		ORDER BY startyear, startmonth ASC";
	return (query($querystr,$conn));
}

function getWorkshop($appid) {
	global $conn;
   	$querystr="SELECT id,trainingtitle,provider,description,startdate,enddate FROM training WHERE training.applicantid = $appid
	ORDER BY startdate ASC";
	return (query($querystr,$conn));
}

function getPublication($appid) {
	global $conn;
   	$querystr="SELECT id,ptitle,pdate,description
		FROM publication
		WHERE publication.applicantid =  $appid
		ORDER BY pdate ASC";
	return (query($querystr,$conn));
}

function getLanguage($appid) {
	global $conn;
	$querystr="SELECT id,applicantid,language,orallevel,writtenlevel
		FROM language
		WHERE language.applicantid =  $appid
		ORDER BY language ASC";
	return (query($querystr,$conn));
}

function getInformatica($appid) {
	global $conn;
	$querystr="SELECT id,applicantid,computacion,nivel
		FROM informatica
		WHERE informatica.applicantid =  $appid
		ORDER BY computacion ASC";
	return (query($querystr,$conn));
}

function getGroup($appid) {
	global $conn;
   	$querystr="SELECT id,applicantid,association,title_role,membersince
		FROM professional
		WHERE professional.applicantid =  $appid
		ORDER BY membersince ASC";
	return (query($querystr,$conn));
}

function getReferal($appid) {
	global $conn;
	$querystr="SELECT name,refposition,organization,telephone,email FROM referee 
		WHERE applicantid = $appid
		ORDER BY name ASC";
	return (query($querystr,$conn));
}

function getAttach($appid) {
	global $conn;
	$querystr="SELECT id,blobtitle,filename, LENGTH(blobdata) AS blobsize FROM attachment
		WHERE attachment.applicantid = $appid
		ORDER BY filename ASC";
	return (query($querystr,$conn));
}


if(isset($_GET["applicant"])){
	SignedInEmployer();

	$cvid = $_GET["applicant"];
	if (!isAdmin() && !isEmployerAllowedView($_SESSION["userid"], $cvid)) {
		header('HTTP/1.0 403 Forbidden');
		exit();
	}

}else{
	//check if user is logged in
	SignedIn();
	$cvid = $_SESSION["userid"];
}

$applicant = getApplicant($cvid);

//update cv views - if applicant do not update the cvviews.
if (isEmployer()) {
	$sql="UPDATE applicant SET cvviews=$applicant->cvviews+1 WHERE applicantid=$cvid";
	$viewresults=query($sql,$conn);
	free_result($viewresults);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo WEBSITE_NAME; ?> :: Curriculum Vitae</title>
<script language="JavaScript" src="js/highlight.js" type="text/javascript"></script>
<link href="css/main.css" rel="stylesheet" type="text/css">
<?php headericon(); ?>
</head>
<body>
<div align="center">
<table>
 <tr align="center">
   <td colspan="2">
   <?php 
   	echo "<h1>$applicant->applicant</h1> <br>
		Telefono : $applicant->hphone / Movil : $applicant->hmobile <br>
		Direccion: $applicant->hbox <br>
		$applicant->htown - $applicant->hzip_postal, $applicant->chomeadd <br>
		Email: <a href:\"mailto:$applicant->hemail\">$applicant->hemail</a> <br>";
   ?>
   </td>
 </tr>
 <tr align="center">
   <th colspan="2">Datos Personales</th>
 </tr>
 <tr>
   <td align="left" colspan="2">
   <?php 
   	$sexo = ($applicant->sex == 'M') ? 'Masculino' : 'Femenino';
   	echo "<b>Fecha de Nacimiento		:</b> $applicant->dob <br>
		<b>Documento 					:</b> $applicant->tipodoc $applicant->documento <br>
		<b>Sexo							:</b> $sexo <br>
		<b>Estado Civil</b> 			:</b> $applicant->mstatus <br>
		<b>Registro de Conducir</b>		:</b> $applicant->driverlic <br>
		<b>Ciudadania</b> 				:</b> $applicant->citizenship <br>
		<b>Pais de Origen</b> 			:</b> $applicant->ctoforigin <br>
		<b>Nacionalidad</b> 			:</b> $applicant->nationality ";
	?> 
</td>
 </tr>
 <tr align="center">
   <th colspan="2">Resumen de Aptitudes</th>
 </tr>
 <tr align="center">
   <td align="left" colspan="2">
   <?php echo $applicant->qualsumm; ?>
</td>
</tr>
 <tr align="center">
   <th colspan="2">Objetivos Laborales</th>
 </tr>
 <tr align="center">
   <td align="left" colspan="2">
   <?php
   $querystr="SELECT id,objective
		FROM objective
		WHERE objective.applicantid =  $cvid";
	$results=query($querystr,$conn);
	$careerobj = fetch_object($results);
	echo wordwrap($careerobj->objective,100,'<br />');
	free_result($results);
   ?>
   </td>
 </tr>
 <tr align="center">
   <th colspan="2">Estudios</th>
 </tr>
 <?php
 	$results = getEducation($cvid);
	while ($education = fetch_object($results)){
	 echo "<tr align=\"center\">
      <td align=\"left\" valign=\"top\" class=\"boldtext\">$education->yearofgraduation</td>
	   <td align=\"left\"><b>$education->award, $education->fieldofstudy</b> ($education->degree)<br>
	   		$education->institution, $education->city, $education->country<br>
			$education->specialaward
			</td>
	   </tr>";
	}
	free_result($results);
?>
 <tr align="center">
   <th colspan="2">Experiencia Profesional</th>
 </tr>
   <?php
	$results = getProfExp($cvid);
	while ($profexp = fetch_object($results)){
	$manager_supervisor = ($profexp->manager_supervisor == '1') ? '(Gerencia/Supervisi&oacute;n)' : '';
	$fechafin = ($profexp->endmonth == '0') ? 'Actualidad' : "$profexp->endmonth/$profexp->endyear";
	 echo "<tr align=\"center\">
      <td align=\"left\" valign=\"top\" class=\"boldtext\">$profexp->startmonth/$profexp->startyear-$fechafin</td>
   <td align=\"left\">";
		echo "<p>$profexp->jobtitle <i>$manager_supervisor</i><br>
		$profexp->organization<br></p>
	
		<p>
		<b>Salario Inicial:</b> $ $profexp->startsalarymonth  <br>
		<b>Salario Final:</b> $ $profexp->currentsalarymonth  <br>
		</p>
	
		Obligaciones y Responsabilidades<br>".
		stripslashes($profexp->duties_responsibilities);
	 echo "</td></tr>";
	}
	free_result($results);
?>
 <tr align="center">
   <th colspan="2">Cursos/Workshops</th>
 </tr>
 <?php
 	$results = getWorkshop($cvid);
	while ($training = fetch_object($results)){
	 echo "<tr align=\"center\">
      <td align=\"left\" valign=\"top\" class=\"boldtext\">$training->startdate - $training->enddate</td>
	   <td align=\"left\"><b>$training->trainingtitle</b><br>
	   		$training->provider<br>
			".wordwrap($training->description,100,'<br/>')."</td>
	   </tr>";
	}
	free_result($results);
?>
 <tr align="center">
   <th colspan="2">Publicaciones</th>
 </tr>
 <?php
	$results = getPublication($cvid);
	while ($publication = fetch_object($results)){
	 echo "<tr align=\"center\">
      <td align=\"left\" valign=\"top\" class=\"boldtext\">Fecha de Publicacion:</td>
	  <td align=\"left\">$publication->pdate</td>
	  </tr>
	  <tr align=\"center\">
      <td align=\"left\" valign=\"top\" class=\"boldtext\">Titulo:</td>
	  <td align=\"left\">".wordwrap($publication->ptitle,100,'<br/>')."<br><br>".wordwrap($publication->description,100,'<br/>')."</td>
	  </tr>";
	}
	free_result($results);
?>
 <tr align="center">
   <th colspan="2">Idiomas</th>
 </tr>
 <tr align="center">
   <td colspan="2">
   <?php 
	$results = getLanguage($cvid);
	echo "<table border=\"0\" width=\"100%\">";  		
	echo "<tr class=\"boldtext\"><td></td><td>Nivel Oral</td><td>Nivel Escrito</td></tr>";
	while ($lang = fetch_object($results)){
		//alternate row colour
		$j++;
		if($j%2==1){
			echo "<tr id=\"row$j\" onmouseover=\"javascript:setColor('$j')\" onmouseout=\"javascript:origColor('$j')\" bgcolor=\"#CCCCCC\">";
		}else{
			echo "<tr id=\"row$j\" onmouseover=\"javascript:setColor('$j')\" onmouseout=\"javascript:origColor('$j')\" bgcolor=\"#EEEEF8\">";
		}	  
			echo "<td align=\"left\">$lang->language</td>
				<td align=\"left\">$lang->orallevel</td>
				<td align=\"left\">$lang->writtenlevel</td>
			</tr>";
	}
	echo "</table>";
	free_result($results);
?></td>
 </tr>
 <tr align="center">
   <th colspan="2">Informatica</th>
 </tr>
 <tr align="center">
   <td colspan="2">
   <?php 
	$results = getInformatica($cvid);
	echo "<table border=\"0\" width=\"100%\">";  		
	echo "<tr class=\"boldtext\"><td></td><td>Nivel</td></tr>";
	while ($lang = fetch_object($results)){
		//alternate row colour
		$j++;
		if($j%2==1){
			echo "<tr id=\"row$j\" onmouseover=\"javascript:setColor('$j')\" onmouseout=\"javascript:origColor('$j')\" bgcolor=\"#CCCCCC\">";
		}else{
			echo "<tr id=\"row$j\" onmouseover=\"javascript:setColor('$j')\" onmouseout=\"javascript:origColor('$j')\" bgcolor=\"#EEEEF8\">";
		}	  
			echo "<td align=\"left\">$lang->computacion</td>
				<td align=\"left\">$lang->nivel</td>
			</tr>";
	}
	echo "</table>";
	free_result($results);
?></td>
 </tr>
 <tr align="center">
   <th colspan="2">Grupos y Asociaciones</th>
 </tr>
   <?php
	$results = getGroup($cvid);
	while ($profmem = fetch_object($results)){
	 echo "<tr align=\"center\">
      <td align=\"left\" valign=\"top\" class=\"boldtext\">Organizaci&oacute;n</td>
	  <td align=\"left\">$profmem->association</td>
	  </tr>
	  <tr align=\"center\">
      <td align=\"left\" valign=\"top\" class=\"boldtext\">Miembro Desde</td>
	  <td align=\"left\">$profmem->membersince<br>$profmem->title_role</td>
	  </tr>";
	}
	free_result($results);
?>
 <tr align="center">
   <th colspan="2">Referencias</th>
 </tr>
  <tr align="center">
   <td align="left" colspan="2">
   <?php
    $results = getReferal($cvid);
	while ($referee = fetch_object($results)){
			echo "<p>
				<b>$referee->name</b><br>
				$referee->refposition<br>
				$referee->organization<br>
				<b>Telefono:</b> $referee->telephone<br>
				<b>Email:</b> <a href='mailto:$referee->email'>$referee->email</a>
				</p>";
	}
	
	free_result($results);
	?>
</td>
 </tr>
 <tr align="center">
   <th colspan="2">Archivos Adjuntos</th>
 </tr>
 <tr align="center">
   <td colspan="2">
   <?php
    $results = getAttach($cvid);
	echo "<table border=\"0\" width=\"100%\">";  		
	echo "<tr class=\"boldtext\"><td>Archivo</td><td>Tama&ntilde;o</td><td>Descripci&oacute;n</td></tr>";
	while ($ref = fetch_object($results)){
		//alternate row colour
		$j++;
		if($j%2==1){
			echo "<tr id=\"row$j\" onmouseover=\"javascript:setColor('$j')\" onmouseout=\"javascript:origColor('$j')\" bgcolor=\"#CCCCCC\">";
		}else{
			echo "<tr id=\"row$j\" onmouseover=\"javascript:setColor('$j')\" onmouseout=\"javascript:origColor('$j')\" bgcolor=\"#EEEEF8\">";
		}	  

		echo "<td align=\"left\" valign=\"top\"><a href=\"attach.php?search=$ref->id&action=Download\"><img src=\"images/attach.png\" alt=\"Archivo\" border=\"0\" width=\"16\" height=\"16\"/>$ref->filename</a></td><td>". floor($ref->blobsize/1024) ." Kb.</td>
	  <td align=\"left\">$ref->blobtitle</td>
	  </tr>";
	}
	echo "</table>";
	free_result($results);


?>
</td></tr>
 <tr>
   <td colspan="2"><?php footer(); ?> <? echo WEBSITE_NAME; ?></td>
 </tr>  
</table>
</div>
</body>
</html>