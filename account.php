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
if(isset($_POST["Submit"]) && $_POST["Submit"]=='Logout') LogOut();

if(isset($_POST["Submit"])){
	//check that the oldpass matches the one on the server.
	$oldpass = !empty($_POST["oldpass"]) ? "'" . $_POST["oldpass"] . "'" : 'NULL';
	
	//check that newpass and confirmpass are the same. -checked on client side using javascript but not the best option
	// this makes sure both passwords entered match
	if ($_POST['confirmpass'] !== $_POST['newpass']) {
		$resmsg = AddErrorBox('La nueva clave ingresada no coincide.');
		unset($_POST["Submit"]);
	}else{
		$newpass = !empty($_POST["newpass"]) ? "'" . $_POST["newpass"] . "'" : 'NULL';
		$confirmpass = !empty($_POST["confirmpass"]) ? "'" . $_POST["confirmpass"] . "'" : 'NULL';
		$pass = "'" . md5($_POST['newpass']) . "'";
		$email = !empty($_POST["email"]) ? "'" . $_POST["email"] . "'" : "'$_SESSION[email]'";
		
		if (!get_magic_quotes_gpc()) {
			$pass = "'" . addslashes(md5($_POST['newpass'])) . "'";
		}
	}

	$sql="SELECT pass FROM users WHERE userid=$_SESSION[userid]";
	$resultsuser=query($sql,$conn);
	$users = fetch_object($resultsuser);
	if (md5($_POST["oldpass"]) !== $users->pass) {
		$resmsg = AddErrorBox('La vieja clave ingresada no coincide.');
		unset($_POST["Submit"]);
	}
	free_result($resultsuser);
	
	switch($_POST["Submit"]){
	case "Enviar":
		$sql="UPDATE users SET pass=$pass,email=$email WHERE userid=$_SESSION[userid]";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido actualizar la clave.";
		$msg[1]="Clave actualizada correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		
		//send mail that password has been changed.
		$commentinfo = "$_SESSION[user], \n Su clave en el sitio web de ". WEBSITE_NAME .". ha sido modificada. \n ". WEBSITE_URL ." \n ". WEBSITE_EMAIL;
		if ((int) $results==1)
			sendemail($commentinfo,WEBSITE_EMAIL,WEBSITE_EMAIL_BCC,$email,"Cuenta modificada");
		break;
	}
}
?>

<?php ShowHeader(WEBSITE_NAME ." :: Mi Cuenta"); ?>

<?php ShowDropMenu(); ?>

</div>

<script language="JavaScript" type="text/javascript">
<!--
var request;
var dest;

function ConfirmPass(){
	if (document.forms.account.confirmpass.value!=document.forms.account.newpass.value){
	 alert("Passwords do not match!");
	} 
};

function validateOnSubmit() {
	var elem;
    var errs=0;
	// execute all element validations in reverse order, so focus gets
    // set to the first one in error.
	if (!validatePresent (document.forms.account.confirmpass,'inf_confirmpass')) errs += 1;
	if (!validatePresent (document.forms.account.newpass,'inf_newpass')) errs += 1;
	if (!validatePresent (document.forms.account.oldpass,'inf_oldpass')) errs += 1;

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
	pass = document.getElementById('oldpass').value;
	var str ='oldpass='+pass+'&button='+button;
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

<h2 class="title-bar">Mi Cuenta</h2>

<?php if (isset($resmsg)) echo $resmsg; ?>

<p class="Lastnews">
<form action="account.php" method="post" name="account" id="account" enctype="multipart/form-data">
<table border="0" cellpadding="0">
<tr>
<td>Usuario</td><td>
<?php
	$querystr="SELECT loginname, email FROM users WHERE userid=$_SESSION[userid]";
	$results=query($querystr,$conn);
	$usrdata = fetch_object($results);
	echo strtolower($usrdata->loginname);
?>
</td></tr>
<tr>
<td>Email</td>
<td><input type="text" name="email" value="<?php echo $usrdata->email; ?>"/></td>
</tr>
   <tr>
     <td>Contrase&ntilde;a Actual </td>
     <td><input type="password" name="oldpass" id="oldpass" onblur="loadHTMLPost('ajaxfunctions.php','inf_oldpass','CheckPass')">
       <div id="inf_oldpass" class="warn">* </div></td>
   </tr>
   <tr>
     <td>Nueva Contrase&ntilde;a </td>
     <td><input type="password" name="newpass" id="newpass">
       <div id="inf_newpass" class="warn">* </div></td>
   </tr>
   <tr>
     <td>Confirme Nueva Contrase&ntilde;a</td>
     <td><input type="password" name="confirmpass" id="confirmpass" onBlur="ConfirmPass()">
       <div id="inf_confirmpass" class="warn">* </div></td>
   </tr>
   <tr>
     <td>&nbsp;</td>
     <td><input type="submit" name="Submit" value="Enviar" onclick="return validateOnSubmit();" class="button">
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
