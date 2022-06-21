<?php
session_start();
include_once "../../config/conexion.php";

if (!empty($_POST)) {
    $alerta = '';

    if (empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {
        $alerta = '<p class="msg_error">Todos los campos son obligatorios.</p>';
    } else {

        $idCliente = $_POST['id'];
        $ruc = $_POST['ruc'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];

        $result = 0;
        if (is_numeric($ruc) and $ruc != 0) {
            $query = mysqli_query($conexion, "SELECT * FROM cliente WHERE (ruc = '$ruc' AND idcliente != $idCliente)");
            $result = mysqli_fetch_array($query);
        }

        if ($result >= 1) {
            $alerta = '<p class="msg_error">La cedula ya existe.</p>';
        } else {
            if ($ruc == '') {
                $ruc = 0;
            }
            $query_update = mysqli_query($conexion, "UPDATE cliente SET ruc = $ruc, nombre = '$nombre' , 
            telefono = '$telefono', direccion = '$direccion' WHERE idcliente = $idCliente");

            if ($query_update) {
                $alerta = '<p class="msg_save">Cliente editado correctamente.</p>';
            } else {
                $alerta = '<p class="msg_error">Erro al editar cliente.</p>';
            }
        }
    }
}

//recuperacion y muestra de datos
if (empty($_REQUEST['id'])) {
    header('Location: list_clients.php');
    mysqli_close($conexion);
}
$idClient = $_GET['id'];

$sql = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idClient AND estatus = 1");
mysqli_close($conexion);
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('Location: list_clients.php');
    mysqli_close($conexion);
} else {
    //$option = '';
    while ($data = mysqli_fetch_array($sql)) {
        $idClient = $data['idcliente'];
        $ruc = $data['ruc'];
        $nombre = $data['nombre'];
        $telefono = $data['telefono'];
        $direccion = $data['direccion'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Clientes</title>

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
                        <h2><b>Actualizar Cliente</b></h2>
                    </div>
                    <div class="col-sm-6">
                        <ul class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="./index.php">Inicio</a></li>
                            <li class="breadcrumb-item active">Editar Cliente</li>
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
                                        <a class="btn btn-block bg-danger" href="../views/list_clients.php"> <i class="nav-icon fas fa-ban"></i> Regresar</a>
                                    </li>
                                </ul>

                            </div>
                            <div class="card card-primary ">
                                <div class="card-header">
                                    <h3 class="card-title text-center">Actualizar datos del Cliente</h3>
                                </div>
                                <div class="card-body">
                                    <div class="alerta text-center"> <?php echo isset($alerta) ? $alerta : ''; ?></div>
                                    <form action="" class="form-horizontal" method="POST">
                                        <div class="form-group row">
                                            <div class="col-sm-10">
                                                <input type="hidden" name="id" class="form-control" value="<?php echo $idClient; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="ruc" class="col-sm-2 col-form-label">Cedula</label>
                                            <div class="col-sm-10">
                                                <input type="number" name="ruc" id="ruc" placeholder="Ingrese la Cedula del Cliente" class="form-control" value="<?php echo $ruc; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo del Cliente" class="form-control" value="<?php echo $nombre; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="telefono" class="col-sm-2 col-form-label">Teléfono</label>
                                            <div class="col-sm-10">
                                                <input type="number" name="telefono" id="telefono" placeholder="Ingrese el Teléfono del Cliente" class="form-control" value="<?php echo $telefono; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="direccion" class="col-sm-2 col-form-label">Direccción</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="direccion" id="direccion" placeholder="Ingrese la Dirección del Cliente" class="form-control" value="<?php echo $direccion; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10 float-right">
                                                <input type="submit" class="btn btn-block btn-outline-primary" value="Guardar Cliente">
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