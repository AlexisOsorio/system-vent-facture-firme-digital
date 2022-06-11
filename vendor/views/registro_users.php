<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<?php
	include_once "../layouts/style.php"
	?>
	<title>Registro Usuarios</title>
</head>

<body>
	<?php
	include_once "../layouts/header.php"
	?>
	<section id="container">
		<div class="form-register">
            <h1>Registro de Usuarios</h1>
            <hr>
            <div class="alert"></div>
            <form action="">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo">
				<label for="correo">Correo Electrónico</label>
				<input type="email" name="correo" id="correo" placeholder="Correo Electrónico">
				<label for="usuario">Usuario:</label>
                <input type="text" name="usuario" id="usuario" placeholder="Ingrese el Usuario">
				<label for="pass">Contraseña:</label>
                <input type="password" name="pass" id="pass" placeholder="Ingrese su Contraseña">
				<label for="rol">Tipo de Usuario:</label>
				<select name="rol" id="rol">
					<option value="1">Administrador</option>
					<option value="2">Supervisor</option>
					<option value="3">Vendedor</option>
				</select>
				<input type="submit" value="Registrar Usuario" class="btn btn">
            </form>
        </div>
	</section>
	<?php
	include_once "../layouts/footer.php"
	?>
</body>

</html>