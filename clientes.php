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

<?php ShowHeader(WEBSITE_NAME ." :: Clientes"); ?>

<?php ShowDropMenu(); ?>

</div>

<div id="content" >

<div id="contentdiv">

<div id="searchtable" >
<img src="images/people.jpg" alt="people" width="575" height="100" border="0" />
</div>


<div id="list">

<h2 class="title-bar">Clientes</h2>
<p class="Lastnews" align="justify">Asistimos a compa&ntilde;&iacute;as de todas las industrias. Algunos de nuestros clientes son:</p>

<div id="servlist" class="servlist"><ul>
 <li>Mister Food</li>
 <li>Alta Marca</li>
 <li>Eme Estudio Imagen</li>
 <li>Competitive PM</li>
 <li>Administraci&oacute;n Palladino Pilar</li>
 <li>VZ Palmas del Pilar</li>
 <li>Vilmax Argentina</li>
 <li>Gu&iacute;as Blue</li>
 <li>Galer&iacute;a Jard&iacute;n</li>
</ul></div>

</div>
</div>

<?php ShowLoginBox(); ?>

</div>

<?php ShowFooter(); ?>
