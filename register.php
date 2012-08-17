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

//check if user has clicked on logout button
if(isset($_POST["submit"]) && $_POST["submit"]=='Logout') LogOut();

if(isset($_GET["action"])){
	switch($_GET["action"]){
	case "Activate":
		$msg[0]="La cuenta no ha sido activada.";
		$msg[1]="Cuenta activada correctamente.";
		$sql="SELECT COUNT(userid) as verified, email FROM users WHERE userid=$_GET[id] AND pass='". str_rot13($_GET['code']) ."';";
		$resultado = fetch_object(query($sql,$conn));
		if ((int) $resultado->verified==1){

			$sql="UPDATE users SET status='A' WHERE userid=$_GET[id]";
			$results=query($sql,$conn);
			$resmsg = GetResultMsg($results,$conn,$msg);
		
			$commentinfo = "Su cuenta ha sido activada.\n Por favor visite ". WEBSITE_URL ." para acceder a su cuenta.\n\n". WEBSITE_NAME ."\n". WEBSITE_EMAIL;
			sendemail($commentinfo,WEBSITE_EMAIL,WEBSITE_EMAIL_BCC,$resultado->email,"Cuenta Activada");
		} else {
			$resmsg = GetResultMsg(0,$conn,$msg);
		}
		break;
	}
}

if(isset($_POST["submit"])){
	if($_POST["submit"]=="Registrarse" || $_POST["submit"]=="Edit"){
		$userid		= $_POST["userid"];
		$fname		= !empty($_POST["fname"]) ? "'" . $_POST["fname"] . "'" : 'NULL';
		$mname		= !empty($_POST["mname"]) ? "'" . $_POST["mname"] . "'" : 'NULL';
		$sname		= !empty($_POST["sname"]) ? "'" . $_POST["sname"] . "'" : 'NULL';
		$contact	= $_POST["fname"] .' '. $_POST["mname"] .' '. $_POST["sname"];
		$email		= $_POST["email"];
		$dateregistered = !empty($_POST["dateregistered"]) ? "'" . $_POST["dateregistered"] . "'" : 'NULL';
//		$admin = !empty($_POST["admin"]) ? "'" . $_POST["admin"] . "'" : 'NULL';
		$status = !empty($_POST["status"]) ? "'" . $_POST["status"] . "'" : "'D'";
		$usercategory = $_POST["member"]=="E" ? "E" : "A";

		// here we encrypt the password and add slashes if needed
		if (!get_magic_quotes_gpc()) {
			$pass = "'" . addslashes(md5($_POST['pass'])) . "'";
			$loginname = "'" . addslashes(strtoupper($email)) . "'";
		} else {
			$pass = "'" . md5($_POST['pass']) . "'";
			$loginname	= "'" . strtoupper($email) . "'";
		}

		//checks for passwords mismatch and if loginame already exists
		// checks if the username is in use
		//if the name exists loginname exists
		if (UserExists(strtoupper($email))) {
			$resmsg = AddErrorBox("El usuario $email ya existe.");
			unset($_POST["submit"]);
		}
		
		// this makes sure both passwords entered match
		if ($_POST['pass'] !== $_POST['confpass']) {
			$resmsg = AddErrorBox('La clave ingresada no coincide.');
			unset($_POST["submit"]);
		}	

	}
	
	switch($_POST["submit"]){
	case "Registrarse":
		$sql="INSERT INTO users (fname,mname,sname,loginname,pass,email,dateregistered,`status`,usercategory)
				VALUES($fname,$mname,$sname,$loginname,$pass,'$email',now(),$status,'$usercategory')";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido crear la cuenta.";
		$msg[1]="Su cuenta ha sido creada correctamente. <a href=\"index.php\">Click aqui</a>.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		
		$id = mysql_insert_id();
		
		//Update applicant or employer depending on registration.
		if($results)
		{
			if($usercategory=='A'){
				$sql="INSERT INTO applicant (applicantid,surname,mname,fname,hemail) VALUES($id,$sname,$mname,$fname,'$email')";
				$results=query($sql,$conn);
				$msg[0]="No se ha podido agregar el registro de usuario.";
				$msg[1]="Registro de usuario agregado correctamente.";
				$resmsg = GetResultMsg($results,$conn,$msg);
			}
			
			if($usercategory=='E'){
				$sql="INSERT INTO employer (employerid,email,contact) VALUES($id,'$email','$contact')";
				$results=query($sql,$conn);
				$msg[0]="No se ha podido agregar el registro de empresa.";
				$msg[1]="Registro de empresa agregado correctamente.";
				$resmsg = GetResultMsg($results,$conn,$msg);
			}
		}
		$commentinfo = "$contact,\n".
			"Gracias por registrarse en ". WEBSITE_NAME .". Su usuario y clave de acceso se detallan a continuacion:".
			"\n\n-------------------------------------------\n".
			"Usuario: $loginname \n".
			"Clave: $_POST[pass] \n\n\n".
			"Por favor, active su cuenta haciendo click en el siguiente link:\n\n".
			WEBSITE_URL ."/register.php?action=Activate&id=$id&code=". str_rot13(md5($_POST['pass'])) ."\n\n".
			"Muchas gracias,\n\n".
			WEBSITE_NAME ."\n".
			"E-Mail: ". WEBSITE_EMAIL ."\n".
			"Website: ". WEBSITE_URL;
		if ((int) $results==1) 
			sendemail($commentinfo,WEBSITE_EMAIL,WEBSITE_EMAIL_BCC,$email,"Registro de cuenta");
		break;	
	case "Edit":
		$userid=$_POST["userid"];
		$sql="UPDATE users SET fname=$fname,sname=$sname,loginname=$loginname,pass=$pass,email=$email,
				dateregistered=$dateregistered,`status`=$status,usercategory=$usercategory
			WHERE userid=$_POST[userid]";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido actualizar el registro de usuario.";
		$msg[1]="Registro de usuario actualizado correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Delete":
		$sql = "DELETE FROM users WHERE userid=$userid";
		break;
	case "Find":
		$sql = "SELECT * FROM users	WHERE userid=$userid";
		$results=query($sql,$conn);
		$user = fetch_object($results);		
		break;
	}
}
?>

<?php ShowHeader(WEBSITE_NAME ." :: Registrarse"); ?>

<?php ShowDropMenu(); ?>

</div>

<script language="JavaScript" type="text/javascript">
<!--
var request;
var dest;

function ConfirmPass(){
	if (document.forms.register.confpass.value!=document.forms.register.pass.value){
		alert("Las claves ingresadas no coinciden.");
	}	
};

function validateOnSubmit() {
	var elem;
    var errs=0;
	// execute all element validations in reverse order, so focus gets
    // set to the first one in error.
    if (!validateEmail (document.forms.register.email,'inf_email',true)) errs += 1; 
	if (!validatePresent (document.forms.register.confpass,'inf_confpass')) errs += 1;
	if (!validatePresent (document.forms.register.pass,'inf_pass')) errs += 1;
	if (!validatePresent (document.forms.register.fname,'inf_fname')) errs += 1;	
	if (!validatePresent (document.forms.register.sname,'inf_sname')) errs += 1;	

    if (errs>1)  alert('Hay campos que deben corregirse antes de enviar los datos.');
    if (errs==1) alert('Hay un campo que debe corregirse antes de enviar los datos.');

    return (errs==0);
};
  
function processStateChange(){
    if (request.readyState == 4){
        contentDiv = document.getElementById(dest);
        if (request.status == 200){
            response = request.responseText;
            contentDiv.innerHTML = response;
        } else {
            contentDiv.innerHTML = "Error: Status "+request.status;
        }
    }
}

function loadHTMLPost(URL, destination, button){
    dest = destination;
	loginname = document.getElementById('loginname').value;
	var str ='loginname='+loginname+'&button='+button;
		
	if (window.XMLHttpRequest){
        request = new XMLHttpRequest();
        request.onreadystatechange = processStateChange;
        request.open("POST", URL, true);
        request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		request.send(str);
    } else if (window.ActiveXObject) {
        request = new ActiveXObject("Microsoft.XMLHTTP");
        if (request) {
            request.onreadystatechange = processStateChange;
            request.open("POST", URL, true);
            request.send();
        }
    }
}
//-->	 
</script>

<div id="content" >

<div id="contentdiv">

<div id="searchtable" >
<img src="images/people.jpg" alt="people" width="575" height="100" border="0" />
</div>

<div id="list">

<h2 class="title-bar">Registrarse</h2>

<?php if (isset($resmsg)) echo $resmsg; ?>

<p class="Lastnews">
<form action="register.php" method="post" name="register" id="register" enctype="multipart/form-data">
<input type="hidden" name="member" value="<?php echo $_GET["member"]; ?>" />

<table border="0" align="center">
    <tr>
      <td>Apellido</td>
      <td>
        <input name="sname" type="text" id="sname" value="<?php echo $user->sname; ?>"/>
		<div id="inf_sname" class="warn">* </div>
      </td>
      </tr>
    <tr>
      <td>Nombre </td>
      <td><input name="fname" type="text" id="fname" value="<?php echo $user->fname; ?>"/>
	  <div id="inf_fname" class="warn">* </div></td>
    </tr>
    <tr>
      <td>Segundo Nombre </td>
      <td><input name="mname" type="text" id="mname" value="<?php echo $user->mname; ?>"/></td>
    </tr>
    <tr>
      <td> Email</td>
      <td>
        <input name="email" type="text" id="email" value="<?php echo isset($_POST["email"]) ? $_POST["email"] : $user->email; ?>" width="200" onblur="loadHTMLPost('ajaxfunctions.php','inf_email','CheckLoginname')"/><div id="inf_email" class="warn">* </div> </td>
      </tr>
    <tr>
      <td>Contrase&ntilde;a</td>
      <td>
        <input name="pass" type="password" id="pass" value="<?php echo $user->pass; ?>"/><div id="inf_pass" class="warn">* </div></td>
      </tr>
    <tr>
      <td>Confirmar Contrase&ntilde;a </td>
      <td>
        <input name="confpass" type="password" id="confpass" value="<?php echo $user->pass; ?>" onBlur="ConfirmPass()"/><div id="inf_confpass" class="warn">* </div></td>
      </tr>
    <tr align="center">
      <td colspan="2"><input type="submit" name="submit" value="<?php echo isset($_GET["search"]) ? "Edit" : "Registrarse"; ?>" onclick="return validateOnSubmit();" class="button"/>
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
