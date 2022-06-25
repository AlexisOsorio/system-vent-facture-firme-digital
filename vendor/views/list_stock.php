<?php
session_start();


include_once "../../config/conexion.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de Productos</title>

    <?php
    include_once "../layouts/style.php"
    ?>

    <style>
        .activar {
            color: white;
            border: 1px solid #17A2B8;
            background: #17A2B8;
            display: inline-block;
        }

        .activar a:hover {
            background: #17A2B8;
        }

        /*Lista Productos*/
        .img_producto img {
            width: 70px;
            height: auto;
            margin: auto;
        }

        .modal {
            position: fixed;
            width: 100%;
            height: 100%;
            background: rgb(0, 0, 0, 0.81);
            display: none;
        }

        .bodyModal {
            width: 100%;
            height: 100%;
            display: -webkit-inline-flex;
            display: -moz-inline-flex;
            display: -ms-inline-flex;
            display: -o-inline-flex;
            display: inline-flex;
            justify-content: center;
            align-items: center;
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
                        <h2><b>Lista Productos</b></h2>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../views/index.php">Inicio</a></li>
                            <li class="breadcrumb-item active">Lista Productos</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section>
            <div class="container">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12" style="padding-bottom: 5px;">
                            <div class="form-group row">
                                <form action="search_products.php" method="get" class=" col-sm-9 d-flex">
                                    <input class="form-control" type="text" name="busqueda" id="busqueda" placeholder="Buscar Producto">
                                    <button type="submit" class="btn btn-outline-info"><i class="nav-icon fas fa-search"></i></button>
                                </form>
                                <?php
                                if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
                                ?>
                                    <ul class="nav justify-content-end">
                                        <li class="nav-item">
                                            <a href="../views/registro_stock.php" class=" btn bg-primary">
                                                <i class="nav-icon fas fa-user-plus"></i>
                                                Nuevo Producto
                                            </a>
                                        </li>
                                    </ul>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-striped table-inverse">
                                <thead class="bg-info thead-inverse">
                                    <tr class="text-center">
                                        <th scope="col-sm-2">ID</th>
                                        <th scope="col-sm-2">DESCRIPCIÓN</th>
                                        <th scope="col-sm-2">
                                            <?php
                                            $query_proveedor = mysqli_query($conexion, "SELECT codproveedor, proveedor FROM proveedor  
                                            WHERE estatus = 1 ORDER BY proveedor ASC ");
                                            $result_proveedor = mysqli_num_rows($query_proveedor);
                                            ?>
                                            <div class="col-sm-auto">
                                                <select name="proveedor" id="search_proveedor" class="form-control" style="background-color: #17A2B8; border: #17A2B8; color: #fff; font-weight: bold;">
                                                    <option value="" selected>PROVEEDOR</option>
                                                    <?php
                                                    if ($result_proveedor > 0) {
                                                        while ($proveedor = mysqli_fetch_array($query_proveedor)) {
                                                    ?>
                                                            <option style=" background-color: #fff;color: #000;" value="<?php echo $proveedor['codproveedor']; ?>"><?php echo $proveedor['proveedor']; ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                        </th>
                                        <th scope="col-sm-2">PRECIO</th>
                                        <th scope="col-sm-2">STOCK</th>
                                        <th scope="col-sm-2">FECHA</th>
                                        <th scope="col-sm-2">FOTO</th>
                                        <?php
                                        if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
                                        ?>
                                            <th scope="col-sm-2">ACCIONES</th>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <?php

                                //paginador
                                $sql_reg =  mysqli_query($conexion, "SELECT COUNT(*) as registros_totales FROM producto WHERE estatus = 1");
                                $result_reg = mysqli_fetch_array($sql_reg);
                                $registros_totales = $result_reg['registros_totales'];

                                $pag_num = 5;

                                if (empty($_GET['pagina'])) {
                                    $pag =  1;
                                } else {
                                    $pag = $_GET['pagina'];
                                }

                                $desde_pg = ($pag - 1) * $pag_num;
                                $total_pg = ceil($registros_totales / $pag_num);

                                $query = mysqli_query($conexion, "SELECT p.codproducto, p.descripcion,pr.proveedor, p.precio, p.existencia,
                                                        p.date_add, p.foto FROM producto p INNER JOIN proveedor pr 
                                                        ON p.proveedor = pr.codproveedor WHERE p.estatus = 1 
                                                        ORDER BY p.codproducto DESC LIMIT $desde_pg,$pag_num ");
                                $result = mysqli_num_rows($query);
                                if ($result > 0) {
                                    while ($data = mysqli_fetch_array($query)) {
                                        $formato = 'Y-m-d H:i:s';
                                        $fecha = DateTime::createFromFormat($formato, $data['date_add']);

                                        if ($data['foto'] != 'imgproducto.png') {
                                            $foto = '../utils/img/uploads/' . $data['foto'];
                                        } else {
                                            $foto = '../utils/' . $data['foto'];
                                        }
                                ?>
                                        <tbody class="text-center">
                                            <tr class="row<?php echo $data['codproducto']; ?>">
                                                <th><?php echo $data['codproducto']; ?></th>
                                                <td><?php echo $data['descripcion']; ?></td>
                                                <td><?php echo $data['proveedor']; ?></td>
                                                <td class="celPrecio"><?php echo $data['precio']; ?></td>
                                                <td class="celStock"><?php echo $data['existencia']; ?></td>
                                                <td><?php echo $fecha->format('d-m-Y'); ?></td>
                                                <td class="img_producto"><img src="<?php echo $foto; ?>" alt="<?php echo $data['descripcion']; ?>"></td>
                                                <?php
                                                if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
                                                ?>
                                                    <td>
                                                        <a class="btn bg-success add_stock" stock="<?php echo $data["codproducto"]; ?>" href="#"><i class="nav-icon fas fa-plus"></i> </a>
                                                        <a class="btn bg-warning" href="editar_stock.php?id=<?php echo $data["codproducto"]; ?>"><i class="nav-icon fas fa-edit"></i> </a>
                                                        <a class="btn bg-danger del_stock" stock="<?php echo $data["codproducto"]; ?>" href="#"><i class="nav-icon fas fa-trash"></i> </a>
                                                    </td>
                                                <?php
                                                }
                                                ?>
                                            </tr>

                                        </tbody>
                                <?php
                                    }
                                }
                                ?>

                            </table>
                            <div>
                                <nav aria-label="...">
                                    <ul class="pagination justify-content-end">
                                        <?php
                                        if ($pag != 1) {
                                            # code...

                                        ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?pagina=<?php echo 1; ?>"><i class="nav-icon fas fa-backward-step"></i></a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="?pagina=<?php echo $pag - 1; ?>" aria-label="Previous">
                                                    <span aria-hidden="true"><i class="nav-icon fas fa-backward-fast"></i></span>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        for ($i = 1; $i <= $total_pg; $i++) {
                                            # code...
                                            if ($i == $pag) {
                                                echo '  <li class="page-link activar">' . $i . '</li>';
                                            } else {
                                                echo '  <li class="page-item">
                                                            <a class="page-link" href="?pagina=' . $i . '">' . $i . '</a>
                                                        </li>';
                                            }
                                        }
                                        if ($pag != $total_pg) {
                                            # code...

                                        ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?pagina=<?php echo $pag + 1; ?>" aria-label="Next">
                                                    <span aria-hidden="true"><i class="nav-icon fas fa-forward-fast"></i></span>
                                                </a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="?pagina=<?php echo $total_pg; ?>"><i class="nav-icon fas fa-forward-step"></i></a>
                                            </li>

                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </nav>
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