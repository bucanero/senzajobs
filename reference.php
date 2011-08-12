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

//id,applicantid,name,organization,refposition,telephone,email,relation

if(isset($_POST["submit"])){
	if($_POST["submit"]=="Guardar" || $_POST["submit"]=="Actualizar"){
		//$applicantid =(!empty($_POST["applicantid"])) ? $_POST["applicantid"] : 'NULL';		
		$id =(!empty($_POST["id"])) ? $_POST["id"] : 'NULL';		
		$applicantid =(!empty($_SESSION["userid"])) ? $_SESSION["userid"] : 'NULL';
		$name = !empty($_POST["name"]) ? "'" . $_POST["name"] . "'" : 'NULL';
		$organization = !empty($_POST["organization"]) ? "'" . $_POST["organization"] . "'" : 'NULL';
		$refposition = !empty($_POST["refposition"]) ? "'" . $_POST["refposition"] . "'" : 'NULL';
		$telephone = !empty($_POST["telephone"]) ? "'" . $_POST["telephone"] . "'" : 'NULL';
		$email = !empty($_POST["email"]) ? "'" . $_POST["email"] . "'" : 'NULL';
		$relation = !empty($_POST["relation"]) ? "'" . $_POST["relation"] . "'" : 'NULL';		
	}
	switch($_POST["submit"]){
	case "Guardar":
		$sql="INSERT INTO referee (applicantid,name,organization,refposition,telephone,email,relation)
			VALUES($applicantid,$name,$organization,$refposition,$telephone,$email,$relation)";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido agregar el registro.";
		$msg[1]="Registro agregado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;	
	case "Actualizar":
		$sql="UPDATE referee SET applicantid=$applicantid,name=$name,organization=$organization,
				refposition=$refposition,telephone=$telephone,email=$email,relation=$relation WHERE id=$_POST[id]";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido actualizar el registro.";
		$msg[1]="Registro actualizado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Delete":
		$sql = "DELETE FROM referee WHERE id=$id";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido eliminar el registro.";
		$msg[1]="Registro eliminado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Find":
		$sql = "SELECT * FROM referee WHERE id=$id";
		$results=query($sql,$conn);
		$referee = fetch_object($results);		
		break;
	case "<<Anterior":
		header("Location: language.php");
		break;
	case "Siguiente>>":
		header("Location: attach.php");
		break;				
	}
}
?>

<?php ShowHeader(WEBSITE_NAME ." :: Referencias"); ?>

<?php ShowDropMenu(); ?>

</div>

<script language="JavaScript" type="text/javascript">
<!--



function validateOnSubmit() {
	var elem;
    var errs=0;
	// execute all element validations in reverse order, so focus gets
    // set to the first one in error.
	if (!validateEmail (document.forms.reference.email,'inf_email',0)) errs += 1;
	if (!validatePresent (document.forms.reference.telephone,'inf_telephone')) errs += 1;
	if (!validatePresent (document.forms.reference.name,'inf_name')) errs += 1;	

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

<h2 class="title-bar">Referencias</h2>

<?php if (isset($resmsg)) echo $resmsg; ?>

<p class="Lastnews">
<?php
	$querystr="SELECT id,applicantid,name,organization,refposition,telephone,email,relation FROM referee
		WHERE referee.applicantid =  $_SESSION[userid]
		ORDER BY name ASC";
	$results=query($querystr,$conn);
	//check if data is returned
	echo "<table border=\"0\" width=\"100%\">";  		
	echo "<tr class=\"boldtext\"><td>Nombre</td><td>Empresa</td><td>Email</td><td>Editar/Borrar</td></tr>";
	while ($ref = fetch_object($results)){
		//alternate row colour
		$j++;
		if($j%2==1){
			echo "<tr id=\"row$j\">";
		}else{
			echo "<tr id=\"row$j\" class=\"odd\">";
		}	  
			echo "<td align=\"left\">$ref->name</td>
				<td align=\"left\">$ref->organization</td>
				<td align=\"left\">$ref->email</td>				
				<td align=\"left\"><a name=\"editreferee\" href=\"reference.php?search=$ref->id&action=Find\"><img src=\"images/edit.gif\" alt=\"Editar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
			echo "<a name=\"deletereferee\" href=\"reference.php?search=$ref->id&action=Delete\" onClick=\"Javascript:return confirm('Confirma que desea Borrar el Registro?','Confirmar Eliminar')\"><img src=\"images/delete.gif\" alt=\"Borrar\" border=\"0\" width=\"16\" height=\"16\"/></a></td>
			</tr>";
	}
	echo "</table>";
?>

<form action="reference.php" method="post" name="reference" id="reference" enctype="multipart/form-data">
<input name="id" type="hidden" value="<?php echo $referee->id; ?>">
<table border="0" width="100%">
    <tr>
      <td colspan="2"><em>Los campos marcados con <span class="warn">*</span> son campos obligatorios y deben completarse.</em></td>
    </tr>
    <tr>
      <td>Nombre</td>
      <td><input name="name" type="text" id="name" value="<?php echo $referee->name; ?>"/>
        <div id="inf_name" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Empresa</td>
      <td><input name="organization" type="text" id="organization" value="<?php echo $referee->organization; ?>"/></td>
    </tr>
    <tr>
      <td>Posicion</td>
      <td><input name="refposition" type="text" id="refposition" value="<?php echo $referee->refposition; ?>"/></td>
    </tr>
    <tr>
      <td>Telefono</td>
      <td><input name="telephone" type="text" id="telephone" value="<?php echo $referee->telephone; ?>"/>
        <div id="inf_telephone" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Email </td>
      <td><input name="email" type="text" id="email" value="<?php echo $referee->email; ?>"/><div id="inf_email" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Relacion</td>
      <td><input name="relation" type="text" id="relation" value="<?php echo $referee->relation; ?>"/>
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
