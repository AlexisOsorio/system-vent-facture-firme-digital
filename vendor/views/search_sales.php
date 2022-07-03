<?php
session_start();

include_once "../../config/conexion.php";

$busqueda = '';
$fecha_de = '';
$fecha_a = '';

if (!empty($_REQUEST['busqueda'])) {
    if (is_numeric($_REQUEST['busqueda'])) {
        # code...
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de Facturas</title>

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
                    <div class="col-sm-3">
                        <h2><b>Lista de Facturas</b></h2>
                    </div>
                    <ul class="nav justify-content-end">
                        <li class="nav-item">
                            <a href="../views/new_sales.php" class=" btn bg-primary">
                                <i class="nav-icon fas fa-file-circle-plus"></i>
                                Nueva Venta
                            </a>
                        </li>
                    </ul>
                    <div class="col-sm-7">
                        <ul class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../views/index.php">Inicio</a></li>
                            <li class="breadcrumb-item active">Lista Facturas</li>
                        </ul>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section>
            <div class="container">
                <div class="container-fluid">
                    <div class="row">
                        <div class="offset-sm-9 col-sm-3 float-right" style="padding-bottom: 5px;">
                            <div class="form-group">
                                <form action="search_sales.php" method="get" class="d-flex">
                                    <input class="form-control" type="text" name="busqueda" id="busqueda" placeholder="N° Factura">
                                    <button type="submit" class="btn btn-outline-info"><i class="nav-icon fas fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <style>
                            .buscar_fecha {
                                padding-top: 3px;
                                padding-bottom: 3px;
                                display: flex;
                                justify-content: flex-start;
                                align-items: center;
                                border-radius: 10px;
                                margin: 10px auto;
                            }

                            .buscar_fecha label {
                                margin: 0 10px;
                            }

                            .buscar_fecha input {
                                width: auto;
                                border: 1px solid #ced4da;
                                border-radius: 0.25rem;
                                padding: 0.375rem 0.75rem;
                                width: 15%;
                                color: #495057;
                                background-color: white;
                            }

                            .tbn {
                                margin: 0 10px;
                                display: inline-block;
                                font-weight: 400;
                                text-align: center;
                                -webkit-user-select: none;
                                -moz-user-select: none;
                                -ms-user-select: none;
                                user-select: none;
                                border: 1px solid transparent;
                                padding: 0.375rem 0.75rem;
                                font-size: 1rem;
                                line-height: 1.5;
                                border-radius: 0.25rem;
                                transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
                            }

                            .div_acciones {
                                display: -webkit-flex;
                                display: -moz-flex;
                                display: -ms-flex;
                                display: -o-flex;
                                display: flex;
                                justify-content: center;
                            }

                            .totalfactura {
                                display: -webkit-flex;
                                display: -moz-flex;
                                display: -ms-flex;
                                display: -o-flex;
                                display: flex;
                                justify-content: space-around;
                            }

                            .pagada .anulada {
                                padding: 4px 15px;
                            }

                            .inactive {
                                background-color: #713545;
                                border: #713545;
                                color: #FFFFFF;
                                cursor: default;
                                transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
                            }
                        </style>
                        <div class="col-sm-12" style="padding-bottom: 7px;">
                            <form action="search_sales.php" method="GET" class="buscar_fecha">
                                <label for="fecha_de" class="col-form-control"> De: </label>
                                <input type="date" name="fecha_de" id="fecha_de" class="" required>
                                <label for="fecha_a" class="col-form-control"> A </label>
                                <input type="date" name="fecha_a" id="fecha_a" required>
                                <button class="tbn btn-info"><i class="nav-icon fas fa-search"></i></button>
                            </form>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-striped table-inverse">
                                <thead class="bg-info thead-inverse">
                                    <tr>
                                        <th>N°</th>
                                        <th>FECHA / HORAS</th>
                                        <th>CLIENTE</th>
                                        <th>VENDEDOR</th>
                                        <th>ESTADO</th>
                                        <th class="text-right">TOTAL FACTURA</th>
                                        <th class="text-center">ACCION</th>
                                    </tr>
                                </thead>
                                <?php

                                //paginador
                                $sql_reg =  mysqli_query($conexion, "SELECT COUNT(*) as registros_totales FROM factura WHERE estatus != 10");
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

                                $query = mysqli_query($conexion, "SELECT f.nofactura, f.fecha, f.totalfactura, f.codcliente, f.estatus,
                                                                         u.nombre as vendedor,
                                                                         cl.nombre as cliente
                                                                        FROM factura f
                                                                        INNER JOIN usuario u
                                                                        ON f.usuario = u.idusuario
                                                                        INNER JOIN cliente cl
                                                                        ON f.codcliente = cl.idcliente 
                                                                        WHERE f.estatus != 10 
                                                                        ORDER BY f.fecha DESC LIMIT $desde_pg,$pag_num ");
                                mysqli_close($conexion);
                                $result = mysqli_num_rows($query);
                                if ($result > 0) {
                                    while ($data = mysqli_fetch_array($query)) {
                                        if ($data['estatus'] == 1) {
                                            $estatus = '<span class="pagada badge badge-success">Pagada</span>';
                                        }
                                ?>
                                        <tbody>
                                            <tr id="row_<?php echo $data['nofactura']; ?>">
                                                <th scope="row"><?php echo $data['nofactura']; ?></th>
                                                <td><?php echo $data['fecha']; ?></td>
                                                <td><?php echo $data['cliente']; ?></td>
                                                <td><?php echo $data['vendedor']; ?></td>
                                                <td><?php echo $estatus; ?></td>
                                                <th class="text-center totalfactura"><span style="width: 10px;">$</span><?php echo $data['totalfactura']; ?></th>
                                                <td class="text-center">
                                                    <div class="div_acciones">
                                                        <div>
                                                            <button class="btn btn-primary view_factura" type="button" cl="<?php echo $data['codcliente']; ?>" f="<?php echo $data['nofactura']; ?>">
                                                                <i class="nav-icon fas fa-eye"></i>
                                                            </button>
                                                        </div>

                                                    </div>
                                                </td>
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