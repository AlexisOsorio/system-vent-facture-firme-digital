<?php
session_start();

include_once '../../config/conexion.php';

if (!empty($_POST)) {
    if ($_POST['idclientD']) {
        header('Location: list_clients.php');
        mysqli_close($conexion);
        exit;
    }
    $idclientD = $_POST['idclientD'];


    //$query_D = mysqli_query($conexion, "DELETE FROM usuario WHERE idusuario = $iduserD");
    $query_D = mysqli_query($conexion, "UPDATE cliente SET estatus = 0 WHERE idcliente = $idclientD");
    mysqli_close($conexion);
    if ($query_D) {
        header('Location: list_clients.php');
    } else {
        echo 'Error al eliminar Cliente';
    }
}

if (empty($_REQUEST['id'])) {
    header('Location: list_clients.php');
    mysqli_close($conexion);
} else {


    $idclientD = $_REQUEST['id'];

    $query = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idclientD");
    mysqli_close($conexion);
    $result = mysqli_num_rows($query);

    if ($result > 0) {
        while ($data = mysqli_fetch_array($query)) {
            $ruc = $data['ruc'];
            $nombre = $data['nombre'];
        }
    } else {
        header('Location: list_clients.php');
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Eliminar Usuario</title>

    <?php
    include_once "../layouts/style.php"
    ?>
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

                    </div>
                    <div class="col-sm-6">
                        <ul class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../views/index.php">Inicio</a></li>
                            <li class="breadcrumb-item active">Eliminar Cliente</li>
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
                        <div class="col-md-4">

                        </div>
                        <div class="col-md-4">
                            <div class="card card-danger">
                                <div class="card-header">
                                    <h3 class="card-title">Elimnar datos del Cliente</h3>
                                </div>
                                <div class="card-body text-center">
                                    <h4><strong>¿Esta seguro que desea eliminar el registro?</strong></h4>
                                    <p><b>Cedula:</b><span class="badge bg-dark"> <?php echo $ruc ?></span></p>
                                    <p><b>Nombre:</b><span class="badge bg-dark"> <?php echo $nombre ?></span></p>
                                </div>
                                <div class="card-footer">
                                    <div class="form-group row">
                                        <form action="" class="form-horizontal" method="POST">
                                            <input type="hidden" name="idclientD" value="<?php echo $idclientD ?>">
                                            <button type="submit" class="btn btn-block bg-danger "><i class="nav-icon fas fa-trash"></i> Eliminar</button>
                                        </form>
                                        <div class="offset-sm-5 col-md-2 float-right">
                                            <a href="list_clientstrash.php" class="btn bg-secondary">Cancelar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">

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