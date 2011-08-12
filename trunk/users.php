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
SignedInAdmin();

//check if user has clicked on logout button
if(isset($_POST["Submit"]) && $_POST["Submit"]=='Logout') LogOut();

if(isset($_GET["action"])){
	switch($_GET["action"]){
	case "Activate":
		$sql="UPDATE users SET status='A' WHERE userid=$_GET[id]";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido activar la cuenta.";
		$msg[1]="Cuenta activada correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Lock":
		$sql="UPDATE users SET status='L' WHERE userid=$_GET[id]";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido desactivar la cuenta.";
		$msg[1]="Cuenta desactivada correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Changepass":
		$newpass = rand();		
		$pass = md5($newpass);
		$sql="UPDATE users SET pass='$pass' WHERE userid=$_GET[id]";
		$results=query($sql,$conn);
		$msg[0]="No se ha encontrado el usuario.";
		$msg[1]="La nueva clave ($newpass) ha sido enviada por e-mail.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		
		//send mail to user and employer
		$commentinfo = "$usernames,\n Su clave ha sido modificada a: $newpass \n Ahora puede ingresar en ". WEBSITE_URL ." y utilizar el sistema.\n\n". WEBSITE_NAME ."\n". WEBSITE_EMAIL;
		//send new password to user
		if ((int) $results==1)
			sendemail($commentinfo,WEBSITE_EMAIL,WEBSITE_EMAIL_BCC,$email,"Recuperar clave");
		break;
	case "Delete":
		$sql="DELETE FROM users WHERE userid=$_GET[search]";
		$results=query($sql,$conn);
		$msg[0]="No se ha podido eliminar la cuenta.";
		$msg[1]="Cuenta eliminada correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	case "Impersonate":
		$sql = "SELECT userid,admin,email,usercategory,status,concat_ws(' ', fname, sname) AS user, pass FROM users WHERE userid = $_GET[id]";
		$results = query($sql,$conn);
		$user = fetch_object($results);
		
		$_SESSION = array();
		session_destroy();
		session_start();

		$_SESSION["user"]=$user->user;
		$_SESSION["userid"]=$user->userid;
		$_SESSION["admin"]=$user->admin; //for rights use
		$_SESSION["email"]=$user->email; //for rights use
		$_SESSION["usercategory"]=$user->usercategory;

		$msg[0]="No se ha podido impersonar la cuenta.";
		$msg[1]="Cuenta impersonada correctamente.";
		$resmsg = GetResultMsg($results,$conn,$msg);
		break;
	}
	$_GET["jobid"]=$_POST["jobid"]; //display the job again
}

?>

<?php ShowHeader(WEBSITE_NAME ." :: Administraci&oacute;n"); ?>

<?php ShowDropMenu(); ?>

</div>

<div id="content" >

<div id="contentdiv">

<div id="searchtable" >
<img src="images/people.jpg" alt="people" width="575" height="100" border="0" />
</div>

<div id="list">

<h2 class="title-bar">Administraci&oacute;n</h2>

<?php if (isset($resmsg)) echo $resmsg; ?>

<p class="Lastnews">
<form action="users.php" method="POST"  name="users" id="users" enctype="multipart/form-data">
<table border="0" width="100%">
    <tr>
      <td align="center">
        <label><input type="radio" name="users" value="U">Usuarios</label>
		<label><input type="radio" name="users" value="J">B&uacute;squedas</label>
		<label><input type="radio" name="users" value="S">Postulaciones</label>
        <label><input type="radio" name="users" value="R">Empresas</label>
	</td>
	<td>
        <input type="submit" name="submit" value="Listar" class="button"/>
	</td>
    </tr>
</table>
</form>

<?php
if(isset($_POST["submit"])){
	if($_POST["submit"]=="Listar") {
		switch($_POST["users"]){
			case 'U':
				$querystr="SELECT userid,concat_ws(' ',fname,mname,sname) as taifauser,loginname,email,dateregistered,admin,`status`,usercategory,loginname,tipodoc
						FROM users
						ORDER BY taifauser";
				$results=query($querystr,$conn);
				//check if data is returned
				echo "<table border=\"0\" width=\"100%\">";  		
				echo "<tr class=\"boldtext\"><td>Nombre</td><td>Documento</td><td>Fecha</td><td width=\"110\">Acciones</td></tr>";
				while ($users = fetch_object($results)){
					//alternate row colour
					$j++;
					if($j%2==1){
						echo "<tr id=\"row$j\">";
					}else{
						echo "<tr id=\"row$j\" class=\"odd\">";
					}	  
					echo "<td align=\"left\"><img border=\"0\" width=\"16\" height=\"16\" src=\"images/user_";
					if ($users->admin == 1)
						echo "admin";
					else
						echo "$users->usercategory$users->status";
					echo ".png\" alt=\"usuario\" />$users->taifauser</td>
						<td align=\"left\">$users->tipodoc $users->loginname</td>
						<td align=\"left\">$users->dateregistered</td>
						<td align=\"left\">";
					if ($users->usercategory == 'A')
						echo "<a name=\"editexperience\" href=\"viewcv.php?applicant=$users->userid\" target='_blank'><img src=\"images/button_view.png\" title=\"Ver CV\" border=\"0\" width=\"16\" height=\"16\"/></a>";

					if ($users->status == 'A')
						echo "<a name=\"deactivateuser\" href=\"users.php?id=$users->userid&action=Lock\"><img src=\"images/user_delete.png\" title=\"Desactivar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
					else
						echo "<a name=\"activateuser\" href=\"users.php?id=$users->userid&action=Activate\"><img src=\"images/user_add.png\" title=\"Activar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
					
					echo "<a name=\"deleteuser\" href=\"users.php?search=$users->userid&action=Delete\" onClick=\"Javascript:return confirm('Confirma que desea Borrar el Registro?','Confirmar Eliminar')\"><img src=\"images/button_remove.png\" title=\"Borrar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
					echo "<a name=\"impersonateuser\" href=\"users.php?id=$users->userid&action=Impersonate\"><img src=\"images/user_go.png\" title=\"Impersonar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
					echo "<a name=\"resetuser\" href=\"users.php?id=$users->userid&action=Changepass\"><img src=\"images/key.png\" title=\"Reset password\" border=\"0\" width=\"16\" height=\"16\"/></a></td></tr>";
				}
				echo "</table>";
				break;

		case 'S':
			$querystr="SELECT applications.id,applications.applicantid,applications.jobid,applications.dateapplied,
					applications.shortlisted,concat_ws(' ',applicant.salutation,applicant.fname,applicant.mname,applicant.surname) as applicant,job.jobtitle,
					employer.organization,job.dateposted,job.dateclosing
				FROM applications
				Left Join job ON applications.jobid = job.jobid
				Left Join employer ON job.employerid = employer.employerid
				Left Join applicant ON applications.applicantid = applicant.applicantid";
			$results=query($querystr,$conn);
			//check if data is returned
			echo "<table border=\"0\" width=\"100%\">";  		
			echo "<tr class=\"boldtext\"><td>Postulante</td><td>Posicion</td><td>Empresa</td><td>Fecha</td><td>Fecha de cierre</td><td>Ver/Borrar</td></tr>";
			while ($myjobs = fetch_object($results)){
				//alternate row colour
				$j++;
				if($j%2==1){
					echo "<tr id=\"row$j\">";
				}else{
					echo "<tr id=\"row$j\" class=\"odd\">";
				}	  
				echo "<td align=\"left\">$myjobs->applicant</td>
					<td align=\"left\"><a name=\"viewjob\" href=\"jobdetails.php?jobid=$myjobs->jobid\" target='_blank'>$myjobs->jobtitle</a></td>
					<td align=\"left\">$myjobs->organization</td>						
					<td align=\"left\">$myjobs->dateapplied</td>
					<td align=\"left\">$myjobs->dateclosing</td>
					<td align=\"left\"><a name=\"editexperience\" href=\"viewcv.php?applicant=$myjobs->applicantid\" target='_blank'><img src=\"images/button_view.png\" alt=\"Ver CV\" border=\"0\" width=\"16\" height=\"16\"/></a>";
				echo "<a name=\"deleteapplication\" href=\"myjobs.php?search=$myjobs->id&action=Delete\" onClick=\"Javascript:return confirm('Confirma que desea Borrar el Registro?','Confirmar Eliminar')\"><img src=\"images/button_remove.png\" alt=\"Borrar\" border=\"0\" width=\"16\" height=\"16\"/></a></td></tr>";
			}
			echo "</table>";
			break;

		case 'R':
			$querystr="SELECT employer.id,employer.employerid,employer.organization,employer.contact,employer.jobtitle,
					employer.telephone,employer.fax,employer.extension,employer.email,employer.box,employer.town,
					employer.zip_postal,employer.website,countries.countrycode,users.status
				FROM employer
				Left Join countries ON employer.countryid = countries.countryid
				Left Join users ON employer.employerid = users.userid";
			$results=query($querystr,$conn);
			//check if data is returned
			echo "<table border=\"0\" width=\"100%\">";  		
			echo "<tr class=\"boldtext\"><td>Contacto</td><td>Empresa</td><td>Telefono</td><td>Ciudad</td><td width=\"45\">Links</td><td width=\"110\">Acciones</td></tr>";
			while ($users = fetch_object($results)){
				//alternate row colour
				$j++;
				if($j%2==1){
					echo "<tr id=\"row$j\">";
				}else{
					echo "<tr id=\"row$j\" class=\"odd\">";
				}	  
				echo "<td align=\"left\">$users->contact</td>
					<td align=\"left\">$users->organization</td>
					<td align=\"left\">$users->telephone</td>
					<td align=\"left\">$users->town, $users->countrycode</td>
					<td align=\"left\"><a href=\"mailto:$users->email\"><img src=\"images/email.png\" alt=\"Email\" border=\"0\" width=\"16\" height=\"16\"/></a><a href=\"$users->website\"><img src=\"images/world.png\" alt=\"Website\" border=\"0\" width=\"16\" height=\"16\"/></a></td>
					<td align=\"left\">";

				if ($users->status == 'A')
					echo "<a name=\"deactivateuser\" href=\"users.php?id=$users->employerid&action=Lock\"><img src=\"images/user_delete.png\" title=\"Desactivar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
				else
					echo "<a name=\"activateuser\" href=\"users.php?id=$users->employerid&action=Activate\"><img src=\"images/user_add.png\" title=\"Activar\" border=\"0\" width=\"16\" height=\"16\"/></a>";

				echo "<a name=\"deleteuser\" href=\"users.php?search=$users->employerid&action=Delete\" onClick=\"Javascript:return confirm('Confirma que desea Borrar el Registro?','Confirmar Eliminar')\"><img src=\"images/button_remove.png\" title=\"Borrar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
				echo "<a name=\"edituser\" href=\"employer.php?search=$users->employerid\"><img src=\"images/user_edit.png\" title=\"Editar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
				echo "<a name=\"impersonateuser\" href=\"users.php?id=$users->employerid&action=Impersonate\"><img src=\"images/user_go.png\" title=\"Impersonar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
				echo "<a name=\"resetuser\" href=\"users.php?id=$users->employerid&action=Changepass\"><img src=\"images/key.png\" title=\"Reset password\" border=\"0\" width=\"16\" height=\"16\"/></a></td></tr>";

					/*<a name=\"edituser\" href=\"employer.php?search=$users->employerid\"><img src=\"images/edit.gif\" alt=\"Editar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
				echo "<a name=\"deleteuser\" href=\"employer.php?search=$users->employerid&action=Delete\" onClick=\"Javascript:return confirm('Confirma que desea Borrar el Registro?','Confirmar Eliminar')\"><img src=\"images/delete.gif\" alt=\"Borrar\" border=\"0\" width=\"16\" height=\"16\"/></a></td></tr>";*/
			}
			echo "</table>";
			break;

		case 'J':
			$querystr="SELECT employer.employerid,employer.organization,job.jobid,job.employerid,job.jobcategory,
					job.employeetype,job.city,job.countryid,job.jobtitle,job.summary,job.description,job.requirements,
					job.dateposted,job.dateclosing,job.contactinfo,job.pay,countries.country
				FROM employer
					Inner Join job ON employer.employerid = job.employerid
					Inner Join countries ON job.countryid = countries.countryid";
			
			$results=query($querystr,$conn);
			//check if data is returned
			echo "<table border=\"0\" width=\"100%\">";  		
			echo "<tr class=\"boldtext\"><td>Empresa</td><td>Posici&oacute;n</td><td>Desde</td><td>Hasta</td><td>Ver/Editar/Borrar</td></tr>";
			while ($joblist = fetch_object($results)){
				//alternate row colour
				$j++;
				if($j%2==1){
					echo "<tr id=\"row$j\">";
				}else{
					echo "<tr id=\"row$j\" class=\"odd\">";
				}	  
				echo "<td align=\"left\">$joblist->organization</td>
					<td align=\"left\">$joblist->jobtitle</td>
					<td align=\"left\">$joblist->dateposted</td>
					<td align=\"left\">$joblist->dateclosing</td>
					<td align=\"left\">";
				echo "<a name=\"editjob\" href=\"jobdetails.php?jobid=$joblist->jobid\" target='_blank'><img src=\"images/button_view.png\" alt=\"Ver busqueda\" border=\"0\" width=\"16\" height=\"16\"/></a>";
				echo "<a name=\"editjob\" href=\"jobs.php?search=$joblist->jobid&action=Find\"><img src=\"images/edit.gif\" alt=\"Editar\" border=\"0\" width=\"16\" height=\"16\"/></a>";
				echo "<a name=\"deletejob\" href=\"jobs.php?search=$joblist->jobid&action=Delete\" title=\"delete jobs record\" onClick=\"Javascript:return confirm('Confirma que desea Borrar el Registro?','Confirmar Eliminar')\"><img src=\"images/delete.gif\" alt=\"Borrar\" border=\"0\" width=\"16\" height=\"16\"/></a></td></tr>";
			}
			echo "</table>";
			break;		
		}
	}
}
?>

</p>

<h2 class="title-bar">Filtrar Usuarios</h2>
<p class="Lastnews">

<form action="users.php" method="POST"  name="users" id="users" enctype="multipart/form-data">
<table border="0" width="100%">
    <tr>
      <td>Ciudad</td>
      <td><input name="htown" type="text" id="htown" value="<?php echo $applicant->htown; ?>"/></td>
    </tr>
    <tr>
      <td>Sexo</td>
      <td><select name="sex" id="sex">
          <option value="">--seleccione--</option>
          <option value="M">Masculino</option>
          <option value="F">Femenino</option>
        </select>
	</td>
    </tr>
    <tr>
      <td>Edad</td>
      <td><select name="age" id="age">
          <option value="">--seleccione--</option>
          <option value="20">0-20</option>
          <option value="30">21-30</option>
          <option value="40">31-40</option>
          <option value="50">41-50</option>
          <option value="60">51-60</option>
          <option value="70">61-70</option>
          <option value="80">71-80</option>
        </select>
	</td>
    </tr>
    <tr>
      <td>Registro de Conducir</td>
      <td><select name="driverlic" id="driverlic">
          <option value="">--seleccione--</option>
<?php
	$salarr = array("No posee", "Moto", "Automovil", "Camioneta", "Camion");
	foreach($salarr as $sal) {
		echo "<option value=\"$sal\"";
		if($applicant->driverlic==$sal)
			echo ' selected';
		echo ">$sal</option>";
	}
?>
        </select></td>      
    </tr>
    <tr>
      <td>Profesi&oacute;n</td>
      <td><input name="profesion" type="text" id="profesion" value="<?php echo $applicant->htown; ?>"/></td>
    </tr>
    <tr>
      <td>Idioma
        <input type="hidden" name="langid" value=<?php echo $language->id; ?>></td>
      <td>
	  <select name="language" id="language">
        <option value="">--seleccione--</option>
        <?php populate_select("languages","language","language",$language->language); ?>
      </select>
	  </td>
    </tr>
<tr>
	<td colspan="2">
        <input type="submit" name="submit" value="Buscar" class="button"/>
	</td>
</tr>
</table>
</form>

<?php

if(isset($_POST["submit"])){
	if($_POST["submit"]=="Buscar") {
		$ciudad = $_POST["htown"];
		$sexo = $_POST["sex"];
		$regcond = $_POST["driverlic"];
		$edad = $_POST["age"];
		$idioma = $_POST["language"];
		$profesion = $_POST["profesion"];
		
		$querystr = "SELECT applicant.applicantid, concat_ws(' ',salutation,fname,mname,surname) as applicant, mstatus, sex, dob, nationality, htown, driverlic, fieldofstudy, language, jobtitle
 FROM applicant
 LEFT JOIN education ON applicant.applicantid = education.applicantid
 LEFT JOIN language ON applicant.applicantid = language.applicantid
 LEFT JOIN experience ON applicant.applicantid = experience.applicantid
 WHERE 1=1 ";
 		if ($ciudad != '')
			$querystr = $querystr ." AND htown LIKE '%$ciudad%' ";
   		if ($sexo != '')
   			$querystr = $querystr ." AND sex = '$sexo' ";
		if ($edad != '')
   			$querystr = $querystr ." AND (DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(dob, '00-%m-%d'))) BETWEEN ". ($edad-10) ." AND $edad ";
   		if ($regcond != '')
   			$querystr = $querystr ." AND driverlic = '$regcond' ";
   		if ($idioma != '')
   			$querystr = $querystr ." AND language = '$idioma' ";

 		if ($profesion != '')
			$querystr = $querystr ." AND fieldofstudy LIKE '%$profesion%' ";
// 		if ($profesion != '')
//			$querystr = $querystr ." AND jobtitle LIKE '%$profesion%' ";

		$querystr = $querystr ." GROUP BY applicant.applicantid";

			echo "<!--  $querystr -->";

			$results=query($querystr,$conn);
			//check if data is returned
			echo "<table border=\"0\" width=\"100%\">";  		
			echo "<tr class=\"boldtext\"><td>Postulante</td><td>Sexo</td><td>Ciudad</td><td>Fecha Nac.</td><td>Registro</td><td>Profesion</td><td>Ver</td></tr>";
			while ($myjobs = fetch_object($results)){
				//alternate row colour
				$j++;
				if($j%2==1){
					echo "<tr id=\"row$j\">";
				}else{
					echo "<tr id=\"row$j\" class=\"odd\">";
				}	  
					echo "<td align=\"left\">$myjobs->applicant</td>
						<td align=\"left\">$myjobs->sex</td>
						<td align=\"left\">$myjobs->htown</td>						
						<td align=\"left\">$myjobs->dob</td>
						<td align=\"left\">$myjobs->driverlic</td>
						<td align=\"left\">$myjobs->fieldofstudy</td>
						<td align=\"left\"><a name=\"editexperience\" href=\"viewcv.php?applicant=$myjobs->applicantid\" target='_blank'><img src=\"images/button_view.png\" alt=\"Ver CV\" border=\"0\" width=\"16\" height=\"16\"/></a></td></tr>";
			}
			echo "</table>";
 	}
}
?>

</p>
</div>
</div>

<?php ShowLoginBox(); ?>

</div>

<?php ShowFooter(); ?>
