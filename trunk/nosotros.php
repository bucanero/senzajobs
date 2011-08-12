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

<?php ShowHeader(WEBSITE_NAME ." :: Nosotros"); ?>

<?php ShowDropMenu(); ?>

</div>

<div id="content" >

<div id="contentdiv">

<div id="searchtable" >
<img src="images/people.jpg" alt="people" width="575" height="100" border="0" />
</div>


<div id="list">

<h2 class="title-bar">Nosotros</h2>
<p class="Lastnews" align="justify"><B><?php echo WEBSITE_NAME; ?></B> es una consultora especializada en el &aacute;rea de recursos humanos, fundada en s&oacute;lidos cimientos de experiencia y formaci&oacute;n.<br/>
                    Buscamos otorgarle a cada cliente la mejor soluci&oacute;n, para que cada sector pueda cumplir sus objetivos, aprovechando al m&aacute;ximo el capital humano.<br/>
                    Nuestra misi&oacute;n es satisfacer al cliente externo e interno, brind&aacute;ndoles servicios, seguridad y garant&iacute;as.</p>
</div>
</div>

<?php ShowLoginBox(); ?>

</div>

<?php ShowFooter(); ?>
