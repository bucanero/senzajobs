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
SignedInEmployer();
$id=$_SESSION["userid"];

if(isset($_GET["search"]) && !empty($_GET["search"]) && isAdmin()){
	$id=$_GET["search"];
}

if(isset($_POST["submit"])){
	if($_POST["submit"]=="Guardar" || $_POST["submit"]=="Actualizar"){
		//personal details
		$employerid =(!empty($_POST["employerid"])) ? $_POST["employerid"] : 'NULL';
		$organization = !empty($_POST["organization"]) ? "'" . $_POST["organization"] . "'" : 'NULL';
		$contact = !empty($_POST["contact"]) ? "'" . $_POST["contact"] . "'" : 'NULL';
		$jobtitle = !empty($_POST["jobtitle"]) ? "'" . $_POST["jobtitle"] . "'" : 'NULL';
		$telephone = !empty($_POST["telephone"]) ? "'" . $_POST["telephone"] . "'" : 'NULL';
		$extension = !empty($_POST["extension"]) ? "'" . $_POST["extension"] . "'" : 'NULL';
		$mobile = !empty($_POST["mobile"]) ? "'" . $_POST["mobile"] . "'" : 'NULL';
		$fax = !empty($_POST["fax"]) ? "'" . $_POST["fax"] . "'" : 'NULL';
		$email = !empty($_POST["email"]) ? "'" . $_POST["email"] . "'" : 'NULL';		
		$box = !empty($_POST["box"]) ? "'" . $_POST["box"] . "'" : 'NULL';
		$town = !empty($_POST["town"]) ? "'" . $_POST["town"] . "'" : 'NULL';
		$zip_postal = !empty($_POST["zip_postal"]) ? "'" . $_POST["zip_postal"] . "'" : 'NULL';		
		$website = !empty($_POST["website"]) ? "'" . $_POST["website"] . "'" : 'NULL';		
		$countryid =(!empty($_POST["countryid"])) ? $_POST["countryid"] : 'NULL';
	}
	switch($_POST["submit"]){
	case "Guardar":
		$sql="INSERT INTO employer (organization,contact,jobtitle,telephone,box,town,zip_postal,fax,extension,mobile,email,website,countryid)
			VALUES($organization,$contact,$jobtitle,$telephone,$box,$town,$zip_postal,$fax,$extension,$mobile,$email,$website,$countryid)";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido agregar la empresa.";
		$msg[1]="Empresa agregada correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;	
	case "Actualizar":
		$sql="UPDATE employer SET employerid=$employerid,organization=$organization,contact=$contact,
				jobtitle=$jobtitle,telephone=$telephone,box=$box,town=$town,zip_postal=$zip_postal,
				fax=$fax,extension=$extension,email=$email,mobile=$mobile,
				website=$website,countryid=$countryid
			WHERE employerid=$employerid";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido actualizar la empresa.";
		$msg[1]="Empresa actualizada correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Delete":
		$sql = "DELETE FROM employer WHERE employerid=$employerid";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido eliminar la empresa.";
		$msg[1]="Empresa eliminada correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Siguiente>>":
		header("Location: jobs.php");
		break;
	}
}

$sql = "SELECT * FROM employer WHERE employerid=$id";
$results=query($sql,$conn);
$employer = fetch_object($results);

?>

<?php ShowHeader(WEBSITE_NAME ." :: Mis Datos"); ?>

<?php ShowDropMenu(); ?>

</div>

<script language="JavaScript" type="text/javascript">
<!--



function validateOnSubmit() {
	var elem;
    var errs=0;
	// execute all element validations in reverse order, so focus gets
    // set to the first one in error.
	if (!validateSelect (document.forms.employer.countryid,'inf_countryid',1)) errs += 1;
	if (!validatePresent (document.forms.employer.town,'inf_town')) errs += 1;
	if (!validatePresent (document.forms.employer.box,'inf_box')) errs += 1;
	if (!validateNum (document.forms.employer.mobile,'inf_mobile',0)) errs += 1;	
	if (!validateEmail (document.forms.employer.email,'inf_email',1)) errs += 1;	
	if (!validatePresent (document.forms.employer.telephone,'inf_telephone')) errs += 1;
	if (!validatePresent (document.forms.employer.contact,'inf_contact')) errs += 1;
	if (!validatePresent (document.forms.employer.organization,'inf_organization')) errs += 1;

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

<h2 class="title-bar">Mis Datos</h2>

<?php if (isset($resmsg)) echo $resmsg; ?>

<p class="Lastnews">
<form action="employer.php" method="post" name="employer" id="employer" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo $employer->id; ?>">
<input type="hidden" name="employerid" value="<?php echo $employer->employerid; ?>">
<table border="0">
    <tr>
      <td colspan="2"><em>Los campos marcados con <span class="warn">*</span> son campos obligatorios y deben completarse.</em></td>
    </tr>
    <tr>
      <td>Empresa</td>
      <td><input name="organization" type="text" id="organization" value="<?php echo $employer->organization; ?>"/>
        <div id="inf_organization" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Persona de Contacto</td>
      <td><input name="contact" type="text" id="contact" value="<?php echo $employer->contact; ?>"/>
        <div id="inf_contact" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Cargo</td>
      <td><input name="jobtitle" type="text" id="jobtitle" value="<?php echo $employer->jobtitle; ?>"/></td>
      
    </tr>
    <tr>
      <td>Telefono/Extension</td>
      <td><input name="telephone" type="text" id="telephone" value="<?php echo $employer->telephone; ?>"/>        
        <input name="extension" type="text" id="extension" value="<?php echo $employer->extension; ?>" size="6"/>
        <div id="inf_telephone" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Celular</td>
      <td><input name="mobile" type="text" id="mobile" value="<?php echo $employer->mobile; ?>"/>
        <div id="inf_mobile" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Fax</td>
      <td><input name="fax" type="text" id="fax" value="<?php echo $employer->fax; ?>"/></td>
    </tr>
    <tr>
      <td>Email</td>
      <td><input name="email" type="text" id="email" value="<?php echo $employer->email; ?>"/>
        <div id="inf_email" class="warn">* </div> </td>
      
    </tr>
    <tr>
      <td>Direccion</td>
      <td><input name="box" type="text" id="box" value="<?php echo $employer->box; ?>"/>
        <div id="inf_box" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Ciudad</td>
      <td><input name="town" type="text" id="town" value="<?php echo $employer->town; ?>"/>
        <div id="inf_town" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Codigo Postal</td>
      <td><input name="zip_postal" type="text" id="zip_postal" value="<?php echo $employer->zip_postal; ?>"/></td>
    </tr>
    <tr>
      <td>Sitio Web: </td>
      <td><input name="website" type="text" id="website" value="<?php echo $employer->website; ?>"/></td>
    </tr>
    <tr>
      <td>Pais</td>
      <td><select name="countryid" id="countryid">
        <option value="">--select country--</option>
        <?php populate_select("countries","countryid","country",$employer->countryid); ?>
      </select>
        <div id="inf_countryid" class="warn">* </div></td>
    </tr>
    <tr align="center">
      <td colspan="2"><input type="submit" name="submit" value="Actualizar" onclick="return validateOnSubmit();" class="button"/>
        <input type="submit" name="submit" value="Siguiente&gt;&gt;" class="button" onClick="return confirm('Desea continuar sin guardar los cambios?','Confirmar Continuar');" />
		</td>
    </tr>

</table>
</form>

</p>
</div>
</div>

<?php ShowLoginBox(); ?>

</div>

<?php ShowFooter(); ?>
