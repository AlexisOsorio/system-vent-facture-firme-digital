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
    <link rel="stylesheet" href="utils/css/css/all.min.css">
    <link rel="stylesheet" href="utils/css/style.css">
</head>

<body>
    <img class="wave" src="utils/images/ola.png" alt="img1">
    <div class="contenedor">
        <div class="img">
            <img src="utils/images/fondo.svg" alt="">
        </div>
        <div class="contenido-login">
            <form action="" method="post">
                <img src="utils/images/user.png" alt="Usuario">
                <h2>Log In System</h2>
                <div class="input-div usr">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Usuario</h5>
                        <input type="text" name="usuario" placeholder="Ingese su usuario" class="input">
                    </div>
                </div>
                <div class="input-div pass">
                        <div class="i">
                            <i class="fas fa-lock"></i>
                            <!--colocamos el icono de contraseña-->
                        </div>
                        <div class="div">
                            <h5>Contraseña</h5>
                            <input type<input type="password" name="pass" placeholder="Ingrese su contraseña" class="input">
                        </div>
                    </div>
                    <input type="submit" class="btn" value="Iniciar Sesión">
            </form>
        </div>
    </div>
</body>

</html>