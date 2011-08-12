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
SignedIn();

//check if user has clicked on logout button
if(isset($_POST["submit"]) && $_POST["submit"]=='Logout') LogOut();

if(isset($_GET["search"]) && !empty($_GET["search"])){
	//have this as a search function
	$id=$_GET["search"];
	$_POST["submit"]=$_GET["action"];
}


if(isset($_POST["submit"])){
	if($_POST["submit"]=="Guardar" || $_POST["submit"]=="Actualizar"){
		$id =(!empty($_POST["id"])) ? $_POST["id"] : 'NULL';		
		$applicantid =(!empty($_SESSION["userid"])) ? $_SESSION["userid"] : 'NULL';		
		$trainingtitle = !empty($_POST["trainingtitle"]) ? "'" . $_POST["trainingtitle"] . "'" : 'NULL';
		$provider = !empty($_POST["provider"]) ? "'" . $_POST["provider"] . "'" : 'NULL';
		$startdate = !empty($_POST["startdate"]) ? "'" . dateconvert($_POST["startdate"],1) . "'" : 'NULL';
		$enddate = !empty($_POST["enddate"]) ? "'" . dateconvert($_POST["enddate"],1) . "'" : 'NULL';
		$description = !empty($_POST["description"]) ? "'" . $_POST["description"] . "'" : 'NULL';
	}
	switch($_POST["submit"]){
	case "Guardar":
		$sql="INSERT INTO training (applicantid,trainingtitle,provider,startdate,enddate,description)
			VALUES($applicantid,$trainingtitle,$provider,$startdate,$enddate,$description)";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido agregar el curso.";
		$msg[1]="Curso agregado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;	
	case "Actualizar":
		$sql="UPDATE training SET applicantid=$applicantid,trainingtitle=$trainingtitle,provider=$provider,startdate=$startdate,enddate=$enddate,description=$description WHERE id=$_POST[id]";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido actualizar el curso.";
		$msg[1]="Curso actualizado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Delete":
		$sql = "DELETE FROM training WHERE id=$id";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido eliminar el curso.";
		$msg[1]="Curso eliminado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Find":
		$sql = "SELECT * FROM training WHERE id=$id";
		$results=query($sql,$conn);
		$training = fetch_object($results);		
		break;
	case "Siguiente>>":
		header("Location: publications.php");
		break;
	case "<<Anterior":
		header("Location: education.php");
		break;				
	}
}
?>

<?php ShowHeader(WEBSITE_NAME ." :: Cursos &amp; Workshops"); ?>

<?php ShowDropMenu(); ?>

</div>

<script language="JavaScript" type="text/javascript">
<!--


var dp_cal;  

window.onload = function () {
	dp_cal  = new Epoch('epoch_popup','popup',document.getElementById('startdate'));
	dp_cal  = new Epoch('epoch_popup','popup',document.getElementById('enddate'));	
};

function validateOnSubmit() {
	var elem;
    var errs=0;
	// execute all element validations in reverse order, so focus gets
    // set to the first one in error.
	if (!validatePresent (document.forms.training.enddate,'inf_enddate')) errs += 1;		
	if (!validatePresent (document.forms.training.startdate,'inf_startdate')) errs += 1;	
	if (!validatePresent (document.forms.training.provider,'inf_provider')) errs += 1;
	if (!validatePresent (document.forms.training.trainingtitle,'inf_trainingtitle')) errs += 1;	

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

<h2 class="title-bar">Cursos &amp; Workshops</h2>

<?php if (isset($resmsg)) echo $resmsg; ?>

<p class="Lastnews">
<?php
	$querystr="SELECT id,applicantid,trainingtitle,startdate,enddate 
				FROM training	
				WHERE training.applicantid =  $_SESSION[userid]
				ORDER BY startdate ASC";
	$results=query($querystr,$conn);
	//check if data is returned
	echo "<table border=\"0\" width=\"100%\">";  		
	echo "<tr class=\"boldtext\"><td>Curso</td><td>Inicio</td><td>Fin</td><td>Editar/Borrar</td></tr>";
	while ($traindetails = fetch_object($results)){
		//alternate row colour
		$j++;
		if($j%2==1){
			echo "<tr id=\"row$j\">";
		}else{
			echo "<tr id=\"row$j\" class=\"odd\">";
		}	  
			echo "<td align=\"left\">$traindetails->trainingtitle</td>
				<td align=\"left\">$traindetails->startdate</td>
				<td align=\"left\">$traindetails->enddate</td>
				<td align=\"left\"><a name=\"editexperience\" href=\"training.php?search=$traindetails->id&action=Find\"><img src=\"images/edit.gif\" alt=\"Editar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
			echo "<a name=\"deleteexperience\" href=\"training.php?search=$traindetails->id&action=Delete\" onClick=\"Javascript:return confirm('Confirma que desea Borrar el Registro?','Confirmar Eliminar')\"><img src=\"images/delete.gif\" alt=\"Borrar\" border=\"0\" width=\"16\" height=\"16\"/></a></td>
			</tr>";
	}
	echo "</table>";
?>

<form action="training.php" method="post" name="training" id="training" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo $training->id; ?>">
 <table border="0" width="100%">
    <tr>
      <td colspan="2"><em>Los campos marcados con <span class="warn">*</span> son campos obligatorios y deben completarse.</em></td>
    </tr>
    <tr>
      <td>Nombre del Curso</td>
      <td><input name="trainingtitle" type="text" id="trainingtitle" value="<?php echo $training->trainingtitle; ?>"/>
        <div id="inf_trainingtitle" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Institucion </td>
      <td><input name="provider" type="text" id="provider" value="<?php echo $training->provider; ?>"/>
        <div id="inf_provider" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Inicio </td>
      <td><input name="startdate" type="text" id="startdate" value="<?php echo dateconvert($training->startdate,2); ?>"/>
        <div id="inf_startdate" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Fin </td>
      <td><input name="enddate" type="text" id="enddate" value="<?php echo dateconvert($training->enddate,2); ?>"/>
        <div id="inf_enddate" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Descripcion </td>
      <td>
	  <textarea name="description" id="description" cols="45" rows="8"><?php echo $training->description; ?></textarea>
		<script language="JavaScript">
			CKEDITOR.replace( 'description',{ toolbar : 'Basic', language : 'es', skin : 'office2003' });
		</script>
	  </td>
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

<?php ShowLoginBox('Curriculum Vitae', leftmenu()); ?>

</div>

<?php ShowFooter(); ?>
