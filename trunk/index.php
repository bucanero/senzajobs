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

<?php ShowHeader(WEBSITE_NAME); ?>

<?php ShowDropMenu(); ?>

</div>

<div id="content" >

<div id="contentdiv">

<div id="searchtable" >
<img src="images/people.jpg" alt="people" width="575" height="100" border="0" />
</div>

<div id="list">

<h2 class="title-bar">Bienvenido a <?php echo WEBSITE_NAME; ?></h2>
<p class="Lastnews" align="justify">Atendiendo a la necesidad de las Empresas de conformar equipos de trabajo calificados, con personal id&oacute;neo y cuidadosamente seleccionado, y tambi&eacute;n a los profesionales con inquietud de crecimiento laboral e inter&eacute;s en acceder a compa&ntilde;&iacute;as responsables y en crecimiento,  <b><?php echo WEBSITE_NAME; ?></b> consolida el nexo imprescindible en ese encuentro.
<br/><br/>
Con un dedicado staff de especialistas en la b&uacute;squeda, evaluaci&oacute;n y selecci&oacute;n de recursos humanos, <b><?php echo WEBSITE_NAME; ?></b> ofrece a las empresas un servicio de excelencia a costos competitivos y con garant&iacute;a de satisfacci&oacute;n. Y,  a los postulantes, una  atenci&oacute;n personalizada y orientaci&oacute;n precisa para canalizar sus competencias y experiencia en la direcci&oacute;n exacta. 
<br/><br/>
<b><?php echo WEBSITE_NAME; ?></b><br/>
Callao 123456<br/>
Buenos Aires  -  Argentina
<br/><br/>
<i>Tel:</i> 011-1234-5678 <BR />
<i>E-mail:</i> <a href="mailto:<?php echo WEBSITE_EMAIL; ?>"><?php echo WEBSITE_EMAIL; ?></a><br/>
<i>Contacto:</i> <?php echo WEBSITE_CONTACT; ?>
</p>
</div>
</div>

<?php ShowLoginBox(); ?>

</div>

<?php ShowFooter(); ?>
