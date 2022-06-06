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
                <h2>Login System</h2>
                <div class="input-div usr">
                    <div class="i">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Usuario</h5>
                        <input type="text" name="usuario" placeholder="Ingese su usuario" class="input">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Contraseña</h5>
                        <input type="password" name="pass" placeholder="Ingrese su contraseña" class="input">
                    </div>
                </div>
                <p class="alert"></p>
                <input type="submit" class="btn" value="Iniciar Sesión">
            </form>
        </div>
    </div>
</body>
<script src="../js/login.js"></script>
</html>