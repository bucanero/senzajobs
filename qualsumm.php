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
	$_POST["submit"]="Find";
}

if(isset($_POST["submit"])){
	if($_POST["submit"]=="Guardar" || $_POST["submit"]=="Actualizar"){
		//personal details
		$id =(!empty($_POST["id"])) ? $_POST["id"] : 'NULL';
		$applicantid =(!empty($_SESSION["userid"])) ? $_SESSION["userid"] : 'NULL';		
		$qualsumm = !empty($_POST["qualsumm"]) ? "'" . $_POST["qualsumm"] . "'" : 'NULL';
	}
	switch($_POST["submit"]){
	case "Guardar":
		$sql="INSERT INTO applicant (applicantid, qualsumm) VALUES($applicantid, $qualsumm)
				ON DUPLICATE KEY UPDATE qualsumm=$qualsumm";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido agregar el registro.";
		$msg[1]="Registro agregado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;	
	case "Actualizar":
		$sql="UPDATE applicant SET qualsumm=$qualsumm WHERE id=$id";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido actualizar el registro.";
		$msg[1]="Registro actualizado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Delete":
		$sql="UPDATE applicant SET qualsumm='' WHERE id=$id";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido eliminar el registro.";
		$msg[1]="Registro eliminado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Find":
		$sql = "SELECT id,applicantid,qualsumm FROM applicant WHERE id=$id";
		$results=query($sql,$conn);
		$applicant = fetch_object($results);		
		break;
	case "Siguiente>>":
		header("Location: profexp.php");
		break;
	case "<<Anterior":
		header("Location: careerobjective.php");
		break;				
	}
}
?>

<?php ShowHeader(WEBSITE_NAME ." :: Resumen de Aptitudes"); ?>

<?php ShowDropMenu(); ?>

</div>

<script language="JavaScript" type="text/javascript">
<!--

function validateOnSubmit() {
	var elem;
    var errs=0;
	// execute all element validations in reverse order, so focus gets
    // set to the first one in error.
//	if (!validatePresent (document.forms.qualform.qualsumm,'inf_qualsumm')) errs += 1;

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

<h2 class="title-bar">Resumen de Aptitudes</h2>

<?php if (isset($resmsg)) echo $resmsg; ?>

<p class="Lastnews">
<?php
	$querystr="SELECT id,applicantid,qualsumm FROM applicant WHERE applicantid=$_SESSION[userid]";
	$results=query($querystr,$conn);
	//check if data is returned
	echo "<table border=\"0\" width=\"100%\">";  		
	echo "<tr class=\"boldtext\"><td>Resumen de Aptitudes</td><td>Editar/Borrar</td></tr>";
	while ($summqual = fetch_object($results)){
		//alternate row colour
		$j++;
		if($j%2==1){
			echo "<tr id=\"row$j\">";
		}else{
			echo "<tr id=\"row$j\" class=\"odd\">";
		}	  
			echo "<td align=\"left\">$summqual->qualsumm</td>
				<td align=\"left\"><a name=\"editobjective\" href=\"qualsumm.php?search=$summqual->id&action=Find\"><img src=\"images/edit.gif\" alt=\"Editar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
			echo "<a name=\"deleteobjective\" href=\"qualsumm.php?search=$summqual->id&action=Delete\" onClick=\"Javascript:return confirm('Confirma que desea Borrar el Registro?','Confirmar Eliminar')\"><img src=\"images/delete.gif\" alt=\"Borrar\" border=\"0\" width=\"16\" height=\"16\"/></a></td>
			</tr>";
	}
	echo "</table>";
?>

<form action="qualsumm.php" method="post" name="qualform" id="qualform" enctype="multipart/form-data">
<input name="id" type="hidden" value="<?php echo $applicant->id; ?>">
<table border="0" align="center" width="100%">
    <tr>
      <td colspan="2"><em>Los campos marcados con <span class="warn">*</span> son campos obligatorios y deben completarse.</em></td>
    </tr>
    <tr>
      <td>Resumen de Aptitudes</td>
      <td><textarea name="qualsumm" id="qualsumm" cols="45" rows="8"><?php echo $applicant->qualsumm; ?></textarea>
		<script language="JavaScript">
			CKEDITOR.replace( 'qualsumm',{ toolbar : 'Basic', language : 'es', skin : 'office2003' });
		</script>
		<div id="inf_qualsumm" class="warn">* </div>
	  </td>
    </tr>
    <tr align="center">
      <td colspan="2"><input type="submit" name="submit" value="&lt;&lt;Anterior" class="button"/>
        <input type="submit" name="submit" value="<?php echo $_GET["action"]=="Find" ? "Actualizar" : "Guardar"; ?>" onclick="return validateOnSubmit();" class="button" />
        <input type="submit" name="submit" value="Siguiente&gt;&gt;" class="button" onClick="return confirm('Desea continuar sin guardar los cambios?','Confirmar Continuar');"  />
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
