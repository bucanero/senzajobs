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
		$pdate = !empty($_POST["pdate"]) ? "'" . dateconvert($_POST["pdate"],1) . "'" : 'NULL';
		$ptitle = !empty($_POST["ptitle"]) ? "'" . $_POST["ptitle"] . "'" : 'NULL';
		$description = !empty($_POST["description"]) ? "'" . $_POST["description"] . "'" : 'NULL';
	}
	switch($_POST["submit"]){
	case "Guardar":
		$sql="INSERT INTO publication (applicantid,pdate,ptitle,description)
			VALUES($applicantid,$pdate,$ptitle,$description)";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido agregar la publicacion.";
		$msg[1]="Publicacion agregada correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;	
	case "Actualizar":
		$sql="UPDATE publication SET applicantid=$applicantid,pdate=$pdate,ptitle=$ptitle,description=$description WHERE id=$_POST[id]";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido actualizar la publicacion.";
		$msg[1]="Publicacion actualizada correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Delete":
		$sql = "DELETE FROM publication WHERE id=$id";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido eliminar la publicacion.";
		$msg[1]="Publicacion eliminada correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Find":
		$sql = "SELECT * FROM publication WHERE id=$id";
		$results=query($sql,$conn);
		$publication = fetch_object($results);		
		break;
	case "Siguiente>>":
		header("Location: profmem.php");
		break;
	case "<<Anterior":
		header("Location: training.php");
		break;		
	}
}
?>

<?php ShowHeader(WEBSITE_NAME ." :: Publicaciones"); ?>

<?php ShowDropMenu(); ?>

</div>

<script language="JavaScript" type="text/javascript">
<!--


var dp_cal;  

window.onload = function () {
	dp_cal  = new Epoch('epoch_popup','popup',document.getElementById('pdate'));
};

function validateOnSubmit() {
	var elem;
    var errs=0;
	// execute all element validations in reverse order, so focus gets
    // set to the first one in error.
	if (!validatePresent (document.forms.publications.ptitle,'inf_ptitle')) errs += 1;
	if (!validatePresent (document.forms.publications.pdate,'inf_pdate')) errs += 1;

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

<h2 class="title-bar">Publicaciones</h2>

<?php if (isset($resmsg)) echo $resmsg; ?>

<p class="Lastnews">
<?php
	$querystr="SELECT id,ptitle,pdate,description
		FROM publication
		WHERE publication.applicantid =  $_SESSION[userid]
		ORDER BY pdate ASC";
	$results=query($querystr,$conn);
	//check if data is returned
	echo "<table border=\"0\" width=\"100%\">";  		
	echo "<tr class=\"boldtext\"><td>Fecha de Publicaci&oacute;n</td><td>T&iacute;tulo</td><td>Editar/Borrar</td></tr>";
	while ($profexper = fetch_object($results)){
		//alternate row colour
		$j++;
		if($j%2==1){
			echo "<tr id=\"row$j\">";
		}else{
			echo "<tr id=\"row$j\" class=\"odd\">";
		}	  
			echo "<td align=\"left\">$profexper->pdate</td>
				<td align=\"left\">$profexper->ptitle</td>
				<td align=\"left\"><a name=\"editpublication\" href=\"publications.php?search=$profexper->id&action=Find\"><img src=\"images/edit.gif\" alt=\"Editar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
			echo "<a name=\"deletepublication\" href=\"publications.php?search=$profexper->id&action=Delete\" onClick=\"Javascript:return confirm('Confirma que desea Borrar el Registro?','Confirmar Eliminar')\"><img src=\"images/delete.gif\" alt=\"Borrar\" border=\"0\" width=\"16\" height=\"16\"/></a></td>
			</tr>";
	}
	echo "</table>";
?>

<form action="publications.php" method="post" name="publications" id="publications" enctype="multipart/form-data">
<input name="id" type="hidden" value="<?php echo $publication->id; ?>">
<table border="0" width="100%">
    <tr>
      <td colspan="2"><em>Los campos marcados con <span class="warn">*</span> son campos obligatorios y deben completarse.</em></td>
    </tr>
    <tr>
      <td>Fecha</td>
      <td><input name="pdate" type="text" id="pdate" value="<?php echo dateconvert($publication->pdate,2); ?>"/>
        <div id="inf_pdate" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Titulo</td>
      <td><input name="ptitle" type="text" id="ptitle" value="<?php echo $publication->ptitle; ?>" size="65"/>
        <div id="inf_ptitle" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Descripcion </td>
      <td>
	  <textarea name="description" id="description" cols="45" rows="8"><?php echo $publication->description; ?></textarea>
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
