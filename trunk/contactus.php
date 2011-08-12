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

if(isset($_POST["submit"]))
	if ($_POST["submit"] == "Enviar") {
		$commentinfo = "Ha recibido una consulta:\n\n".
			"Nombre   	: $_POST[name]\n".
			"Empresa  	: $_POST[company]\n".
			"Direccion	: $_POST[address]\n".
			"Ciudad   	: $_POST[city]\n".
			"Provincia	: $_POST[state]\n".
			"Codigo Postal:$_POST[zip]\n".
			"Pais 	: $_POST[country]\n".
			"Email 	: $_POST[email]\n".
			"Telefono: $_POST[telephone]\n".
			"Celular : $_POST[mobile]\n".
			"Fax  	: $_POST[fax]\n".
			"Mensaje : $_POST[comment]\n".
			"\n\n". WEBSITE_NAME ."\n". WEBSITE_URL;

		$res = sendemail($commentinfo,WEBSITE_EMAIL,WEBSITE_EMAIL_BCC,$_POST["email"],"Contacto");

		$msg[0]="No se ha podido enviar el mensaje.";
		$msg[1]="Su mensaje fue enviado correctamente.";
		$resmsg = GetResultMsg(1,$msg,$msg);
//		echo $commentinfo;
}

?>

<?php ShowHeader(WEBSITE_NAME ." :: Cont&aacute;ctenos"); ?>

<?php ShowDropMenu(); ?>

</div>

<script language="JavaScript" type="text/javascript">
<!--
function checkForm(){

		//Name
		var name = trim(document.enquiryForm.name.value);
		if(name.length == 0)
		{
		   alert("Please enter Name");
		   document.enquiryForm.name.focus();
		   return false;
		}
		//Address
		var address_1 = trim(document.enquiryForm.address.value);
		if(address_1.length == 0)
		{
		   alert("Please enter Address");
		   document.enquiryForm.address.focus();
		   return false;
		}
		//Country		  		  
		var country = document.enquiryForm.country.value;
		if(country.length == 0)
		{
		   alert("Please Select Country");
		   document.enquiryForm.country.focus();
		   return false;
		}
		//Email
		var email = document.enquiryForm.email.value;
		if(email.length == 0)
		{
			alert("Invalid email");
			document.enquiryForm.email.focus();
			return false;
		}
		var telephone_1 = document.enquiryForm.telephone.value;
		if(telephone_1.length == 0)
		{
		   alert("Please enter Telephone Number");
		   document.enquiryForm.telephone.focus();
		   return false;
		}
		//message-partership/other
		var comment1 = document.enquiryForm.comment.value;
		if(comment1.length == 0)
		{
		   alert("Please enter message");
		   document.enquiryForm.comment.focus();
		   return false;
		}
		return true;
}
//-->
</script>

<div id="content" >

<div id="contentdiv">

<div id="searchtable" >
<img src="images/people.jpg" alt="people" width="575" height="100" border="0" />
</div>


<div id="list">

<h2 class="title-bar">Cont&aacute;ctenos</h2>

<?php if (isset($resmsg)) echo $resmsg; ?>

<p class="Lastnews">
<form action="contactus.php" method="post" name="enquiryForm" onSubmit="return checkForm();">
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr><td width="28%">Nombre<font class="smallred"> *</font></td>
    <td width="72%"><input name="name" type="text" class="inputstyle" id="name" value="" size="33" maxlength="50"> 
		</td></tr>
<tr><td>Empresa</td>
	<td><input name="company" type="text" class="inputstyle" value="" size="41" maxlength="250"></td></tr>
<tr><td>Direcci&oacute;n <font class="smallred"> *</font></td>
	<td><textarea name="address" cols="40" rows="5" class="inputstyle2" ></textarea></td></tr>
<tr><td>Ciudad</td>
	<td><input name="city" type="text" class="inputstyle" value="" size="41" maxlength="50"></td></tr>
<tr><td>Provincia</td>
	<td><input name="state" type="text" class="inputstyle" value="" size="41" maxlength="50"></td></tr>
<tr><td>C&oacute;digo Postal</td>
	<td><input name="zip" type="text" class="inputstyle" value="" size="41" maxlength="50"></td></tr>
<tr><td>Pa&iacute;s<font class="smallred"> *</font></td>
	<td><select name="country" style="width:262px" class="inputstyle">
		<?php populate_select("countries","country","country", "Argentina"); ?>
		</select>
	</td></tr>
<tr><td>Email<font class="smallred"> *</font></td>
	<td><input name="email" type="text" class="inputstyle" value="" size="41" maxlength="50"></td></tr>
<tr><td>Telefono <font class="smallred">*</font></td>
	<td><input name="telephone" type="text" class="inputstyle" value="" size="41" maxlength="50"></td></tr>
<tr><td>Celular</td>
	<td><input name="mobile" type="text" class="inputstyle" id="mobile2" value="" size="41" maxlength="50"></td></tr>
<tr><td>Fax</td>
	<td><input name="fax" type="text" class="inputstyle" id="fax2" value="" size="41" maxlength="50"></td></tr>
<tr><td>Mensaje<font class="smallred"> *</font></td>
<td><textarea name="comment" cols="40" rows="5" class="inputstyle2" id="comment"></textarea></td></tr>
<tr><td></td><td><input type="submit" name="submit" value="Enviar" class="button">&nbsp;<input type="reset" name="Reset" value="Cancelar" class="button"></td></tr>
</table>
</form>

</p>
</div>
</div>

<?php ShowLoginBox(); ?>

</div>

<?php ShowFooter(); ?>
