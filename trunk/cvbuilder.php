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
if(isset($_POST["submit"]) && $_POST["submit"]=='Logout') LogOut();
?>

<?php ShowHeader(WEBSITE_NAME ." :: Editar mi CV"); ?>

<?php ShowDropMenu(); ?>

</div>

<div id="content" >

<div id="contentdiv">

<div id="searchtable" >
<img src="images/people.jpg" alt="people" width="575" height="100" border="0" />
</div>


<div id="list">

<h2 class="title-bar">Curriculum Vitae</h2>
<p class="Lastnews">
Por favor compruebe que todos los campos <strong>obligatorios</strong> hayan sido completados. Usted podr&aacute; actualizar, modificar y eliminar los datos en cualquier ocasi&oacute;n que visite nuestro sitio.<br/><br/>
        <img src="images/check-r.gif" width="16" height="16">  Secciones completas. <br/>
        <img src="images/check-w.gif" width="16" height="16">  Secciones incompletas.
		
<table border="0">
    <tr>
      <td><a href="personaldata.php?search=<?php echo $_SESSION[userid]; ?>">Datos Personales</a> </td>
      <td><b>Obligatorio</b></td>
      <td><?php ShowCvStatus('applicant', 'hbox'); ?></td>
      
    </tr>
    <tr>
      <td><a href="careerobjective.php">Objetivos Laborales</a> </td>
      <td>Opcional</td>
      <td><?php ShowCvStatus('objective'); ?></td>
      
    </tr>
    <tr>
      <td><a href="qualsumm.php">Resumen de Aptitudes</a></td>
      <td>Opcional</td>
      <td><?php ShowCvStatus('applicant', 'qualsumm'); ?></td>
      
    </tr>
    <tr>
      <td><a href="profexp.php">Experiencia Profesional</a> </td>
      <td>Opcional</td>
      <td><?php ShowCvStatus('experience'); ?></td>
    </tr>
    <tr>
      <td><a href="education.php">Estudios</a></td>
      <td><b>Obligatorio</b></td>
      <td><?php ShowCvStatus('education'); ?></td>
    </tr>
    <tr>
      <td><a href="training.php">Cursos/Workshops</a></td>
      <td>Opcional</td>
      <td><?php ShowCvStatus('training'); ?></td>
    </tr>
    <tr>
      <td><a href="publications.php">Publicaciones</a></td>
      <td>Opcional</td>
      <td><?php ShowCvStatus('publication'); ?></td>
    </tr>
    <tr>
      <td><a href="profmem.php">Grupos y Asociaciones</a></td>
      <td>Opcional</td>
      <td><?php ShowCvStatus('professional'); ?></td>
    </tr>
    <tr>
      <td><a href="language.php">Idiomas</a> </td>
      <td><b>Obligatorio</b></td>
      <td><?php ShowCvStatus('language'); ?></td>
    </tr>
    <tr>
      <td><a href="informatica.php">Inform&aacute;tica</a> </td>
      <td>Opcional</td>
      <td><?php ShowCvStatus('informatica'); ?></td>
    </tr>
    <tr>
      <td><a href="reference.php">Referencias</a></td>
      <td>Opcional</td>
      <td><?php ShowCvStatus('referee'); ?></td>
    </tr>
    <tr>
      <td><a href="attach.php">Archivos Adjuntos</a></td>
      <td>Opcional</td>
      <td><?php ShowCvStatus('attachment'); ?></td>
    </tr>
</table>

</p>
</div>
</div>

<?php ShowLoginBox('Curriculum Vitae', leftmenu()); ?>

</div>

<?php ShowFooter(); ?>
