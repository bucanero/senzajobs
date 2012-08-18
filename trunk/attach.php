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

		$fileContent = '';
		$fileUplType = '';
		$fileUplName = '';
		if(isset($_FILES['fileattach'])) {
			$fileUplType = $_FILES['fileattach']['type'];
			$fileUplName = $_FILES['fileattach']['name'];
			if(($fileUplType=='image/jpeg') || ($fileUplType=='application/msword') ||
			   ($fileUplType=='image/pjpeg')) {
				$fileHandle = fopen($_FILES['fileattach']['tmp_name'], "r");
				$fileContent = addslashes(fread($fileHandle, $_FILES['fileattach']['size']));
				fclose($fileHandle);
			} else {
				unset($_POST["submit"]);
				$resmsg = AddErrorBox('Formato de archivo no permitido.');
			}
		}
			
		$id =(!empty($_POST["id"])) ? $_POST["id"] : 'NULL';		
		$applicantid =(!empty($_SESSION["userid"])) ? $_SESSION["userid"] : 'NULL';
		$filedesc = !empty($_POST["filedesc"]) ? "'" . $_POST["filedesc"] . "'" : 'NULL';
		$fileattach = !empty($fileContent) ? "'" . $fileContent . "'" : 'NULL';
		$filetype = !empty($fileUplType) ? "'" . $fileUplType . "'" : 'NULL';
		$filename = !empty($fileUplName) ? "'" . $fileUplName . "'" : 'NULL';
	}
	switch($_POST["submit"]){
	case "Guardar":
		$sql="INSERT INTO attachment (applicantid,blobtype,blobtitle,blobdata,filename)
			VALUES($applicantid,$filetype,$filedesc,$fileattach,$filename)";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido agregar el archivo.";
		$msg[1]="Archivo agregado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;	
	case "Actualizar":
		$sql="UPDATE attachment SET applicantid=$applicantid,blobtype=$filetype,blobtitle=$filedesc,blobdata=$fileattach,filename=$filename WHERE id=$id";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido actualizar el archivo.";
		$msg[1]="Archivo actualizado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Delete":
		$sql = "DELETE FROM attachment WHERE id=$id";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido eliminar el archivo.";
		$msg[1]="Archivo eliminado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Find":
		$sql = "SELECT applicantid,blobtype,blobtitle,id,filename FROM attachment WHERE id=$id";
		$results=query($sql,$conn);
		$attachment = fetch_object($results);
		break;
	case "Download":
		$sql = "SELECT applicantid,blobdata,blobtype,blobtitle,filename,LENGTH(blobdata) as blobsize FROM attachment WHERE id=$id";
		
		$results=query($sql,$conn);
		$attachment = fetch_object($results);
		
		if (isEmployer()) {
			if (!isAdmin() && !isEmployerAllowedView($_SESSION["userid"], $attachment->applicantid)) {
				header('HTTP/1.0 403 Forbidden');
				exit();
			}
		} else {
			if ($_SESSION["userid"] != $attachment->applicantid) {
				header('HTTP/1.0 403 Forbidden');
				exit();
			}
		}
	
  		header("Content-type: $attachment->blobtype");
  		header("Content-length: $attachment->blobsize");
  		header("Content-Disposition: attachment; filename=$attachment->filename");
  		header("Content-Description: SenzaJobs Generated Data");
  		echo $attachment->blobdata;
		exit();
	case "<<Anterior":
		header("Location: reference.php");
		break;
	case "Finalizar":
		header("Location: cvbuilder.php");
		break;				
	}
}
?>

<?php ShowHeader(WEBSITE_NAME ." :: Archivos Adjuntos"); ?>

<?php ShowDropMenu(); ?>

</div>

<script language="JavaScript" type="text/javascript">
<!--



function validateOnSubmit() {
	var elem;
    var errs=0;
	// execute all element validations in reverse order, so focus gets
    // set to the first one in error.
	if (!validatePresent (document.forms.attach.filedesc,'inf_desc')) errs += 1;
	if (!validatePresent (document.forms.attach.fileattach,'inf_file')) errs += 1;	

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

<h2 class="title-bar">Archivos Adjuntos</h2>

<?php if (isset($resmsg)) echo $resmsg; ?>

<p class="Lastnews">
<?php
	$querystr="SELECT id,blobtitle,filename, LENGTH(blobdata) AS blobsize FROM attachment
		WHERE attachment.applicantid =  $_SESSION[userid]
		ORDER BY filename ASC";
	$results=query($querystr,$conn);
	//check if data is returned
	echo "<table border=\"0\" width=\"100%\">";  		
	echo "<tr class=\"boldtext\"><td>Archivo</td><td>Tama&ntilde;o</td><td>Descripci&oacute;n</td><td>Editar/Borrar</td></tr>";
	while ($ref = fetch_object($results)){
		//alternate row colour
		$j++;
		if($j%2==1){
			echo "<tr id=\"row$j\">";
		}else{
			echo "<tr id=\"row$j\" class=\"odd\">";
		}	  
			echo "<td align=\"left\"><a href=\"attach.php?search=$ref->id&action=Download\">$ref->filename</a></td>
				<td align=\"left\">". floor($ref->blobsize/1024) ." Kb.</td>
				<td align=\"left\">$ref->blobtitle</td>
				<td align=\"left\"><a name=\"editattachment\" href=\"attach.php?search=$ref->id&action=Find\"><img src=\"images/edit.gif\" alt=\"Editar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
			echo "<a name=\"deleteattachment\" href=\"attach.php?search=$ref->id&action=Delete\" onClick=\"Javascript:return confirm('Confirma que desea Borrar el Registro?','Confirmar Eliminar')\"><img src=\"images/delete.gif\" alt=\"Borrar\" border=\"0\" width=\"16\" height=\"16\"/></a></td>
			</tr>";
	}
	echo "</table>";
?>

<form action="attach.php" method="post" name="attach" id="attach" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
<input name="id" type="hidden" value="<?php echo $attachment->id; ?>" />
<table border="0" width="100%">
    <tr>
      <td colspan="2"><em>Los campos marcados con <span class="warn">*</span> son campos obligatorios y deben completarse.</em></td>
    </tr>
    <tr>
      <td>Archivo</td>
      <td><input type="file" name="fileattach" />
        <div id="inf_file" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Descripci&oacute;n</td>
      <td><input type="text" name="filedesc" value="<? echo $attachment->blobtitle; ?>" />
        <div id="inf_desc" class="warn">* </div></td>
    </tr>
    <tr>
      <td colspan="2"><em>Solo se permiten archivos en formato <b>.DOC</b> (Documento Word) o <b>.JPG</b> (Imagen/Foto).</em></td>
    </tr>
    <tr align="center">
      <td colspan="2"><input type="submit" name="submit" value="&lt;&lt;Anterior" class="button"/>
        <input type="submit" name="submit" value="<?php echo $_GET["action"]=="Find" ? "Actualizar" : "Guardar"; ?>" onclick="return validateOnSubmit();" class="button"/>
        <input type="submit" name="submit" value="Finalizar" class="button" onClick="return confirm('Desea continuar sin guardar los cambios?','Confirmar Continuar');" />
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
