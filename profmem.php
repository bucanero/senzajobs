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
		//$applicantid =(!empty($_POST["applicantid"])) ? $_POST["applicantid"] : 'NULL';		
		$id =(!empty($_POST["id"])) ? $_POST["id"] : 'NULL';		
		$applicantid =(!empty($_SESSION["userid"])) ? $_SESSION["userid"] : 'NULL';
		$association = !empty($_POST["association"]) ? "'" . $_POST["association"] . "'" : 'NULL';
		$title_role = !empty($_POST["title_role"]) ? "'" . $_POST["title_role"] . "'" : 'NULL';
		$membersince = !empty($_POST["membersince"]) ? "'" . dateconvert($_POST["membersince"],1) . "'" : 'NULL';

	}
	switch($_POST["submit"]){
	case "Guardar":
		$sql="INSERT INTO professional (applicantid,association,title_role,membersince)
			VALUES($applicantid,$association,$title_role,$membersince)";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido agregar el registro.";
		$msg[1]="Registro agregado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;	
	case "Actualizar":
		$sql="UPDATE professional SET applicantid=$applicantid,association=$association,title_role=$title_role,membersince=$membersince WHERE id=$_POST[id]";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido actualizar el registro.";
		$msg[1]="Registro actualizado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Delete":
		$sql = "DELETE FROM professional WHERE id=$id";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido eliminar el registro.";
		$msg[1]="Registro eliminado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Find":
		$sql = "SELECT * FROM professional WHERE id=$id";
		$results=query($sql,$conn);
		$professional = fetch_object($results);		
		break;
	case "Siguiente>>":
		header("Location: language.php");
		break;
	case "<<Anterior":
		header("Location: publications.php");
		break;		
	}
}
?>

<?php ShowHeader(WEBSITE_NAME ." :: Grupos y Asociaciones"); ?>

<?php ShowDropMenu(); ?>

</div>

<script language="JavaScript" type="text/javascript">
<!--


var dp_cal;  

window.onload = function () {
	dp_cal  = new Epoch('epoch_popup','popup',document.getElementById('membersince'));
};

function validateOnSubmit() {
	var elem;
    var errs=0;
	// execute all element validations in reverse order, so focus gets
    // set to the first one in error.
	if (!validatePresent (document.forms.profmem.membersince,'inf_membersince')) errs += 1;
	if (!validatePresent (document.forms.profmem.title_role,'inf_title_role')) errs += 1;
	if (!validatePresent (document.forms.profmem.association,'inf_association')) errs += 1;	

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

<h2 class="title-bar">Grupos y Asociaciones</h2>

<?php if (isset($resmsg)) echo $resmsg; ?>

<p class="Lastnews">
<?php
	$querystr="SELECT id,applicantid,association,title_role,membersince
		FROM professional
		WHERE professional.applicantid =  $_SESSION[userid]
		ORDER BY membersince ASC";
	$results=query($querystr,$conn);
	//check if data is returned
	echo "<table border=\"0\" width=\"100%\">";  		
	echo "<tr class=\"boldtext\"><td>Entidad</td><td>Titulo/Rol</td><td>Miembro Desde</td><td>Editar/Borrar</td></tr>";
	while ($profmem = fetch_object($results)){
		//alternate row colour
		$j++;
		if($j%2==1){
			echo "<tr id=\"row$j\">";
		}else{
			echo "<tr id=\"row$j\" class=\"odd\">";
		}	  
			echo "<td align=\"left\">$profmem->association</td>
				<td align=\"left\">$profmem->title_role</td>
				<td align=\"left\">$profmem->membersince</td>				
				<td align=\"left\"><a name=\"editprofessional\" href=\"profmem.php?search=$profmem->id&action=Find\"><img src=\"images/edit.gif\" alt=\"Editar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
			echo "<a name=\"deleteprofessional\" href=\"profmem.php?search=$profmem->id&action=Delete\" onClick=\"Javascript:return confirm('Confirma que desea Borrar el Registro?','Confirmar Eliminar')\"><img src=\"images/delete.gif\" alt=\"Borrar\" border=\"0\" width=\"16\" height=\"16\"/></a></td>
			</tr>";
	}
	echo "</table>";
?>

<form action="profmem.php" method="post" name="profmem" id="profmem" enctype="multipart/form-data">
<input name="id" type="hidden" value="<? echo $professional->id; ?>">
<table border="0" width="100%">
    <tr>
      <td colspan="2"><em>Los campos marcados con <span class="warn">*</span> son campos obligatorios y deben completarse.</em></td>
    </tr>
    <tr>
      <td>Nombre de la Entidad </td>
      <td><input name="association" type="text" id="association" value="<?php echo $professional->association; ?>"/>
        <div id="inf_association" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Titulo/<span>Rol</span></td>
      <td><input name="title_role" type="text" id="title_role" value="<?php echo $professional->title_role; ?>"/>
        <div id="inf_title_role" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Miembro Desde </td>
      <td><input name="membersince" type="text" id="membersince" value="<?php echo dateconvert($professional->membersince,2); ?>"/>
        <div id="inf_membersince" class="warn">* </div></td>      
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
