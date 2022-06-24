<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("Location: ../views/index.php");
}

include_once "../../config/conexion.php";
if (!empty($_POST)) {
    $alerta = '';

    if (
        empty($_POST['descripcion']) || empty($_POST['proveedor']) || empty($_POST['precio']) || $_POST['precio'] <= 0
        || empty($_POST['stock']) || $_POST['stock'] <= 0
    ) {
        $alerta = '<p class="msg_error">Todos los campos son obligatorios.</p>';
    } else {

        $descripcion = $_POST['descripcion'];
        $proveedor = $_POST['proveedor'];
        $precio = $_POST['precio'];
        $existencia = $_POST['stock'];
        $usuario_id = $_SESSION['idUser'];

        $foto = $_FILES['foto'];
        $nombre_foto = $foto['name'];
        $type = $foto['type'];
        $url_tmp = $foto['tmp_name'];

        $imgProd = 'imgproducto.png';

        if ($nombre_foto != '') {
            $destino = '../utils/img/uploads/';
            $img_nombre = 'img_' . md5(date('d-m-Y H:m:s'));
            $imgProd = $img_nombre . '.jpg';
            $src = $destino . $imgProd;
        }

        $query = mysqli_query($conexion, "SELECT * FROM producto WHERE descripcion = '$descripcion'");
        $result = mysqli_fetch_array($query);

        if ($result > 0) {
            $alerta = '<p class="msg_error">La descripcion del producto ya existe.</p>';
        } else {

            $query_insert = mysqli_query($conexion, "INSERT INTO producto(descripcion,proveedor,precio,existencia,usuario_id,foto) 
            values ('$descripcion','$proveedor', '$precio', '$existencia', '$usuario_id', '$imgProd')");

            if ($query_insert) {
                if ($nombre_foto != '') {
                    move_uploaded_file($url_tmp, $src);
                }
                $alerta = '<p class="msg_save">Producto guardado correctamente.</p>';
            } else {
                $alerta = '<p class="msg_error">Erro al guardar el producto.</p>';
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
    <title>Registrar Productos</title>

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

        /*=========================CSS=====================*/
        .prevPhoto {
            display: flex;
            justify-content: space-between;
            width: 160px;
            height: 150px;
            border: 1px solid #CCC;
            position: relative;
            cursor: pointer;
            background: url(../utils/img/);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
            margin: auto;
        }

        .prevPhoto label {
            cursor: pointer;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 2;
        }

        .prevPhoto img {
            width: 100%;
            height: 100%;
        }

        .upimg,
        .notBlock {
            display: none !important;
        }

        .errorArchivo {
            font-size: 16px;
            font-family: arial;
            color: #cc0000;
            text-align: center;
            font-weight: bold;
            margin-top: 10px;
        }

        .delPhoto {
            color: #FFF;
            display: -webkit-flex;
            display: -moz-flex;
            display: -ms-flex;
            display: -o-flex;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            background: red;
            position: absolute;
            right: -10px;
            top: -10px;
            z-index: 10;
        }
        .imgProductoDelete {
            width: 175px;
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
                        <h2><b>Productos</b></h2>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="./index.php">Inicio</a></li>
                            <li class="breadcrumb-item active">Registrar Productos</li>
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
                                        <a class="btn btn-block bg-danger" href="../views/list_stock.php"><i class="nav-icon fas fa-circle-arrow-left"></i> Regresar</a>
                                    </li>
                                </ul>

                            </div>
                            <div class="card card-success ">
                                <div class="card-header">
                                    <h3 class="card-title text-center">Registar Nuevo Producto</h3>
                                </div>
                                <div class="card-body">
                                    <div class="alerta text-center"> <?php echo isset($alerta) ? $alerta : ''; ?></div>
                                    <form action="" class="form-horizontal" method="POST" enctype="multipart/form-data">
                                        <div class="form-group row">
                                            <label for="descripcion" class="col-sm-2 col-form-label">Descripcion</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="descripcion" class="form-control" placeholder="Descripcion de Producto" id="descripcion">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="proveedor" class="col-sm-2 col-form-label">Proveedor</label>
                                            <?php
                                            $query_proveedor = mysqli_query($conexion, "SELECT codproveedor, proveedor FROM proveedor  
                                            WHERE estatus = 1 ORDER BY proveedor ASC ");
                                            $result_proveedor = mysqli_num_rows($query_proveedor);
                                            mysqli_close($conexion);
                                            ?>
                                            <div class="col-sm-6">
                                                <select name="proveedor" id="proveedor" class="form-control">
                                                    <?php
                                                    if ($result_proveedor > 0) {
                                                        while ($proveedor = mysqli_fetch_array($query_proveedor)) {
                                                    ?>
                                                            <option value="<?php echo $proveedor['codproveedor']; ?>"><?php echo $proveedor['proveedor']; ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="precio" class="col-sm-2 col-form-label">Precio</label>
                                            <div class="col-sm-10">
                                                <input type="number" name="precio" id="precio" placeholder="Precio del Producto" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="stock" class="col-sm-2 col-form-label">Stock</label>
                                            <div class="col-sm-10">
                                                <input type="number" name="stock" id="stock" placeholder="Cantidad de Productos" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="foto" class="col-sm-2 col-form-label">Foto</label>
                                            <div class="col-sm-5">
                                                <div class="prevPhoto">
                                                    <span class="delPhoto notBlock">X</span>
                                                    <label for="foto"></label>
                                                </div>
                                                <div class="upimg">
                                                    <input type="file" name="foto" id="foto">
                                                </div>
                                                <div id="form_alert"></div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10 float-right">
                                                <input type="submit" class="btn btn-block btn-outline-success" value="Agregar Nuevo Producto">
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