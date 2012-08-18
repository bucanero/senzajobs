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

$where = "WHERE (job.dateclosing >= NOW()) ";

if(isset($_POST["submit"]) && $_POST["submit"]=='Buscar'){

	//country
	if(isset($_POST["countryid"]) && trim($_POST["countryid"])!=='')
		$where = $where ." AND job.countryid=$_POST[countryid] ";

	//career level
	if(isset($_POST["carrierlevelid"]) && trim($_POST["carrierlevelid"])!=='')
		$where = $where ." AND job.levelid=$_POST[carrierlevelid] ";
	
	//jobcategory
	if(isset($_POST["jobcategory"]) && trim($_POST["jobcategory"])!=='')
		$where = $where ." AND job.jobcategory=$_POST[jobcategory] ";
	
	//employeetype
	if(isset($_POST["employeetype"]) && trim($_POST["employeetype"])!=='')
		$where = $where ." AND job.employeetype='$_POST[employeetype]' ";
	
	//creteria for search on the jobtitle, summary, description, requirements
	if(isset($_POST["keyword"]) && trim($_POST["keyword"])!=='')
		$where = $where ." AND (job.jobtitle LIKE '%$_POST[keyword]%' OR job.summary LIKE '%$_POST[keyword]%' OR job.description LIKE '%$_POST[keyword]%' OR job.requirements LIKE '%$_POST[keyword]%' OR employer.organization LIKE '%$_POST[keyword]%')";

}

?>

<?php ShowHeader(WEBSITE_NAME." :: B&uacute;squedas"); ?>

<?php ShowDropMenu(); ?>

</div>

<div id="content" >

<div id="contentdiv">

<div id="searchtable" >
<img src="images/people.jpg" alt="people" width="575" height="100" border="0" />
</div>

<div id="list">

<h2 class="title-bar">B&uacute;squedas Activas</h2>
<p class="Lastnews">
<form action="busquedas.php" method="POST"  name="busquedas" id="busquedas" enctype="multipart/form-data">
<table border="0" width="100%">
   <tr>
     <td>Palabras clave</td>
     <td><input type="text" name="keyword" value="<?php echo $_POST["keyword"]; ?>" />
	</td>
   </tr>
   <tr>
     <td>Categoria </td>
     <td><select name="jobcategory" id="jobcategory">
       <option value="">--seleccione--</option>
       <?php populate_select("jobcat","id","jobcategory",$_POST["jobcategory"]); ?>
     </select></td>
   </tr>
   <tr>
     <td>Experiencia </td>
     <td><select name="carrierlevelid">
       <option value="">--seleccione--</option>
       <?php populate_select("careerlevel","careerid","careerlevel",$_POST["carrierlevelid"]);?>
     </select></td>
   </tr>
   <tr>
     <td>Dedicacion </td>
     <td><select name="employeetype" id="employeetype">
       <option value="">--seleccione--</option>
       <?php populate_select("emptype","employmenttype","employmenttype",$_POST["employeetype"]); ?>
     </select></td>
   </tr>
   <tr>
     <td>Pais</td>
     <td><select name="countryid" id="countryid">
       <option value="">--seleccione--</option>
       <?php populate_select("countries","countryid","country",$_POST["countryid"]); ?>
     </select></td>
   </tr>
   <tr>
     <td>&nbsp;</td>
     <td><input type="submit" name="submit" class="button" value="Buscar" />
       <input type="reset" name="Reset" value="Limpiar" class="button" /></td>
   </tr>
 </table>
</form>

<?php

	$querystr="SELECT job.jobid,job.employerid,job.jobcategory,job.employeetype,job.city,job.countryid,job.jobtitle,
		job.summary,job.requirements,job.dateposted,job.dateclosing,job.pay,job.alias,countries.country,employer.organization
	FROM job
		Left Join countries ON job.countryid = countries.countryid
		Left Join employer ON job.employerid = employer.employerid
		$where
		ORDER BY job.dateposted DESC, job.jobtitle ASC";
	$results=query($querystr,$conn);
	$today = getdate();
	//check if data is returned

 	echo '<table cellpadding="0" cellspacing="0" border="0" class="Box-table" >';
 	echo '<thead><tr><th class="date" scope="col">Posici&oacute;n</th>';
 	echo '<th scope="col">Detalles</th></tr></thead><tbody>';

	while ($jobs = fetch_object($results)){
		//alternate row colour
		$j++;
		if($j%2==1){
			echo "<tr id=\"row$j\">";
		}else{
			echo "<tr id=\"row$j\" class=\"odd\">";
		}
		$empresa = (!empty($jobs->alias)) ? $jobs->alias : $jobs->organization;
		echo "<td align=\"left\"><h3>$jobs->jobtitle</h3></td>";
		echo "<td align=\"left\"><p>$jobs->summary </p>";
		echo "<p><b>Empresa:</b> $empresa <br />
				<b>Ciudad:</b> $jobs->city, $jobs->country <br />
				<b>Dedicaci&oacute;n:</b> $jobs->employeetype <br />
				<b>Fecha:</b> $jobs->dateposted <br />
				<b>Cierre:</b> $jobs->dateclosing </p>";
		echo "<a href=\"jobdetails.php?jobid=$jobs->jobid\"><img src=\"images/button_view.png\" alt=\"Ver busqueda\" border=\"0\" width=\"16\" height=\"16\"/>M&aacute;s Informaci&oacute;n/Postularse</a></td></tr>";
	}
	echo "</tbody></table>";

?>
</p>

</div>
</div>

<?php ShowLoginBox(); ?>

</div>

<?php ShowFooter(); ?>
