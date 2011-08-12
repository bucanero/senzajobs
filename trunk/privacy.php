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
?>

<?php ShowHeader(WEBSITE_NAME ." :: Pol&iacute;tica de Privacidad"); ?>

<?php ShowDropMenu(); ?>

</div>

<div id="content" >

<div id="contentdiv">

<div id="searchtable" >
<img src="images/people.jpg" alt="people" width="575" height="100" border="0" />
</div>


<div id="list">

<h2 class="title-bar">Pol&iacute;tica de Privacidad</h2>
<p class="Lastnews" align="justify"><B><?php echo WEBSITE_NAME; ?></B> utilizar&aacute; los datos solicitados en este formulario para evaluar su perfil laboral y su ajuste a la demanda de trabajo por parte de empresas relacionadas con <B><?php echo WEBSITE_NAME; ?></B>. Es necesario ingresar estos datos en forma completa y sin errores, de lo contrario <B><?php echo WEBSITE_NAME; ?></B> no podr&aacute; evaluar su perfil laboral. En caso que su perfil coincida con lo requerido por un cliente de <B><?php echo WEBSITE_NAME; ?></B> en ocasi&oacute;n de una b&uacute;squeda, &eacute;sta podr&aacute; entregar sus datos al cliente de <B><?php echo WEBSITE_NAME; ?></B>. El ingreso y aceptaci&oacute;n de sus datos implicar&aacute; su consentimiento a tal efecto.<BR /><BR />Usted podr&aacute; ejercer los derechos de acceso, rectificaci&oacute;n y supresi&oacute;n de sus datos ingresando a la base de datos con su n&uacute;mero de documento y contrase&ntilde;a o dirigiendo un correo electr&oacute;nico a <a href="mailto:<?php echo WEBSITE_EMAIL; ?>"><?php echo WEBSITE_EMAIL; ?></a> identific&aacute;ndose con su nombre y n&uacute;mero de documento. <BR /><BR />La base de datos que contiene sus datos personales es responsabilidad de <B><?php echo WEBSITE_NAME; ?></B>.<BR><BR><BR><BR><BR><IMG SRC="images/logo_DNPDP.gif" width="198" height="33" alt="DNPDP" border="0"><BR><BR>"El titular de los datos personales tiene la facultad de ejercer el derecho de acceso a los mismo en forma gratuita a intervalos no inferiores a seis meses, salvo que se acredite un inter&eacute;s leg&iacute;timo al efecto, conforme lo establecido en el art&iacute;culo 14, inciso 3 de la Ley nro. 25.326".<BR><BR>"La DIRECCI&Oacute;N NACIONAL DE PROTECCI&Oacute;N DE DATOS PERSONALES, &Oacute;rgano de Control de la Ley nro. 25.326, tiene la atribuci&oacute;n de atender las denuncias y reclamos que se interpongan con relaci&oacute;n al incumplimiento de las normas sobre protecci&oacute;n de datos personales".</p>
</div>
</div>

<?php ShowLoginBox(); ?>

</div>

<?php ShowFooter(); ?>
