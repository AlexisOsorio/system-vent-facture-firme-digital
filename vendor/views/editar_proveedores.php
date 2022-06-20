<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("Location: ../views/index.php");
}
include_once "../../config/conexion.php";

if (!empty($_POST)) {
    $alerta = '';

    if (empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {
        $alerta = '<p class="msg_error">Todos los campos son obligatorios.</p>';
    } else {

        $idProveedor = $_POST['id'];
        $proveedor = $_POST['proveedor'];
        $contacto = $_POST['contacto'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];


        $query_update = mysqli_query($conexion, "UPDATE proveedor SET proveedor = '$proveedor', contacto = '$contacto' , 
            telefono = '$telefono', direccion = '$direccion' WHERE codproveedor = $idProveedor");

        if ($query_update) {
            $alerta = '<p class="msg_save">Proveedor editado correctamente.</p>';
        } else {
            $alerta = '<p class="msg_error">Erro al editar proveedor.</p>';
        }
    }
}

//recuperacion y muestra de datos
if (empty($_REQUEST['id'])) {
    header('Location: list_proveedor.php');
    mysqli_close($conexion);
}
$idProv = $_REQUEST['id'];

$sql = mysqli_query($conexion, "SELECT * FROM proveedor WHERE codproveedor = $idProv");
mysqli_close($conexion);
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('Location: list_proveedor.php');
    mysqli_close($conexion);
} else {
    //$option = '';
    while ($data = mysqli_fetch_array($sql)) {
        $idProv = $data['codproveedor'];
        $proveedor = $data['proveedor'];
        $contacto = $data['contacto'];
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
    <title>Editar Proveedores</title>

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
                        <h2><b>Actualizar Proveedor</b></h2>
                    </div>
                    <div class="col-sm-6">
                        <ul class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="./index.php">Inicio</a></li>
                            <li class="breadcrumb-item active">Editar Proveedor</li>
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
                                        <a class="btn btn-block bg-danger" href="../views/list_proveedor.php"> <i class="nav-icon fas fa-ban"></i> Regresar</a>
                                    </li>
                                </ul>

                            </div>
                            <div class="card card-primary ">
                                <div class="card-header">
                                    <h3 class="card-title text-center">Actualizar datos del Proveedor</h3>
                                </div>
                                <div class="card-body">
                                    <div class="alerta text-center"> <?php echo isset($alerta) ? $alerta : ''; ?></div>
                                    <form action="" class="form-horizontal" method="POST">
                                        <div class="form-group row">
                                            <div class="col-sm-10">
                                                <input type="hidden" name="id" class="form-control" value="<?php echo $idProv; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="proveedor" class="col-sm-2 col-form-label">Proveedor</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="proveedor" id="proveedor" placeholder="Nombre del Proveedor" class="form-control" value="<?php echo $proveedor; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="contacto" class="col-sm-2 col-form-label">Contacto</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="contacto" id="contacto" placeholder="Nombre completo del Contacto" class="form-control" value="<?php echo $contacto; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="telefono" class="col-sm-2 col-form-label">Teléfono</label>
                                            <div class="col-sm-10">
                                                <input type="number" name="telefono" id="telefono" placeholder="Celular del Proveedor" class="form-control" value="<?php echo $telefono; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="direccion" class="col-sm-2 col-form-label">Direccción</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="direccion" id="direccion" placeholder="Dirección del Proveedor" class="form-control" value="<?php echo $direccion; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10 float-right">
                                                <input type="submit" class="btn btn-block btn-outline-primary" value="Guardar Proveedor">
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