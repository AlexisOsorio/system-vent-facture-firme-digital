<?php
$alert = '';
session_start();
if (!empty($_SESSION['active'])) {
    header('location: vendor/views');
} else {
    if (!empty($_POST)) {
        if (empty($_POST['usuario']) || empty($_POST['pass'])) {
            $alert = 'Ingrese su usuario y su clave';
        } else {
            require_once "config/conexion.php";
            $user = mysqli_real_escape_string($conexion, $_POST['usuario']);
            $clave = md5(mysqli_real_escape_string($conexion, $_POST['pass']));
            $query = mysqli_query($conexion, "SELECT u.idusuario, u.nombre, u.correo,u.usuario,r.idrol,r.rol 
                                            FROM usuario u 
                                            INNER JOIN rol r 
                                            ON u.rol = r.idrol 
                                            WHERE u.correo = '$user' AND u.clave = '$clave' AND estatus = 1");
            mysqli_close($conexion);
            $resultado = mysqli_num_rows($query);
            if ($resultado > 0) {
                $dato = mysqli_fetch_array($query);
                $_SESSION['active'] = true;
                $_SESSION['idUser'] = $dato['idusuario'];
                $_SESSION['nombre'] = $dato['nombre'];
                $_SESSION['email'] = $dato['correo'];
                $_SESSION['user'] = $dato['usuario'];
                $_SESSION['rol'] = $dato['idrol'];
                $_SESSION['rol_name'] = $dato['rol'];
                header('location: vendor/views');
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
              Usuario o Contraseña Incorrecta
            </div>';
                session_destroy();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | System Billing</title>
    <!--Fuente-->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
    <!--Estilos CSS-->
    <link rel="stylesheet" type="text/css" href="utils/css/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="utils/css/styles.css">
    <!--Icono del Sistema-->
<link rel="icon" href="utils/images/uploads/user.png">
    <script src="https://kit.fontawesome.com/258935b168.js" crossorigin="anonymous"></script>
</head>

<body>
    <img class="wave" src="utils/images/ola.png" alt="img1">
    <div class="contenedor">
        <div class="img">

        </div>
        <div class="contenido-login">
            <form action="" method="post">
                <img src="utils/images/user.png" alt="Usuario">
                <h2>RPEIV System</h2>
                <div class="input-div usr">
                    <div class="i">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Usuario</h5>
                        <input type="email" name="usuario" class="input">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Contraseña</h5>
                        <input type="password" name="pass" class="input">
                    </div>
                </div>
                <div class="alert"><?php echo isset($alert) ? $alert : '' ?></div>
                <input type="submit" class="btn" value="Iniciar Sesión">
            </form>
        </div>
    </div>
</body>
<script src="utils/js/login.js"></script>

</html>