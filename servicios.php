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

<?php ShowHeader(WEBSITE_NAME ." :: Servicios"); ?>

<?php ShowDropMenu(); ?>

</div>

<div id="content" >

<div id="contentdiv">

<div id="searchtable" >
<img src="images/people.jpg" alt="people" width="575" height="100" border="0" />
</div>


<div id="list">

<h2 class="title-bar">Servicios</h2>
<p class="Lastnews" align="justify"><B><?php echo WEBSITE_NAME; ?></B> es una empresa especializada en la b&uacute;squeda y selecci&oacute;n de personal. Contamos con amplias bases de datos propias y externas y un servicio profesional que incluye:</p>

<div id="servlist" class="servlist"><ul>
 <li>Psicot&eacute;cnicos para ingreso, ascenso y movimiento horizontal</li>
 <li>Evaluaci&oacute;n de Empleabilidad</li>
 <li>Descripci&oacute;n de Puestos </li>
 <li>Assessment Center</li>
 <li>Outplacement</li>
</ul></div>

</div>
</div>

<?php ShowLoginBox(); ?>

</div>

<?php ShowFooter(); ?>
