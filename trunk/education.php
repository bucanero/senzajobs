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
		$id = (!empty($_POST["id"])) ? $_POST["id"] : 'NULL';		
		$applicantid =(!empty($_SESSION["userid"])) ? $_SESSION["userid"] : 'NULL';
		$institution = !empty($_POST["institution"]) ? "'" . $_POST["institution"] . "'" : 'NULL';
		$countryid = !empty($_POST["countryid"]) ? "'" . $_POST["countryid"] . "'" : 'NULL';
		$city = !empty($_POST["city"]) ? "'" . $_POST["city"] . "'" : 'NULL';
		$award = !empty($_POST["award"]) ? "'" . $_POST["award"] . "'" : 'NULL';
		$awardcategory = !empty($_POST["awardcategory"]) ? "'" . $_POST["awardcategory"] . "'" : 'NULL';
		$fieldofstudy = !empty($_POST["fieldofstudy"]) ? "'" . $_POST["fieldofstudy"] . "'" : 'NULL';
		$fieldofstudycategoryid = !empty($_POST["fieldofstudycategoryid"]) ? $_POST["fieldofstudycategoryid"] : 'NULL';
		$specialaward = !empty($_POST["specialaward"]) ? "'" . $_POST["specialaward"] . "'" : 'NULL';
		$yearofgraduation = !empty($_POST["yearofgraduation"]) ? "'" . $_POST["yearofgraduation"] . "'" : 'NULL';
		$expectedgraduation = !empty($_POST["expectedgraduation"]) ? "'" . $_POST["expectedgraduation"] . "'" : 'NULL';
		$highestlevel = (!isset($_POST["highestlevel"]) || trim($_POST["highestlevel"])=='') ? 'NULL' : $_POST["highestlevel"];		
	}
	switch($_POST["submit"]){
	case "Guardar":
		$sql="INSERT INTO education (applicantid,institution,countryid,city,award,awardcategory,fieldofstudy,fieldofstudycategoryid,
				specialaward,yearofgraduation,expectedgraduation,highestlevel)
			VALUES($applicantid,$institution,$countryid,$city,$award,$awardcategory,$fieldofstudy,$fieldofstudycategoryid,
			$specialaward,$yearofgraduation,$expectedgraduation,$highestlevel)";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido agregar el estudio.";
		$msg[1]="Estudio agregado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;	
	case "Actualizar":
		$sql="UPDATE education SET applicantid=$applicantid,institution=$institution,countryid=$countryid,city=$city,award=$award,
				awardcategory=$awardcategory,fieldofstudy=$fieldofstudy,fieldofstudycategoryid=$fieldofstudycategoryid,
				specialaward=$specialaward,yearofgraduation=$yearofgraduation,expectedgraduation=$expectedgraduation,highestlevel=$highestlevel WHERE id=$_POST[id]";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido actualizar el estudio.";
		$msg[1]="Estudio actualizado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Delete":
		$sql = "DELETE FROM education WHERE id=$id";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido eliminar el estudio.";
		$msg[1]="Estudio eliminado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Find":
		$sql = "SELECT * FROM education WHERE id=$id";
		$results=query($sql,$conn);
		$education = fetch_object($results);		
		break;
	case "Siguiente>>":
		header("Location: training.php");
		break;
	case "<<Anterior":
		header("Location: profexp.php");
		break;
	}
}
?>

<?php ShowHeader(WEBSITE_NAME ." :: Estudios"); ?>

<?php ShowDropMenu(); ?>

</div>

<script language="JavaScript" type="text/javascript">
<!--



function validateOnSubmit() {
	var elem;
    var errs=0;
	// execute all element validations in reverse order, so focus gets
    // set to the first one in error.
	if (!validateSelect (document.forms.education.yearofgraduation,'inf_yearofgraduation',1)) errs += 1;
	//if (!validateSelect (document.forms.education.fieldofstudycategoryid,'inf_fieldofstudycategoryid',1)) errs += 1;
	if (!validatePresent (document.forms.education.fieldofstudy,'inf_fieldofstudy')) errs += 1;	
	if (!validatePresent (document.forms.education.award,'inf_award')) errs += 1;
	if (!validateSelect (document.forms.education.countryid,'inf_countryid',1)) errs += 1;
	if (!validatePresent (document.forms.education.institution,'inf_institution')) errs += 1;	
	if (!validateSelect(document.forms.education.awardcategory, 'inf_awardcategory', 1)) errs += 1;

    if (errs>1)  alert('Hay campos que deben corregirse antes de enviar los datos.');
    if (errs==1) alert('Hay un campo que debe corregirse antes de enviar los datos.');

    return (errs==0);
};

function toggleDiv(myElem, myDiv) {
	if (myElem.value >= 5)
		showdiv(myDiv);
	else
		hidediv(myDiv);
}

function loadtextValue(myElem, myText) {
	var textElem = document.getElementById(myText);
	textElem.value = myElem.value;
}

//-->	 
</script>

<div id="content" >

<div id="contentdiv">

<div id="searchtable" >
<img src="images/people.jpg" alt="people" width="575" height="100" border="0" />
</div>

<div id="list">

<h2 class="title-bar">Estudios</h2>

<?php if (isset($resmsg)) echo $resmsg; ?>

<p class="Lastnews">
<?php
	$querystr="SELECT id,applicantid,highestlevel,award,fieldofstudy,institution,yearofgraduation,specialaward
		FROM education
		WHERE education.applicantid =  $_SESSION[userid]
		ORDER BY yearofgraduation ASC";
	$results=query($querystr,$conn);
	//check if data is returned
	echo "<table border=\"0\" width=\"100%\">";  		
	echo "<tr class=\"boldtext\"><td>Nivel Maximo</td><td>Titulo</td><td>Disciplina</td><td>Institucion</td><td>A&ntilde;o de Graduacion</td><td>Editar/Borrar</td></tr>";
	while ($edu = fetch_object($results)){
		//alternate row colour
		$j++;
		if($j%2==1){
			echo "<tr id=\"row$j\">";
		}else{
			echo "<tr id=\"row$j\" class=\"odd\">";
		}	  
			echo "<td align=\"left\">";
			if($edu->highestlevel==1) echo "Si";
			if($edu->highestlevel==0) echo "No";			
			echo "</td>
				<td align=\"left\">$edu->award</td>
				<td align=\"left\">$edu->fieldofstudy</td>
				<td align=\"left\">$edu->institution</td>
				<td align=\"left\">$edu->yearofgraduation</td>				
				<td align=\"left\"><a name=\"editeducation\" href=\"education.php?search=$edu->id&action=Find\"><img src=\"images/edit.gif\" alt=\"Editar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
			echo "<a name=\"deleteeducation\" href=\"education.php?search=$edu->id&action=Delete\" onClick=\"Javascript:return confirm('Confirma que desea Borrar el Registro?','Confirmar Eliminar')\"><img src=\"images/delete.gif\" alt=\"Borrar\" border=\"0\" width=\"16\" height=\"16\"/></a></td>
			</tr>";
	}
	echo "</table>";
?>
 
<form action="education.php" method="post" name="education" id="education" enctype="multipart/form-data">
<input name="id" type="hidden" value="<?php echo $education->id; ?>">
<table border="0" width="100%">
    <tr>
      <td colspan="2"><em>Los campos marcados con <span class="warn">*</span> son campos obligatorios y deben completarse.</em></td>
    </tr>
    <tr>
      <td>Grado</td>
      <td><select name="awardcategory" id="awardcategory" onchange="toggleDiv(this, 'toggleSelect');">
	       <option value="">--seleccione--</option>
           <?php populate_select("degree","id","degree",$education->awardcategory); ?>
          </select>
	  <div id="inf_awardcategory" class="warn">* </div>
  	  <br/>Seleccione si es el mayor nivel de estudios alcanzado:<br>
          <label><input type="radio" name="highestlevel" value="1" <?php if($education->highestlevel==1) echo 'checked' ?>>Si</label>
          <label><input type="radio" name="highestlevel" value="0" <?php if($education->highestlevel=='0' && trim($education->highestlevel)!=='') echo 'checked' ?>>No</label></td>
    </tr>
    <tr>
      <td>Institucion Academica </td>
      <td>
	  <div id="toggleSelect" style="display: none">
	    <select name="universidades" id="universidades" onchange="loadtextValue(this, 'institution');">
        <option value="">--seleccione--</option>
        <?php populate_select("universidad","universidad","universidad", $education->institution); ?>
		</select>
		<br /><br />
	  </div>
	  <input name="institution" type="text" id="institution" value="<?php echo $education->institution; ?>" size="40"/>
        <div id="inf_institution" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Pa&iacute;s</td>
      <td><select name="countryid" id="countryid">
        <option value="">--seleccione--</option>
        <?php populate_select("countries","countryid","country",$education->countryid); ?>
      </select>
        <div id="inf_countryid" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Ciudad</td>
      <td><input name="city" type="text" id="city" value="<?php echo $education->city; ?>"/></td>
    </tr>
    <tr>
      <td valign="top">T&iacute;tulo</td>
      <td><input name="award" type="text" id="award" value="<?php echo $education->award; ?>"/>
        <div id="inf_award" class="warn">* </div>
	</td>
    </tr>
    <tr>
      <td>Disciplina </td>
      <td><input name="fieldofstudy" type="text" id="fieldofstudy" value="<?php echo $education->fieldofstudy; ?>"/>
        <div id="inf_fieldofstudy" class="warn">* </div>
	  </td>      
    </tr>
    <tr>
      <td>Categoria </td>
      <td>
	  <select name="fieldofstudycategoryid" id="fieldofstudycategoryid">
        <option value="">--seleccione--</option>
        <?php populate_select("studyfieldcat","id","fieldcategory",$education->fieldofstudycategoryid); ?>
      </select>
	  <!-- <div id="inf_fieldofstudycategoryid" class="warn">* </div> --></td>
    </tr>
    <tr>
      <td>Diploma de Honor </td>
      <td><input name="specialaward" type="text" id="specialaward" value="<?php echo $education->specialaward; ?>" size="40"/></td>
    </tr>
    <tr>
      <td>A&ntilde;o de Graduaci&oacute;n </td>
      <td><select name="yearofgraduation" id="yearofgraduation">
        <option value="">--seleccione--</option>
		<?php populate_year_select(date("Y"), 1960, $education->yearofgraduation); ?>
      </select>
        <div id="inf_yearofgraduation" class="warn">* </div>
        O<br>
        A&ntilde;o de graduaci&oacute;n esperado si esta cursando 
        <select name="expectedgraduation" id="expectedgraduation">
          <option value="">--seleccione--</option>
<?php 
	  	for($i=0; $i<6; $i++){
			$payyear = date("Y")+$i;
			echo "<option value=\"$payyear\"";
			if($education->expectedgraduation==$payyear) echo " selected";
			echo ">$payyear</option>";
		}
?>
        </select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
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
