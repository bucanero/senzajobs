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
		$language = !empty($_POST["language"]) ? "'" . $_POST["language"] . "'" : 'NULL';
		$orallevel =(!empty($_POST["orallevel"])) ? "'" . $_POST["orallevel"] . "'" : 'NULL';		
		$writtenlevel =(!empty($_POST["writtenlevel"])) ? "'" . $_POST["writtenlevel"] . "'" : 'NULL';		
	}
	switch($_POST["submit"]){
	case "Guardar":
		$sql="INSERT INTO language (applicantid,language,orallevel,writtenlevel)
			VALUES($applicantid,$language,$orallevel,$writtenlevel)";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido agregar el idioma.";
		$msg[1]="Idioma agregado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;	
	case "Actualizar":
		$sql="UPDATE language SET applicantid=$applicantid,language=$language,orallevel=$orallevel,writtenlevel=$writtenlevel WHERE id=$_POST[langid]";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido actualizar el idioma.";
		$msg[1]="Idioma actualizado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Delete":
		$sql = "DELETE FROM language WHERE id=$id";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido eliminar el idioma.";
		$msg[1]="Idioma eliminado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Find":
		$sql = "SELECT * FROM language WHERE id=$id";
		$results=query($sql,$conn);
		$language = fetch_object($results);		
		break;
	case "Siguiente>>":
		header("Location: informatica.php");
		break;
	case "<<Anterior":
		header("Location: profmem.php?search=$_SESSION[userid]");
		break;		
	}
}
?>

<?php ShowHeader(WEBSITE_NAME ." :: Idiomas"); ?>

<?php ShowDropMenu(); ?>

</div>

<script language="JavaScript" type="text/javascript">
<!--



function validateOnSubmit() {
	var elem;
    var errs=0;
	// execute all element validations in reverse order, so focus gets
    // set to the first one in error.
	if (!validateCheckbox(document.forms.language.writtenlevel, 'inf_writtenlevel', 1)) errs += 1;
	if (!validateCheckbox(document.forms.language.orallevel, 'inf_orallevel', 1)) errs += 1;
	if (!validateSelect (document.forms.language.language,'inf_language',1)) errs += 1;
	
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

<h2 class="title-bar">Idiomas</h2>

<?php if (isset($resmsg)) echo $resmsg; ?>

<p class="Lastnews">
<?php
	$querystr="SELECT id,applicantid,language,orallevel,writtenlevel
		FROM language
		WHERE language.applicantid =  $_SESSION[userid]
		ORDER BY language ASC";
	$results=query($querystr,$conn);
	//check if data is returned
	echo "<table border=\"0\" width=\"100%\">";  		
	echo "<tr class=\"boldtext\"><td>Idioma</td><td>Oral</td><td>Escrito</td><td>Editar/Borrar</td></tr>";
	while ($lang = fetch_object($results)){
		//alternate row colour
		$j++;
		if($j%2==1){
			echo "<tr id=\"row$j\">";
		}else{
			echo "<tr id=\"row$j\" class=\"odd\">";
		}	  
			echo "<td align=\"left\">$lang->language</td>
				<td align=\"left\">$lang->orallevel</td>
				<td align=\"left\">$lang->writtenlevel</td>
				<td align=\"left\"><a name=\"editlanguage\" href=\"language.php?search=$lang->id&action=Find\"><img src=\"images/edit.gif\" alt=\"Editar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
			echo "<a name=\"deletelanguage\" href=\"language.php?search=$lang->id&action=Delete\" onClick=\"Javascript:return confirm('Confirma que desea Borrar el Registro?','Confirmar Eliminar')\"><img src=\"images/delete.gif\" alt=\"Borrar\" border=\"0\" width=\"16\" height=\"16\"/></a></td>
			</tr>";
	}
	echo "</table>";
?>

<form action="language.php" method="post" name="language" id="language" enctype="multipart/form-data">
<table border="0" width="100%">
    <tr>
      <td colspan="2"><em>Los campos marcados con <span class="warn">*</span> son campos obligatorios y deben completarse.</em></td>
    </tr>
    <tr>
      <td>Idioma
        <input type="hidden" name="langid" value=<?php echo $language->id; ?>></td>
      <td>
	  <select name="language" id="language">
        <option value="">--seleccione--</option>
        <?php populate_select("languages","language","language",$language->language); ?>
      </select>
	  <div id="inf_language" class="warn">* </div>
	  </td>
    </tr>
    <tr>
      <td>Nivel Oral</td>
      <td>
        <label>
        <input type="radio" name="orallevel" value="Inicial" <?php if($language->orallevel=='Inicial') echo 'checked'?>>
  Inicial</label>
        <label>
        <input type="radio" name="orallevel" value="Intermedio" <?php if($language->orallevel=='Intermedio') echo 'checked'?>>
  Intermedio</label>
        <label>
        <input type="radio" name="orallevel" value="Avanzado" <?php if($language->orallevel=='Avanzado') echo 'checked'?>>
  Avanzado</label>
        <div id="inf_orallevel" class="warn">* </div>
        </td>
    </tr>
    <tr>
      <td>Nivel Escrito</td>
      <td><label>
        <input type="radio" name="writtenlevel" value="Inicial" <?php if($language->writtenlevel=='Inicial') echo 'checked'?>>
  Inicial</label>
        <label>
        <input type="radio" name="writtenlevel" value="Intermedio" <?php if($language->writtenlevel=='Intermedio') echo 'checked'?>>
  Intermedio</label>
        <label>
        <input type="radio" name="writtenlevel" value="Avanzado" <?php if($language->writtenlevel=='Avanzado') echo 'checked'?>>
  Avanzado</label>
        <div id="inf_writtenlevel" class="warn">* </div>
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
