<?php
session_start();

if ($_SESSION['rol'] != 1) {
    header("Location: ../views/index.php");
}
include_once '../../config/conexion.php';

if (!empty($_POST)) {
    if ($_POST['iduserD'] == 1) {
        header('Location: list_users.php');
        mysqli_close($conexion);
        exit;
    }
    $iduserD = $_POST['iduserD'];


    //$query_D = mysqli_query($conexion, "DELETE FROM usuario WHERE idusuario = $iduserD");
    $query_D = mysqli_query($conexion, "UPDATE usuario SET estatus = 0 WHERE idusuario = $iduserD");
    mysqli_close($conexion);
    if ($query_D) {
        header('Location: list_users.php');
    } else {
        echo 'Error al eliminar usuario';
    }
}

if (empty($_REQUEST['id']) || $_REQUEST['id'] == 1) {
    header('Location: list_users.php');
    mysqli_close($conexion);
} else {


    $iduserD = $_REQUEST['id'];

    $query = mysqli_query($conexion, "SELECT u.nombre, u.usuario, r.rol  FROM usuario u INNER JOIN rol r 
                            ON u.rol = r.idrol WHERE u.idusuario = $iduserD");
    mysqli_close($conexion);
    $result = mysqli_num_rows($query);

    if ($result > 0) {
        while ($data = mysqli_fetch_array($query)) {
            $nombre = $data['nombre'];
            $usuario = $data['usuario'];
            $rol = $data['rol'];
        }
    } else {
        header('Location: list_users.php');
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
                            <li class="breadcrumb-item active">Eliminar Usuario</li>
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
                                    <h3 class="card-title">Elimnar datos del Usuario</h3>
                                </div>
                                <div class="card-body text-center">
                                    <h4><strong>¿Esta seguro que desea eliminar el registro?</strong></h4>
                                    <p><b>Nombre:</b><span class="badge bg-dark"> <?php echo $nombre ?></span></p>
                                    <p><b>Usuario:</b><span class="badge bg-dark"> <?php echo $usuario ?></span></p>
                                    <p><b>Categoria:</b><span class="badge bg-dark"> <?php echo $rol ?></span></p>
                                </div>
                                <div class="card-footer">
                                    <div class="form-group row">
                                        <form action="" class="form-horizontal" method="POST">
                                            <input type="hidden" name="iduserD" value="<?php echo $iduserD ?>">
                                            <input type="submit" class="btn btn-block bg-danger" value="Aceptar"></input>
                                        </form>
                                        <div class="offset-sm-6 col-md-2 float-right">
                                            <button class="btn bg-secondary"><a href="list_users.php">Cancelar</a></button>
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