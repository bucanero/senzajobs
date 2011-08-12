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
		$organization = !empty($_POST["organization"]) ? "'" . $_POST["organization"] . "'" : 'NULL';
		$startmonth =(!empty($_POST["startmonth"])) ? $_POST["startmonth"] : 'NULL';		
		$startyear =(!empty($_POST["startyear"])) ? $_POST["startyear"] : 'NULL';		
		$endmonth = (!isset($_POST["endmonth"]) || trim($_POST["endmonth"])=='') ? 'NULL' : $_POST["endmonth"];		
		$endyear = (!isset($_POST["endyear"]) || trim($_POST["endyear"])=='') ? 'NULL' : $_POST["endyear"];		
		$startsalarymonth =(!empty($_POST["startsalarymonth"])) ? $_POST["startsalarymonth"] : 'NULL';		
		$currentsalarymonth =(!empty($_POST["currentsalarymonth"])) ? $_POST["currentsalarymonth"] : 'NULL';		
		$jobtitle = !empty($_POST["jobtitle"]) ? "'" . $_POST["jobtitle"] . "'" : 'NULL';
		$manager_supervisor = (!isset($_POST["manager_supervisor"]) || trim($_POST["manager_supervisor"])=='') ? 'NULL' : $_POST["manager_supervisor"];		
		$duties_responsibilities = !empty($_POST["duties_responsibilities"]) ? "'" . addslashes($_POST["duties_responsibilities"]) . "'" : 'NULL';
	}
	switch($_POST["submit"]){
	case "Guardar":
		$sql="INSERT INTO experience (applicantid,organization,startmonth,startyear,endmonth,endyear,startsalarymonth,currentsalarymonth,
				jobtitle,manager_supervisor,duties_responsibilities)
			VALUES($applicantid,$organization,$startmonth,$startyear,$endmonth,$endyear,$startsalarymonth,$currentsalarymonth,
				$jobtitle,$manager_supervisor,$duties_responsibilities)";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido agregar el registro.";
		$msg[1]="Registro agregado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;	
	case "Actualizar":
		$sql="UPDATE experience SET applicantid=$applicantid,organization=$organization,startmonth=$startmonth,
				startyear=$startyear,endmonth=$endmonth,endyear=$endyear,startsalarymonth=$startsalarymonth,
				currentsalarymonth=$currentsalarymonth,jobtitle=$jobtitle,manager_supervisor=$manager_supervisor,
				duties_responsibilities=$duties_responsibilities 
			WHERE id=$_POST[id]";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido actualizar el registro.";
		$msg[1]="Registro actualizado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Delete":
		$sql = "DELETE FROM experience WHERE id=$id";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido eliminar el registro.";
		$msg[1]="Registro eliminado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Find":
		$sql = "SELECT * FROM experience WHERE id=$id";
		$results=query($sql,$conn);
		$experience = fetch_object($results);		
		break;
	case "Siguiente>>":
		header("Location: education.php");
		break;
	case "<<Anterior":
		header("Location: qualsumm.php");
		break;				
	}
}
?>

<?php ShowHeader(WEBSITE_NAME ." :: Experiencia Profesional"); ?>

<?php ShowDropMenu(); ?>

</div>

<script language="JavaScript" type="text/javascript">
<!--



function validateOnSubmit() {
	var elem;
    var errs=0;
	// execute all element validations in reverse order, so focus gets
    // set to the first one in error.
	if (!validatePresent (document.forms.profexp.jobtitle,'inf_jobtitle')) errs += 1;
	if (!validateSelect (document.forms.profexp.endyear,'inf_enddate',1)) errs += 1;
	if (!validateSelect (document.forms.profexp.endmonth,'inf_enddate',1)) errs += 1;
	if (!validateSelect (document.forms.profexp.startyear,'inf_startdate',1)) errs += 1;
	if (!validateSelect (document.forms.profexp.startmonth,'inf_startdate',1)) errs += 1;		
	if (!validatePresent (document.forms.profexp.organization,'inf_organization')) errs += 1;

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

<h2 class="title-bar">Experiencia Profesional</h2>

<?php if (isset($resmsg)) echo $resmsg; ?>

<p class="Lastnews">
<?php
	$querystr="SELECT id,applicantid,organization,jobtitle,startmonth,startyear,endmonth,endyear
		FROM experience
		WHERE experience.applicantid =  $_SESSION[userid]
		ORDER BY startyear, startmonth ASC";
	$results=query($querystr,$conn);
	//check if data is returned
	echo "<table border=\"0\" width=\"100%\">";  		
	echo "<tr class=\"boldtext\"><td>Empresa</td><td>Posicion</td><td>Desde</td><td>Hasta</td><td>Accion</td></tr>";
	while ($profexper = fetch_object($results)){
		//alternate row colour
		$j++;
		if($j%2==1){
			echo "<tr id=\"row$j\">";
		}else{
			echo "<tr id=\"row$j\" class=\"odd\">";
		}	  
		echo "<td align=\"left\">$profexper->organization</td>
			<td align=\"left\">$profexper->jobtitle</td>
			<td align=\"left\">$profexper->startmonth / $profexper->startyear</td>
			<td align=\"left\">$profexper->endmonth / $profexper->endyear</td>
			<td align=\"left\"><a name=\"editexperience\" href=\"profexp.php?search=$profexper->id&action=Find\"><img src=\"images/edit.gif\" alt=\"Editar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
		echo "<a name=\"deleteexperience\" href=\"profexp.php?search=$profexper->id&action=Delete\" onClick=\"Javascript:return confirm('Confirma que desea Borrar el Registro?','Confirmar Eliminar')\"><img src=\"images/delete.gif\" alt=\"Borrar\" border=\"0\" width=\"16\" height=\"16\"/></a></td>
		</tr>";
	}
	echo "</table>";
?>
 
<form action="profexp.php" method="post" name="profexp" id="profexp" enctype="multipart/form-data">
<input name="id" type="hidden" value="<?php echo $experience->id; ?>">
<table border="0" width="100%">
    <tr>
      <td colspan="2"><em>Los campos marcados con <span class="warn">*</span> son campos obligatorios y deben completarse.</em></td>
    </tr>
    <tr>
      <td>Empresa</td>
      <td><input name="organization" type="text" id="organization" value="<?php echo $experience->organization; ?>"/>
        <div id="inf_organization" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Desde (Fecha) </td>
      <td><select name="startmonth" id="startmonth">
        <option value="">--seleccione mes--</option>
		<?php populate_month_select($experience->startmonth); ?>
      </select>
        <select name="startyear" id="startyear">
          <option value="">--seleccione a&ntilde;o--</option>
          <?php populate_year_select(date("Y"), 1960, $experience->startyear); ?>
        </select>
        <div id="inf_startdate" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Hasta (Fecha) </td>
      <td><select name="endmonth" id="endmonth">
        <option value="0" <?php if($experience->endmonth==0) echo 'selected' ?>>--empleo actual--</option>
		<?php populate_month_select($experience->endmonth); ?>
      </select>
        <select name="endyear" id="endyear">
          <option value="0">--empleo actual--</option>
          <?php populate_year_select(date("Y"), 1960, $experience->endyear); ?>
        </select>
        <div id="inf_enddate" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Salario Inicial Mensual</td>
      <td><input name="startsalarymonth" type="text" id="startsalarymonth" value="<?php echo $experience->startsalarymonth; ?>"/></td>
    </tr>
    <tr>
      <td>Salario Final Mensual</td>
      <td><input name="currentsalarymonth" type="text" id="currentsalarymonth" value="<?php echo $experience->currentsalarymonth; ?>"/></td>
    </tr>
    <tr>
      <td valign="top">Posicion </td>
      <td><input name="jobtitle" type="text" id="jobtitle" value="<?php echo $experience->jobtitle; ?>"/>
          <div id="inf_jobtitle" class="warn">* </div>
          Responsabilidad de gerencia o supervision<br>
          <label>
          <input type="radio" name="manager_supervisor" value="1" <?php if($experience->manager_supervisor==1) echo 'checked' ?>>Si</label>
          <label><input type="radio" name="manager_supervisor" value="0" <?php if($experience->manager_supervisor=='0' && trim($experience->manager_supervisor)!=='') echo 'checked' ?>>No</label>
	</td>
    </tr>
    <tr>
      <td>Obligaciones y Responsabilidades </td>
      <td>
	  <textarea name="duties_responsibilities" id="duties_responsibilities" cols="45" rows="8"><?php echo stripslashes($experience->duties_responsibilities); ?></textarea>
		<script language="JavaScript">
			CKEDITOR.replace( 'duties_responsibilities',{ toolbar : 'Basic', language : 'es', skin : 'office2003' });
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
