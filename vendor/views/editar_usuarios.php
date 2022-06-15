<?php
include_once "../../config/conexion.php";
if (!empty($_POST)) {
    $alerta = '';

    if (empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['rol'])) {
        $alerta = '<p class="msg_error">Todos los campos son obligatorios.</p>';
    } else {

        $idUsuario = $_POST['idUsuario'];
        $nombre = $_POST['nombre'];
        $email = $_POST['correo'];
        $user = $_POST['usuario'];
        $pass = md5($_POST['pass']);
        $rol = $_POST['rol'];

        $query = mysqli_query($conexion, "SELECT * FROM usuario 
                                    WHERE (usuario = '$user' AND idusuario != $idUsuario)
                                    OR (correo = '$email' AND idusuario != $idUsuario)");
        $result = mysqli_fetch_array($query);

        if ($result > 0) {
            $alerta = '<p class="msg_error">El correo o usuario ya existe.</p>';
        } else {

            if (empty($_POST['pass'])) {
                $query_update = mysqli_query($conexion, "UPDATE usuario SET nombre = '$nombre',
                correo = '$email', usuario = '$user', rol = '$rol' WHERE idusuario = $idUsuario ");
            } else {
                $query_update = mysqli_query($conexion, "UPDATE usuario SET nombre = '$nombre',
                correo = '$email', usuario = '$user', clave = '$pass', rol = '$rol' WHERE idusuario = $idUsuario ");
            }

            if ($query_update) {
                $alerta = '<p class="msg_save">Usuario editado correctamente.</p>';
            } else {
                $alerta = '<p class="msg_error">Erro al editar usuario.</p>';
            }
        }
    }
}

//recuperacion y muestra de datos
if (empty($_GET['id'])) {
    header('Location: list_users.php');
}

$idUser = $_GET['id'];

$sql = mysqli_query($conexion, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, (u.rol) as idrol, 
    (r.rol) as rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE idusuario = $idUser");
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('Location: list_users.php');
} else {
    while ($data = mysqli_fetch_array($sql)) {
        $idUser = $data['idusuario'];
        $nombre = $data['nombre'];
        $correo = $data['correo'];
        $usuario = $data['usuario'];
        $idrol = $data['idrol'];
        $rol = $data['rol'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Usuarios</title>

    <?php
    include_once "../layouts/style.php"
    ?>
    <style>
        .msg_error {
            color: #BD2130;
        }

        .msg_save {
            color: #28A745;
        }

        .alerta p {
            padding: 10px;
        }

        .notItemOne option:first-child {
            display: none;
        }
    </style>
 </head> 
<body class="hold-transition sidebar-mini">
    <?php
    include_once "../layouts/header.php"
    ?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h2><b>Actualizar Usuario</b></h2>
                    </div>
                    <div class="col-sm-6">
                        <ul class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="./index.php">Inicio</a></li>
                            <li class="breadcrumb-item active">Editar Usuarios</li>
                        </ul>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12" style="padding-bottom: 5px;">
                                <ul class="nav justify-content-end">
                                    <li class="nav-item">
                                        <a class="btn btn-block bg-danger" href="../views/list_users.php">Regresar</a>
                                    </li>
                                </ul>

                            </div>
                            <div class="card card-primary ">
                                <div class="card-header">
                                    <h3 class="card-title text-center">Actualizar datos del Usuario</h3>
                                </div>
                                <div class="card-body">
                                    <div class="alerta text-center"> <?php echo isset($alerta) ? $alerta : ''; ?></div>
                                    <form action="" class="form-horizontal" method="POST">
                                        <div class="form-group row">
                                            <div class="col-sm-10">
                                                <input type="hidden" name="idUsuario" class="form-control" value="<?php echo $idUser; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo" class="form-control" value="<?php echo $nombre; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="correo" class="col-sm-2 col-form-label">Correo Electrónico</label>
                                            <div class="col-sm-10">
                                                <input type="email" name="correo" id="correo" placeholder="Correo Electrónico" class="form-control" value="<?php echo $correo; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="usuario" class="col-sm-2 col-form-label">Usuario</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="usuario" id="usuario" placeholder="Ingrese el Usuario" class="form-control" value="<?php echo $usuario; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="pass" class="col-sm-2 col-form-label">Contraseña</label>
                                            <div class="col-sm-10">
                                                <input type="password" name="pass" id="pass" placeholder="Ingrese la Contraseña" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="rol" class="col-sm-2 col-form-label">Tipo de Usuario</label>
                                            <div class="col-sm-5">

                                                <select name="rol" id="rol" class="form-control notItemOne">
                                                    <option value="1" <?php
                                                                        if ($rol == 1) {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>Administrador</option>
                                                    <option value="2" <?php
                                                                        if ($rol == 2) {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>Supervisor</option>
                                                    <option value="3" <?php
                                                                        if ($rol == 3) {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>Vendedor</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10 float-right">
                                                <input type="submit" class="btn btn-block btn-outline-primary" value="Actualizar Usuario">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php
    include_once "../layouts/footer.php"
    ?>