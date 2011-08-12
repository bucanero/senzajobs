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
	$jobid=$_GET["search"];
	$_POST["submit"]=$_GET["action"];
}

if(isset($_POST["submit"])){
	if($_POST["submit"]=="Guardar" || $_POST["submit"]=="Actualizar"){
		$jobid =$_POST["jobid"];		
		$employerid = $_SESSION["userid"];
		$jobcategory = !empty($_POST["jobcategory"]) ? "'" . $_POST["jobcategory"] . "'" : 'NULL';
		$employeetype = !empty($_POST["employeetype"]) ? "'" . $_POST["employeetype"] . "'" : 'NULL';
		$city = !empty($_POST["city"]) ? "'" . $_POST["city"] . "'" : 'NULL';
		$countryid =(!empty($_POST["countryid"])) ? $_POST["countryid"] : 'NULL';		
		$jobtitle = !empty($_POST["jobtitle"]) ? "'" . $_POST["jobtitle"] . "'" : 'NULL';
		$summary = !empty($_POST["summary"]) ? "'" . addslashes($_POST["summary"]) . "'" : 'NULL';
		$contactinfo = !empty($_POST["contactinfo"]) ? "'" . addslashes($_POST["contactinfo"]) . "'" : 'NULL';
		$description = !empty($_POST["description"]) ? "'" . addslashes($_POST["description"]) . "'" : 'NULL';
		$requirements = !empty($_POST["requirements"]) ? "'" . addslashes($_POST["requirements"]) . "'" : 'NULL';
		$dateposted = !empty($_POST["dateposted"]) ? "'" . dateconvert($_POST["dateposted"],1) . "'" : 'NULL';
		$dateclosing = !empty($_POST["dateclosing"]) ? "'" . dateconvert($_POST["dateclosing"],1) . "'" : 'NULL';
		$pay =(!empty($_POST["pay"])) ? $_POST["pay"] : 'NULL';
		$alias = !empty($_POST["alias"]) ? "'" . $_POST["alias"] . "'" : 'NULL';
		$levelid = !empty($_POST["levelid"]) ? "'" . $_POST["levelid"] . "'" : 'NULL';
	}

	switch($_POST["submit"]){
	case "Guardar":
		$sql="INSERT INTO job (employerid,jobcategory,employeetype,city,countryid,jobtitle,summary,requirements,description,dateposted,dateclosing,pay,contactinfo,alias,levelid)
			VALUES($employerid,$jobcategory,$employeetype,$city,$countryid,$jobtitle,$summary,$requirements,$description,$dateposted,$dateclosing,$pay,$contactinfo,$alias,$levelid)";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido agregar el empleo.";
		$msg[1]="Empleo agregado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;	
	case "Actualizar":
		$sql="UPDATE job SET employerid=$employerid,jobcategory=$jobcategory,employeetype=$employeetype,
			city=$city,countryid=$countryid,jobtitle=$jobtitle,summary=$summary,requirements=$requirements,
			dateposted=$dateposted,dateclosing=$dateclosing,pay=$pay,contactinfo=$contactinfo,
			description=$description,alias=$alias,levelid=$levelid
			 WHERE jobid=$_POST[jobid]";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido actualizar el empleo.";
		$msg[1]="Empleo actualizado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Delete":
		$sql = "DELETE FROM job WHERE jobid=$jobid";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido eliminar el empleo.";
		$msg[1]="Empleo eliminado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Find":
		$sql = "SELECT * FROM job WHERE jobid=$jobid";
		$results=query($sql,$conn);
		$job = fetch_object($results);		
		break;
	case "<<Anterior":
		header("Location: employer.php");
		break;		
	case "Siguiente>>":
		header("Location: applicants.php");
		break;		
	}
}
?>

<?php ShowHeader(WEBSITE_NAME ." :: Mis B&uacute;squedas"); ?>

<?php ShowDropMenu(); ?>

</div>

<script language="JavaScript" type="text/javascript">
<!--


var dp_cal;  

window.onload = function () {
	dp_cal  = new Epoch('epoch_popup','popup',document.getElementById('dateposted'));
	dp_cal  = new Epoch('epoch_popup','popup',document.getElementById('dateclosing'));
};

function validateOnSubmit() {
	var elem;
    var errs=0;
	// execute all element validations in reverse order, so focus gets
    // set to the first one in error.
	if (!validatePresent (document.forms.jobs.dateclosing,'inf_dateclosing')) errs += 1;
	if (!validatePresent (document.forms.jobs.dateposted,'inf_dateposted')) errs += 1;
	//if (!validatePresent (document.forms.jobs.description,'inf_description')) errs += 1;
	if (!validateSelect (document.forms.jobs.countryid,'inf_countryid',1)) errs += 1;	
	if (!validatePresent (document.forms.jobs.city,'inf_city')) errs += 1;
	if (!validateSelect (document.forms.jobs.levelid,'inf_careerlevel',1)) errs += 1;
	if (!validateSelect (document.forms.jobs.employeetype,'inf_employeetype',1)) errs += 1;
	if (!validatePresent (document.forms.jobs.jobcategory,'inf_jobcategory')) errs += 1;		
	if (!validatePresent (document.forms.jobs.jobtitle,'inf_jobtitle')) errs += 1;

    if (errs>1)  alert('Hay campos que deben corregirse antes de enviar los datos.');
    if (errs==1) alert('Hay un campo que debe corregirse antes de enviar los datos.');

    return (errs==0);
};

//-->	 
</script>

<div id="content" >

<div id="contentdiv">

<div id="searchtable" >
<img src="images/people.jpg" alt="people" width="575" height="100" border="0" />
</div>

<div id="list">

<h2 class="title-bar">Mis B&uacute;squedas</h2>

<?php if (isset($resmsg)) echo $resmsg; ?>

<p class="Lastnews">
<?php 
	$querystr="SELECT employer.employerid,employer.organization,job.jobid,job.employerid,job.jobcategory,
			job.employeetype,job.city,job.countryid,job.jobtitle,job.summary,job.description,job.requirements,
			job.dateposted,job.dateclosing,job.contactinfo,job.pay,job.alias,countries.country
		FROM employer
			Inner Join job ON employer.employerid = job.employerid
			Inner Join countries ON job.countryid = countries.countryid
		WHERE employer.employerid = $_SESSION[userid]";
	
	$results=query($querystr,$conn);
	//check if data is returned
	echo "<table border=\"0\" width=\"100%\">";  		
	echo "<tr class=\"boldtext\"><td>Empresa</td><td>Posici&oacute;n</td><td>Desde</td><td>Hasta</td><td>Ver/Editar/Borrar</td></tr>";
	while ($joblist = fetch_object($results)){
		//alternate row colour
		$j++;
		if($j%2==1){
			echo "<tr id=\"row$j\">";
		}else{
			echo "<tr id=\"row$j\" class=\"odd\">";
		}	  
		$empresa = (!empty($joblist->alias)) ? $joblist->alias : $joblist->organization;
		echo "<td align=\"left\">$empresa</td>
			<td align=\"left\">$joblist->jobtitle</td>
			<td align=\"left\">$joblist->dateposted</td>
			<td align=\"left\">$joblist->dateclosing</td>
			<td align=\"left\">
			<a name=\"viewjob\" href=\"jobdetails.php?jobid=$joblist->jobid\" target='_blank'><img src=\"images/button_view.png\" alt=\"Ver busqueda\" border=\"0\" width=\"16\" height=\"16\"/></a>";
		echo "<a name=\"editjob\" href=\"jobs.php?search=$joblist->jobid&action=Find\"><img src=\"images/edit.gif\" alt=\"Editar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
		echo "<a name=\"deletejob\" href=\"jobs.php?search=$joblist->jobid&action=Delete\" onClick=\"Javascript:return confirm('Confirma que desea Borrar el Registro?','Confirmar Eliminar')\"><img src=\"images/delete.gif\" alt=\"Borrar\" border=\"0\" width=\"16\" height=\"16\"/></a></td></tr>";
	}
	echo "</table>";
?>

<form action="jobs.php" method="post" name="jobs" id="jobs" enctype="multipart/form-data">
<input name="jobid" type="hidden" value="<?php echo $job->jobid; ?>">
 <table border="0" width="100%">
    <tr>
      <td colspan="2"><em>Los campos marcados con <span class="warn">*</span> son campos obligatorios y deben completarse.</em></td>
    </tr>
    <tr>
      <td>Titulo de Busqueda
        <input type="hidden" name="jobid" value="<?php echo $job->jobid; ?>"></td>
      <td><input name="jobtitle" type="text" id="jobtitle" value="<?php echo $job->jobtitle; ?>"/>
        <div id="inf_jobtitle" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Categoria</td>
      <td><select name="jobcategory" id="jobcategory">
          <option value="">--seleccione--</option>
          <?php populate_select("jobcat","id","jobcategory",$job->jobcategory); ?>
           <option value="0">Other</option>
        </select>
        <div id="inf_jobcategory" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Tipo de Empleo</td>
      <td>
	  <select name="employeetype" id="employeetype">
          <option value="">--seleccione--</option>
          <?php populate_select("emptype","employmenttype","employmenttype",$job->employeetype); ?>
        </select>
        <div id="inf_employeetype" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Nivel de Experiencia </td>
      <td><select name="levelid">
        <option value="">--seleccione--</option>
        <?php populate_select("careerlevel","careerid","careerlevel",$job->levelid);?>
      </select>
        <div id="inf_careerlevel" class="warn">* </div>
		</td>      
    </tr>
    <tr>
      <td>Alias Empresa</td>
      <td><input name="alias" type="text" id="alias" value="<?php echo $job->alias; ?>"/>
	  </td>
    </tr>
    <tr>
      <td>Ciudad</td>
      <td>        <input name="city" type="text" id="city" value="<?php echo $job->city; ?>"/>
        <div id="inf_city" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Pa&iacute;s</td>
      <td>        <select name="countryid" id="countryid">
          <option value="">--seleccione--</option>
          <?php populate_select("countries","countryid","country",$job->countryid); ?>
        </select>
        <div id="inf_countryid" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Resumen</td>
      <td><input name="summary" type="text" id="summary" value="<?php echo $job->summary; ?>" size="50"/></td>
    </tr>
    <tr>
      <td valign="top">Descripcion</td>
      <td><textarea name="description" id="description" cols="45" rows="8"><?php echo $job->description; ?></textarea>
		<script language="JavaScript">
			CKEDITOR.replace( 'description',{ toolbar : 'Basic', language : 'es', skin : 'office2003' });
		</script>
		<div id="inf_description" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Requerimientos</td>
      <td>
	  <textarea name="requirements" id="requirements" cols="45" rows="8"><?php echo $job->requirements; ?></textarea>
		<script language="JavaScript">
			CKEDITOR.replace( 'requirements',{ toolbar : 'Basic', language : 'es', skin : 'office2003' });
		</script>
	  </td>      
    </tr>
    <tr>
      <td>Fecha de Publicacion</td>
      <td><input name="dateposted" type="text" id="dateposted" value="<?php echo dateconvert($job->dateposted,2); ?>"/>
        <div id="inf_dateposted" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Fecha de Cierre</td>
      <td><input name="dateclosing" type="text" id="dateclosing" value="<?php echo dateconvert($job->dateclosing,2); ?>"/>
        <div id="inf_dateclosing" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Informacion de Contacto<br />
        <em>Si no se detalla, se utilizan los datos cargados en Mis Datos.</em></td>
      <td><textarea name="contactinfo" id="contactinfo" rows="4"><?php echo $job->contactinfo; ?></textarea>
		<script language="JavaScript">
			CKEDITOR.replace( 'contactinfo',{ toolbar : 'Basic', language : 'es', skin : 'office2003' });
		</script>
	  </td>
    </tr>
    <tr>
      <td>Sueldo</td>
      <td><input name="pay" type="text" id="pay" value="<?php echo $job->pay; ?>"/></td>
    </tr>
    <tr align="center">
      <td colspan="2"><input type="submit" name="submit" value="&lt;&lt;Anterior" class="button"/>
        <input type="submit" name="submit" value="<?php echo $_GET["action"]=="Find" ? "Actualizar" : "Guardar"; ?>" onclick="return validateOnSubmit();" class="button"/>
        <input type="submit" name="submit" value="Siguiente&gt;&gt;" class="button" onClick="return confirm('Desea continuar sin guardar los cambios?','Confirmar Continuar');" />
		</td>
    </tr>

</table>
</form>

</p>
</div>
</div>

<?php ShowLoginBox(); ?>

</div>

<?php ShowFooter(); ?>
