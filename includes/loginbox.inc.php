<div id="column-right" style="float:right; margin-top:-110px">
<h2 class="Box">Profesionales Registrados</h2>

<?php

if(isset($_SESSION["userid"])) {
	ShowUserMenu();
	
	if ($titulo) {
		echo '<h2 class="title-bar">'. $titulo .'</h2>';
		echo '<div id="navlist" class="navlist"><ul>';
		foreach ($links as $link) {
			echo "<li>$link</li>";
		}
		echo '</ul></div>';
	}
} else{
?>

<div class="BoxLogin">
<form action="login.php" method="post" enctype="multipart/form-data" name="login" id="login">
E-Mail:<br />
<input type="text" value="" name="username" id="username" size="28" />
<br />
Contrase&ntilde;a:<br />
<input type="password" value="" name="password" id="password" size="28" />
<input type="hidden" name="member" value="A" />
<input type="submit" name="submit" class="button" value="Login" />
</form>
<hr style="color:#f2f2f2;" noshade="noshade"/>
<a href="forgot.php">Recuperar clave</a>
</div>

<h2 class="title-bar">Profesionales no registrados</h2>
<div class="BoxLogin">
<p>Para ingresar su CV, haga <a href="register.php?member=A">click aqu&iacute;</a>.</p>
</div>

<?php } ?>

</div>
