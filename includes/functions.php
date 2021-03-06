<?php
/*****************************************************************************
/*Copyright (C) 2011 Damian Parrino [ dparrino@gmail.com ]
/*****************************************************************************

Basado en:
	* Taifa Jobs >> http://sourceforge.net/projects/taifajobs/
	* Job Finder >> http://sourceforge.net/projects/jobfinder/

/*****************************************************************************
Here we have functions that are called from other pages and used in a number
of sections within the site
Function listing:
1. populate_select
2. Lookup
3. SignedIn
4. LogOut
5. headericon
7. leftmenu
10. GetUser
11. navigationbottom
12. navigationbottom
13. footer
15. num_format
16. dateconvert
17. GetFieldsValue
18. sendemail
19. vacancies
20. AddSuccess
21. StatusComplete
/*****************************************************************************/
error_reporting(E_ALL & ~E_NOTICE);
include_once ("queryfunctions.php");

//same as populate_select but has option of passing a sql statement
function Lookup($fields_id='',$fields_value='',$selected,$sql){
	$conn=db_connect();
	$results=query($sql,$conn);
	while ($row = fetch_object($results)){
		$SelectedField=($row->$fields_id==$selected) ? " selected" : "";		
		echo '<option value="'. $row->$fields_id .'"'. $SelectedField .">". $row->$fields_value ."</option>\n";
	}
	free_result($results);
}

function populate_select($table,$fields_id,$fields_value,$selected){
	$sql="SELECT $fields_id,$fields_value FROM $table ORDER BY $fields_value";
	Lookup($fields_id, $fields_value, $selected, $sql);
}

function populate_year_select($starty, $endy, $sely) {
  	for($payyear=$starty; $payyear >= $endy; $payyear--){
		echo "<option value=\"$payyear\"";
		if($sely == $payyear)
			echo " selected";
		echo ">$payyear</option>\n";
	}
}

function populate_month_select($selm) {
	$meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
					"Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	for ($i=1; $i < 13; $i++) {
		echo "<option value=\"$i\"";
		if($selm == $i)
			echo " selected";
		echo ">$meses[$i]</option>\n";
	}
}

function SignedIn() {
    if (!isset($_SESSION["userid"]))
		die("<center><font color=red>You have not yet Logged in.<a href=\"index.php\">Please click here to log in.</a></font><center>");
}

function isAdmin() {
	return ($_SESSION["admin"] == 1);
}

function isEmployer(){
	return ($_SESSION["usercategory"] == 'E');
}

function isApplicant(){
	return ($_SESSION["usercategory"] == 'A');
}

function SignedInAdmin() {
	SignedIn();
	if(!isAdmin()) {
		header('HTTP/1.0 403 Forbidden');
		exit();
	}
}

function SignedInUser() {
	SignedIn();
	if(!isApplicant()) {
		header('HTTP/1.0 403 Forbidden');
		exit();
	}
}

function SignedInEmployer() {
	SignedIn();
	if (!isEmployer()) {
		header('HTTP/1.0 403 Forbidden');
		exit();
	}
}

function LogOut() {
	$_SESSION = array();
	session_destroy();
	header("Location: index.php");
}

function headericon(){
	echo '<link rel="shortcut icon" href="favicon.ico" />';
	echo '<link rel="icon" href="favicon.ico" />';
}

function ShowHeader($pagetitle) {
	require('./includes/header.inc.php');
}

function ShowFooter() {
	require('./includes/footer.inc.php');
}

function ShowLoginBox($titulo = '', $links = '') {
	require ("./includes/loginbox.inc.php");
}
function ShowDropMenu() {
	echo '<div align="right" style="margin-top:-48px;" ><ul>';
	echo '<li class="last"><b><a href="javascript:void();" onmouseover="showdiv(\'dropdown\')" onmouseout="mclosetime()">Mi Perfil</a></b></li>';
	echo '<div align="left" id="dropdown" onmouseover="cancelclose()"  onmouseout="mclosetime()" >';
	$arr = GetUserOptions();
	foreach ($arr as $link) {
		echo $link;
	}
	echo '</div></ul></div>';
}

function ShowUserMenu() {
	echo '<div id="navlist" class="navlist"><ul>';
	echo '<li>Bienvenido, <b>'. $_SESSION["user"] .'</b></li>';
	$arr = GetUserOptions();
	foreach ($arr as $link) {
		echo "<li>$link</li>\n";
	}
//<li class="last"><a href="jslogout.php">Logout</a></li>
	echo '</ul></div>';
}

function GetUserOptions(){
  $linkarr = array();
  $i = 0;
  if(isset($_SESSION["userid"])){
    if ($_SESSION["usercategory"]=='A'){
	 $linkarr[$i++] = "<a href=\"myjobs.php\">Mis B&uacute;squedas</a>";
	 $linkarr[$i++] = "<a href=\"cvbuilder.php\">Editar mi CV</a>";
    }
	
	if ($_SESSION["usercategory"]=='E'){
 	 $linkarr[$i++] = "<a href=\"employer.php\">Mis Datos</a>";
	 $linkarr[$i++] = "<a href=\"jobs.php\">B&uacute;squedas Laborales</a>";
	 $linkarr[$i++] = "<a href=\"applicants.php\">Mis Postulantes</a>";
    }

	if (($_SESSION["usercategory"]=='D') || ($_SESSION["admin"]==1)) {
	 $linkarr[$i++] = "<a href=\"users.php\">Administraci&oacute;n</a>";
    }

    $linkarr[$i++] = "<a href=\"account.php\">Mi Cuenta</a>";
	$linkarr[$i++] = "<a href=\"login.php?submit=Logout\">Logout</a>";
  } else {
  	$linkarr[$i++] = "<a href=\"register.php?member=A\">Registrarse</a>";
  }
  return $linkarr;
}

function leftmenu(){
    $linkarr = array();
    $i = 0;
	if(isset($_SESSION["userid"])){
		switch ($_SESSION["usercategory"]){
		case 'A':
		 	//if applicant is logged in display this menu
		    $linkarr[$i++] = "<a href=\"personaldata.php\">Datos Personales</a>";
			$linkarr[$i++] = "<a href=\"careerobjective.php\">Objetivos Laborales</a>";
			$linkarr[$i++] = "<a href=\"qualsumm.php\">Resumen de Aptitudes</a>";
			$linkarr[$i++] = "<a href=\"profexp.php\">Experiencia Profesional</a>";
			$linkarr[$i++] = "<a href=\"education.php\">Estudios</a>";
			$linkarr[$i++] = "<a href=\"training.php\">Cursos/Workshops</a>";
			$linkarr[$i++] = "<a href=\"publications.php\">Publicaciones</a>";
			$linkarr[$i++] = "<a href=\"profmem.php\">Grupos y Asociaciones</a>";
			$linkarr[$i++] = "<a href=\"language.php\">Idiomas</a>";
			$linkarr[$i++] = "<a href=\"informatica.php\">Inform&aacute;tica</a>";
			$linkarr[$i++] = "<a href=\"reference.php\">Referencias</a>";
			$linkarr[$i++] = "<a href=\"attach.php\">Archivos Adjuntos</a>";
			$linkarr[$i++] = "<a href=\"viewcv.php\" target=\"_blank\">Visualizar CV</a>";	
			break;
		case 'E':
			//if employer is logged in display this menu
			$linkarr[$i++] = "<a href=\"employer.php?search=$_SESSION[userid]\">Employers Data</a>";
			$linkarr[$i++] = "<a href=\"jobs.php\">Post jobs</a>";
			$linkarr[$i++] = "<a href=\"applicants.php\">Applicants</a>";
			break;
		case 'D':
			$linkarr[$i++] = "<a href=\"users.php\">Administraci&oacute;n</a>";
			break;
		}
		if ($_SESSION["admin"]==1)
			$linkarr[$i++] = "<a href=\"users.php\">Administraci&oacute;n</a>";
	}	
	return $linkarr;
}

function footer(){
	echo "Copyright &copy; ". date("Y");
}

function StatusComplete($table, $column, $cvid){
	$sql = "SELECT id FROM $table WHERE applicantid = $cvid";
	if (!empty($column))
		$sql = $sql ." AND $column IS NOT NULL";
	return (dbRowExists($sql));
}

function ShowCvStatus($table, $column = '') {
	if(StatusComplete($table, $column, $_SESSION["userid"]))
		echo '<img src="images/check-r.gif" alt="Ok" width="16" height="16" />';
	else
		echo '<img src="images/check-w.gif" alt="Error" width="16" height="16" />';
}

function num_format($number, $digits) {
  return str_replace(",","",number_format($number,$digits));
}

function AddSuccessBox($msg) {
	$rv = "<p class=\"box_success\" align=\"center\">$msg</p>";
	return $rv;
}

function AddErrorBox($msg) {
	$rv = "<p class=\"box_alert\" align=\"center\">$msg</p>";
	return $rv;
}

function AddInformationBox($msg) {
	$rv = "<p class=\"box_info\" align=\"center\">$msg</p>";
	return $rv;
}

function AddWarningBox($msg) {
	$rv = "<p class=\"box_warning\" align=\"center\">$msg</p>";
	return $rv;
}

function GetResultMsg($results,&$conn,$msg){
	$rv = "";
	if ((int) $results==0){
		//should log mysql errors to a file instead of displaying them to the user
		$rv = AddErrorBox($msg[0]) ."\n<!--\n Invalid query: ". mysql_errno($conn) ."\n ". mysql_error($conn) ."\n -->\n";
	} else {
		$rv = AddSuccessBox($msg[1]);
	}
	return $rv;
}

function dateconvert($date,$func){
  if ($func == 1){ //insert conversion
    list($day, $month, $year) = split('[/.-]', $date);
    $year=trim($year);
	$date = "$year-$month-$day";
    return $date;
  }
  if ($func == 2){ //output conversion
    list($year, $month, $day) = split('[-.]', $date);
    if(trim($date)!=='') $date = "$day/$month/$year";
    return $date;
  }
}

function UserExists($usrname) {
	$sql="SELECT loginname FROM users WHERE loginname='$usrname'";
	return (dbRowExists($sql));
}

function EmailExists($email) {
	$sql="SELECT email FROM users WHERE email='$email'";
	return (dbRowExists($sql));
}

function isEmployerAllowedView($empid, $appid) {
	$sql="SELECT a.id FROM applications a, job j, employer e 
			WHERE 	applicantid = $appid 
				AND a.jobid = j.jobid
				AND j.employerid = e.employerid
				AND e.employerid = $empid";
	return (dbRowExists($sql));
}

//sendmail
function sendemail($commentinfo,$support_email='',$bcc='',$notify_owner_email,$subject)
{
	$emailm = stripslashes($commentinfo);
	$headers = 'From: "'. WEBSITE_NAME ."\" <$support_email>\n"; 
	$headers .= "Return-Path: <$support_email>\n"; 
	$headers .= "X-Sender: <$support_email>\n"; 
	$headers .= "X-Mailer: SenzaJobs Site\n"; //mailer 
	$headers .= "X-Mailer-URL: ". WEBSITE_URL ."\n";
	$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Medium 
	if (!empty($bcc))
		$headers .= "Bcc: $bcc\r\n";
	return mail($notify_owner_email,$subject,$emailm,$headers);
}

?>
