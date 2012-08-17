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

function make_seed() {
    list($usec, $sec) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
}

if (isset($_POST["submit"]) && $_POST["submit"]=="Enviar Clave"){
srand(make_seed());
$newpass = rand();		
$pass = md5($newpass);
		$loginname = "'" . $_POST[loginname] . "'";
		if (!get_magic_quotes_gpc()) {
			$pass = addslashes($pass);
			$loginname = "'" . addslashes($_POST['loginname']) . "'";
		}

		//The username you have specified does not match any user in our system.
		$sql = "SELECT loginname,email,concat_ws(' ',fname,sname) as usernames FROM users WHERE loginname = '$_POST[loginname]'";
		$results = query($sql,$conn);
		$user = fetch_object($results);
		$email = $user->email;
		$usernames = $user->usernames;
		if(num_rows($results) == 1){
			$sql="UPDATE users SET pass='$pass' WHERE loginname=$loginname";
			$results=query($sql,$conn);
			$msg[0]="No se ha encontrado el usuario.";
			$msg[1]="Nueva clave enviada por e-mail.";
			$resmsg = GetResultMsg($results,$conn,$msg);
			
			$commentinfo = "$usernames,\n Su clave ha sido modificada a: $newpass \n Ahora puede ingresar en ". WEBSITE_URL ." y utilizar el sistema.\n\n". WEBSITE_NAME ."\n". WEBSITE_EMAIL;
			//send new password to user
			if ((int) $results==1)
				sendemail($commentinfo,WEBSITE_EMAIL,WEBSITE_EMAIL_BCC,$email,"Recuperar clave"); 
		} else { //else warn that user does not exist
			$resmsg = AddErrorBox("No se ha encontrado el usuario.");
		}
}
?>

<?php ShowHeader(WEBSITE_NAME ." :: Recuperar clave"); ?>

<?php ShowDropMenu(); ?>

</div>

<script language="JavaScript" type="text/javascript">
<!--



function validateOnSubmit() {
	var elem;
    var errs=0;
	// execute all element validations in reverse order, so focus gets
    // set to the first one in error.
    //if (!validateEmail (document.forms.forgot.email,'inf_email',true)) errs += 1; 
	if (!validatePresent (document.forms.forgot.loginname,'inf_loginname')) errs += 1;	

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

<h2 class="title-bar">Recuperar contrase&ntilde;a</h2>

<?php if (isset($resmsg)) echo $resmsg; ?>

<p class="Lastnews">
<form action="forgot.php" method="post" name="forgot" id="forgot" enctype="multipart/form-data">
<table border="0">
    <tr>
      <td colspan="2">Olvid&oacute; su contrase&ntilde;a?
        <hr>
        Ingrese su e-mail y le enviaremos una nueva contrase&ntilde;a a su correo electr&oacute;nico.
        </td>
      </tr>
    <tr>
      <td>E-Mail:</td>
      <td>
        <input name="loginname" type="text" id="loginname" value=""/>
        <input type="submit" name="submit" value="Enviar Clave" onclick="return validateOnSubmit();" class="button"/>
        <div id="inf_loginname" class="warn">*</div></td>
      </tr>
</table>	
</form>

</p>
</div>
</div>

<?php ShowLoginBox(); ?>

</div>

<?php ShowFooter(); ?>
