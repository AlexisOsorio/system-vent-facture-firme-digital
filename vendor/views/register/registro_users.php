<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<?php
	include_once "layouts/style.php"
	?>
	<title>Registro Usuario</title>
</head>

<body>
	<?php
	include_once "layouts/header.php"
	?>
	<section id="container">
		<div class="form-register">
            <h1>Registro de Usuarios</h1>
            <hr>
            <div class="alert"></div>
            <form action="">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo">
            </form>
        </div>
	</section>
	<?php
	include_once "layouts/footer.php"
	?>
</body>

</html>