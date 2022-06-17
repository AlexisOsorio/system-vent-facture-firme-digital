<?php
session_start();

include_once "../../config/conexion.php";
if (!empty($_POST)) {
    $alerta = '';

    if (
        empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) ||
        empty($_POST['pass']) || empty($_POST['rol'])
    ) {
        $alerta = '<p class="msg_error">Todos los campos son obligatorios.</p>';
    } else {


        $nombre = $_POST['nombre'];
        $email = $_POST['correo'];
        $user = $_POST['usuario'];
        $pass = md5($_POST['pass']);
        $rol = $_POST['rol'];

        $query = mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario = '$user' OR correo = '$email'");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $alerta = '<p class="msg_error">El correo o usuario ya existe.</p>';
        } else {
            $query_insert = mysqli_query($conexion, "INSERT INTO usuario(nombre,correo, usuario, clave, rol) 
                VALUES ('$nombre','$email','$user','$pass','$rol')");

            if ($query_insert) {
                $alerta = '<p class="msg_save">Usuario guardado correctamente.</p>';
            } else {
                $alerta = '<p class="msg_error">Erro al guardar usuario.</p>';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrar Clientes</title>

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
                        <h2><b>Clientes</b></h2>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="./index.php">Inicio</a></li>
                            <li class="breadcrumb-item active">Registrar Clientes</li>
                        </ol>
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
                                        <a class="btn btn-block bg-danger" href="../views/list_clients.php">Regresar</a>
                                    </li>
                                </ul>

                            </div>
                            <div class="card card-success ">
                                <div class="card-header">
                                    <h3 class="card-title text-center">Registar Nuevo Cliente</h3>
                                </div>
                                <div class="card-body">
                                    <div class="alerta text-center"> <?php echo isset($alerta) ? $alerta : ''; ?></div>
                                    <form action="" class="form-horizontal" method="POST">
                                        <div class="form-group row">
                                            <label for="ruc" class="col-sm-2 col-form-label">RUC</label>
                                            <div class="col-sm-10">
                                                <input type="number" name="ruc" id="ruc" placeholder="Ingrese el RUC del Cliente" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo del Cliente" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="telefono" class="col-sm-2 col-form-label">Teléfono</label>
                                            <div class="col-sm-10">
                                                <input type="number" name="telefono" id="telefono" placeholder="Ingrese el Teléfono del Cliente" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="direcc" class="col-sm-2 col-form-label">Direccción</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="direcc" id="direcc" placeholder="Ingrese la Dirección del Cliente" class="form-control">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10 float-right">
                                                <input type="submit" class="btn btn-block btn-outline-success" value="Registrar Cliente">
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