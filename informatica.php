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
		$langid =(!empty($_POST["langid"])) ? $_POST["langid"] : 'NULL';		
		$applicantid =(!empty($_SESSION["userid"])) ? $_SESSION["userid"] : 'NULL';
		$informatica = !empty($_POST["informatica"]) ? "'" . $_POST["informatica"] . "'" : 'NULL';
		$nivel =(!empty($_POST["nivel"])) ? "'" . $_POST["nivel"] . "'" : 'NULL';		
	}
	switch($_POST["submit"]){
	case "Guardar":
		$sql="INSERT INTO informatica (applicantid,computacion,nivel)
			VALUES($applicantid,$informatica,$nivel)";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido agregar el registro.";
		$msg[1]="Registro agregado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;	
	case "Actualizar":
		$sql="UPDATE informatica SET applicantid=$applicantid,computacion=$informatica,nivel=$nivel WHERE id=$_POST[langid]";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido actualizar el registro.";
		$msg[1]="Registro actualizado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Delete":
		$sql = "DELETE FROM informatica WHERE id=$id";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido eliminar el registro.";
		$msg[1]="Registro eliminado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Find":
		$sql = "SELECT * FROM informatica WHERE id=$id";
		$results=query($sql,$conn);
		$informatica = fetch_object($results);		
		break;
	case "Siguiente>>":
		header("Location: reference.php");
		break;
	case "<<Anterior":
		header("Location: language.php");
		break;		
	}
}
?>

<?php ShowHeader(WEBSITE_NAME ." :: Inform&aacute;tica"); ?>

<?php ShowDropMenu(); ?>

</div>

<script language="JavaScript" type="text/javascript">
<!--



function validateOnSubmit() {
	var elem;
    var errs=0;
	// execute all element validations in reverse order, so focus gets
    // set to the first one in error.
	if (!validateCheckbox(document.forms.informatica.nivel, 'inf_nivel', 1)) errs += 1;
	if (!validateSelect (document.forms.informatica.informatica,'inf_informatica',1)) errs += 1;
	
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

<h2 class="title-bar">Inform&aacute;tica</h2>

<?php if (isset($resmsg)) echo $resmsg; ?>

<p class="Lastnews">
<?php
	$querystr="SELECT id,applicantid,computacion,nivel
		FROM informatica
		WHERE informatica.applicantid =  $_SESSION[userid]
		ORDER BY computacion ASC";
	$results=query($querystr,$conn);
	//check if data is returned
	echo "<table border=\"0\" width=\"100%\">";  		
	echo "<tr class=\"boldtext\"><td>Conocimientos</td><td>Nivel</td><td>Editar/Borrar</td></tr>";
	while ($lang = fetch_object($results)){
		//alternate row colour
		$j++;
		if($j%2==1){
			echo "<tr id=\"row$j\">";
		}else{
			echo "<tr id=\"row$j\" class=\"odd\">";
		}	  
			echo "<td align=\"left\">$lang->computacion</td>
				<td align=\"left\">$lang->nivel</td>
				<td align=\"left\"><a name=\"editinformatica\" href=\"informatica.php?search=$lang->id&action=Find\"><img src=\"images/edit.gif\" alt=\"Editar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
			echo "<a name=\"deleteinformatica\" href=\"informatica.php?search=$lang->id&action=Delete\" onClick=\"Javascript:return confirm('Confirma que desea Borrar el Registro?','Confirmar Eliminar')\"><img src=\"images/delete.gif\" alt=\"Borrar\" border=\"0\" width=\"16\" height=\"16\"/></a></td>
			</tr>";
	}
	echo "</table>";
?>

<form action="informatica.php" method="post" name="informatica" id="informatica" enctype="multipart/form-data">
<table border="0" width="100%">
    <tr>
      <td colspan="2"><em>Los campos marcados con <span class="warn">*</span> son campos obligatorios y deben completarse.</em></td>
    </tr>
    <tr>
      <td>Conocimientos
        <input type="hidden" name="langid" value=<?php echo $informatica->id; ?>></td>
      <td>
	  <select name="informatica" id="informatica">
        <option value="">--seleccione--</option>
        <?php populate_select("computacion","computacion","computacion",$informatica->computacion); ?>
      </select>
	  <div id="inf_informatica" class="warn">* </div>
	  </td>
    </tr>
    <tr>
      <td>Nivel </td>
      <td>
        <label>
        <input type="radio" name="nivel" value="Inicial" <?php if($informatica->nivel=='Inicial') echo 'checked'?>>
  Inicial</label>
        <label>
        <input type="radio" name="nivel" value="Intermedio" <?php if($informatica->nivel=='Intermedio') echo 'checked'?>>
  Intermedio</label>
        <label>
        <input type="radio" name="nivel" value="Avanzado" <?php if($informatica->nivel=='Avanzado') echo 'checked'?>>
  Avanzado</label>
        <div id="inf_nivel" class="warn">* </div>
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
