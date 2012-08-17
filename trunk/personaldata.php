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
	$applicantid=$_GET["search"];
	$_POST["submit"]="Find";
}

if(isset($_POST["submit"])){
	if($_POST["submit"]=="Guardar" || $_POST["submit"]=="Actualizar"){
		//personal details
		$id =(!empty($_POST["id"])) ? $_POST["id"] : 'NULL';
		$applicantid =(!empty($_POST["applicantid"])) ? $_POST["applicantid"] : 'NULL';		
		$salutation = !empty($_POST["salutation"]) ? "'" . $_POST["salutation"] . "'" : 'NULL';
		$surname = !empty($_POST["surname"]) ? "'" . $_POST["surname"] . "'" : 'NULL';
		$mname = !empty($_POST["mname"]) ? "'" . $_POST["mname"] . "'" : 'NULL';
		$fname = !empty($_POST["fname"]) ? "'" . $_POST["fname"] . "'" : 'NULL';
		$sex = !empty($_POST["sex"]) ? "'" . $_POST["sex"] . "'" : 'NULL';
		$mstatus = !empty($_POST["mstatus"]) ? "'" . $_POST["mstatus"] . "'" : 'NULL';
		$dob = !empty($_POST["dob"]) ? "'" . dateconvert($_POST["dob"],1) . "'" : 'NULL';
		$nationality =(!empty($_POST["nationality"])) ? $_POST["nationality"] : 'NULL';
		$citizenship =(!empty($_POST["citizenship"])) ? $_POST["citizenship"] : 'NULL';
		$ctoforigin =(!empty($_POST["ctoforigin"])) ? $_POST["ctoforigin"] : 'NULL';
		$documento = !empty($_POST["documento"]) ? "'" . $_POST["documento"] . "'" : 'NULL';
		$tipodoc = !empty($_POST["tipodoc"]) ? "'" . $_POST["tipodoc"] . "'" : 'NULL';
		$driverlic = !empty($_POST["driverlic"]) ? "'" . $_POST["driverlic"] . "'" : 'NULL';
		$hbox = !empty($_POST["hbox"]) ? "'" . $_POST["hbox"] . "'" : 'NULL';
		$htown = !empty($_POST["htown"]) ? "'" . $_POST["htown"] . "'" : 'NULL';
		$hzip_postal = !empty($_POST["hzip_postal"]) ? "'" . $_POST["hzip_postal"] . "'" : 'NULL';
		$hcountry =(!empty($_POST["hcountry"])) ? $_POST["hcountry"] : 'NULL';
		$hphone = !empty($_POST["hphone"]) ? "'" . $_POST["hphone"] . "'" : 'NULL';
		$hmobile = !empty($_POST["hmobile"]) ? "'" . $_POST["hmobile"] . "'" : 'NULL';
		$hemail = !empty($_POST["hemail"]) ? "'" . $_POST["hemail"] . "'" : 'NULL';
		$obox = !empty($_POST["obox"]) ? "'" . $_POST["obox"] . "'" : 'NULL';
		$otown = !empty($_POST["otown"]) ? "'" . $_POST["otown"] . "'" : 'NULL';
		$ozip_postal = !empty($_POST["ozip_postal"]) ? "'" . $_POST["ozip_postal"] . "'" : 'NULL';
		$ocountry =(!empty($_POST["ocountry"])) ? $_POST["ocountry"] : 'NULL';
		$ophone = !empty($_POST["ophone"]) ? "'" . $_POST["ophone"] . "'" : 'NULL';
		$omobile = !empty($_POST["omobile"]) ? "'" . $_POST["omobile"] . "'" : 'NULL';
		$oemail = !empty($_POST["oemail"]) ? "'" . $_POST["oemail"] . "'" : 'NULL';
	}
	switch($_POST["submit"]){
	case "Guardar":
		$sql="INSERT INTO applicant (salutation,surname,mname,fname,sex,mstatus,dob,nationality,citizenship,ctoforigin,hbox,htown,hzip_postal,
				hcountry,hphone,hmobile,hemail,obox,otown,ozip_postal,ocountry,ophone,omobile,oemail,driverlic,tipodoc,documento)
			VALUES($salutation,$surname,$mname,$fname,$sex,$dob,$nationality,$citizenship,$ctoforigin,$hbox,$htown,$hzip_postal,
				$hcountry,$hphone,$hmobile,$hemail,$obox,$otown,$ozip_postal,$ocountry,$ophone,$omobile,$oemail,$driverlic,$tipodoc,$documento)";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido agregar el registro.";
		$msg[1]="Registro agregado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		header("Location: careerobjective.php");
		break;	
	case "Actualizar":
		$sql="UPDATE applicant SET salutation=$salutation,surname=$surname,mname=$mname,fname=$fname,
				sex=$sex,mstatus=$mstatus,dob=$dob,nationality=$nationality,citizenship=$citizenship,ctoforigin=$ctoforigin,
				hbox=$hbox,htown=$htown,hzip_postal=$hzip_postal,hcountry=$hcountry,hphone=$hphone,
				hmobile=$hmobile,hemail=$hemail,obox=$obox,otown=$otown,ozip_postal=$ozip_postal,
				ocountry=$ocountry,ophone=$ophone,omobile=$omobile,oemail=$oemail,
				driverlic=$driverlic,tipodoc=$tipodoc,documento=$documento
			WHERE id=$id";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido actualizar el registro.";
		$msg[1]="Registro actualizado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		header("Location: personaldata.php?search=$applicantid");
		break;
	case "Find":
		$sql = "SELECT * FROM applicant	WHERE applicantid=$applicantid";
		$results=query($sql,$conn);
		$applicant = fetch_object($results);		
		break;
	case "Siguiente>>":
		header("Location: careerobjective.php");
		break;				
	}
}
?>

<?php ShowHeader(WEBSITE_NAME ." :: Datos Personales"); ?>

<?php ShowDropMenu(); ?>

</div>

<script language="JavaScript" type="text/javascript">
<!--


var dp_cal;  

window.onload = function () {
	dp_cal  = new Epoch('epoch_popup','popup',document.getElementById('dob'));
};

function validateOnSubmit() {
	var elem;
    var errs=0;
	// execute all element validations in reverse order, so focus gets
    // set to the first one in error.
	if (!validatePresent (document.forms.personaldata.surname,'inf_surname')) errs += 1;
	if (!validatePresent (document.forms.personaldata.fname,'inf_fname')) errs += 1;
	if (!validateNum (document.forms.personaldata.documento,'inf_documento',true)) errs += 1;
	if (!validateSelect (document.forms.personaldata.hcountry,'inf_hcountry',1)) errs += 1;
	if (!validateEmail (document.forms.personaldata.hemail,'inf_hemail',1)) errs += 1;
	if (!validatePresent (document.forms.personaldata.hbox,'inf_hbox')) errs += 1;

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

<h2 class="title-bar">Datos Personales</h2>

<?php if (isset($resmsg)) echo $resmsg; ?>

<p class="Lastnews">
<form action="personaldata.php" method="post" name="personaldata" id="personaldata" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo $applicant->id; ?>">
<input type="hidden" name="applicantid" value="<?php echo $applicant->applicantid; ?>">
 <table border="0">
    <tr>
      <td colspan="2"><em>Los campos marcados con <span class="warn">*</span> son campos obligatorios y deben completarse.</em></td>
    </tr>
    <tr>
      <td>Titulo/Apellido</td>
      <td><select name="salutation" id="salutation">
          <option value="">seleccione</option>
<?php
	$salarr = array("Sr.", "Sra.", "Srta.", "Dr.", "Lic.", "Ing.", "Ph.D.", "Mg.");
	foreach($salarr as $sal) {
		echo "<option value=\"$sal\"";
		if($applicant->salutation==$sal)
			echo ' selected';
		echo ">$sal</option>";
	}
?>
        </select>
        /
          <input name="surname" type="text" id="surname" value="<?php echo $applicant->surname; ?>"/><div id="inf_surname" class="warn">* </div></td>
      
    </tr>
    <tr>
      <td>Nombre </td>
      <td><input name="fname" type="text" id="fname" value="<?php echo $applicant->fname; ?>"/>
        <div id="inf_fname" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Segundo Nombre </td>
      <td><input name="mname" type="text" id="mname" value="<?php echo $applicant->mname; ?>"/></td>
    </tr>
    <tr>
      <td>Sexo</td>
      <td>
        <label><input type="radio" name="sex" value="M" <?php if($applicant->sex=='M') echo 'checked'; ?>>Masculino</label>
        <label><input type="radio" name="sex" value="F" <?php if($applicant->sex=='F') echo 'checked'; ?>>Femenino</label>
	</td>
    </tr>
    <tr>
      <td>Estado Civil </td>
      <td><select name="mstatus" id="mstatus">
        <option value="">--seleccione--</option>
<?php
	$salarr = array("Soltero", "Casado", "Divorciado", "Viudo", "Separado");
	foreach($salarr as $sal) {
		echo "<option value=\"$sal\"";
		if($applicant->mstatus==$sal)
			echo ' selected';
		echo ">$sal</option>";
	}
?>
      </select></td>
    </tr>
    <tr>
      <td>Fecha de nacimiento </td>
      <td><input name="dob" type="text" id="dob" value="<?php echo dateconvert($applicant->dob,2); ?>"/></td>
      
    </tr>
    <tr>
      <td>Nacionalidad</td>
      <td><select name="nationality" id="nationality">
        <option value="">--seleccione--</option>
        <?php populate_select("countries","countryid","country",$applicant->nationality); ?>
      </select></td>
    </tr>
    <tr>
      <td>Ciudadania </td>
      <td><select name="citizenship" id="citizenship"">
        <option value="">--seleccione--</option>
        <?php populate_select("countries","countryid","country",$applicant->citizenship); ?>
      </select></td>
    </tr>
    <tr>
      <td>Pais de Origen </td>
      <td><select name="ctoforigin" id="ctoforigin"">
        <option value="">--seleccione--</option>
        <?php populate_select("countries","countryid","country",$applicant->ctoforigin); ?>
      </select></td>
    </tr>
    <tr>
      <td>Tipo/Nro. de Documento</td>
      <td>

        <select name="tipodoc" id="tipodoc">
          <option value="">seleccione</option>
<?php
	$salarr = array("DNI", "LE", "LC", "CI", "EXT");
	foreach($salarr as $sal) {
		echo "<option value=\"$sal\"";
		if($applicant->tipodoc==$sal)
			echo ' selected';
		echo ">$sal</option>";
	}
?>
		</select>
		<input name="documento" type="text" id="documento" value="<?php echo $applicant->documento; ?>"/><div id="inf_documento" class="warn">*</div></td>
      </tr>
    <tr>
      <td>Registro de Conducir</td>
      <td><select name="driverlic" id="driverlic">
          <option value="">seleccione</option>
<?php
	$salarr = array("No posee", "Moto", "Automovil", "Camioneta", "Camion");
	foreach($salarr as $sal) {
		echo "<option value=\"$sal\"";
		if($applicant->driverlic==$sal)
			echo ' selected';
		echo ">$sal</option>";
	}
?>
        </select>
		<div id="inf_driverlic" class="warn">* </div></td>
      
    </tr>
    <tr class="odd">
      <td colspan="2"><strong>DATOS DE CONTACTO</strong></td>
    </tr>
    <tr class="boldtext">
      <td colspan="2">Domicilio Particular</td>
    </tr>
    <tr>
      <td>Direccion</td>
      <td><input name="hbox" type="text" id="hbox" value="<?php echo $applicant->hbox; ?>"/>
        <div id="inf_hbox" class="warn">* </div></td>
      
    </tr>
    <tr>
      <td>Ciudad</td>
      <td><input name="htown" type="text" id="htown" value="<?php echo $applicant->htown; ?>"/></td>
    </tr>
    <tr>
      <td>Codigo Postal </td>
      <td><input name="hzip_postal" type="text" id="hzip_postal" value="<?php echo $applicant->hzip_postal; ?>"/></td>
    </tr>
    <tr>
      <td>Pais</td>
      <td><select name="hcountry" id="hcountry">
        <option value="">--seleccione--</option>
        <?php populate_select("countries","countryid","country",$applicant->hcountry); ?>
      </select>
        <div id="inf_hcountry" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Telefono</td>
      <td><input name="hphone" type="text" id="hphone" value="<?php echo $applicant->hphone; ?>"/></td>
    </tr>
    <tr>
      <td>Celular </td>
      <td><input name="hmobile" type="text" id="hmobile" value="<?php echo $applicant->hmobile; ?>"/></td>
    </tr>
    <tr>
      <td>Email</td>
      <td><input name="hemail" type="text" id="hemail" value="<?php echo $applicant->hemail; ?>"/>
        <div id="inf_hemail" class="warn">* </div> </td>
      
    </tr>
    <tr class="boldtext">
      <td colspan="2">Domicilio Laboral</td>
    </tr>
	<tr>
      <td>Direccion</td>
      <td><input name="obox" type="text" id="obox" value="<?php echo $applicant->obox; ?>"/>
      <!--  <div id="inf_obox" class="warn">* </div> --></td>
      
    </tr>
    <tr>
      <td>Ciudad</td>
      <td><input name="otown" type="text" id="otown" value="<?php echo $applicant->otown; ?>"/></td>
    </tr>
    <tr>
      <td>Codigo Postal </td>
      <td><input name="ozip_postal" type="text" id="ozip_postal" value="<?php echo $applicant->ozip_postal; ?>"/></td>
    </tr>
    <tr>
      <td>Pais</td>
      <td><select name="ocountry" id="ocountry">
        <option value="">--seleccione--</option>
        <?php populate_select("countries","countryid","country",$applicant->ocountry); ?>
      </select></td>
    </tr>
    <tr>
      <td>Telefono</td>
      <td><input name="ophone" type="text" id="ophone" value="<?php echo $applicant->ophone; ?>"/></td>
    </tr>
    <tr>
      <td>Celular </td>
      <td><input name="omobile" type="text" id="omobile" value="<?php echo $applicant->omobile; ?>"/></td>
    </tr>
    <tr>
      <td>Email</td>
      <td><input name="oemail" type="text" id="oemail" value="<?php echo $applicant->oemail; ?>"/> </td>
      
    </tr>
    <tr align="center">
      <td colspan="2"><input type="submit" name="submit" value="<?php echo ($_GET["action"]=="Find" || isset($_GET["search"])) ? "Actualizar" : "Guardar"; ?>" onclick="return validateOnSubmit();" class="button"/>
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
