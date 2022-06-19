<?php
session_start();

include_once "../../config/conexion.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de Clientes</title>

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
                    <div class="col-sm-6">
                        <h2><b>Lista Clientes</b></h2>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../views/index.php">Inicio</a></li>
                            <li class="breadcrumb-item active">Lista Clientes</li>
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
                                <form action="search_clients.php" method="get" class=" col-sm-9 d-flex">
                                    <input class="form-control" type="text" name="busqueda" id="busqueda" placeholder="Buscar Cliente">
                                    <button type="submit" class="btn btn-outline-info"><i class="nav-icon fas fa-search"></i></button>
                                </form>

                                <ul class="nav justify-content-end">
                                    <li class="nav-item">
                                        <a href="../views/registro_clients.php" class=" btn bg-primary">
                                            <i class="nav-icon fas fa-user-plus"></i>
                                            Nuevo Ciente
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-striped table-inverse">
                                <thead class="bg-info thead-inverse">
                                    <tr class="text-center">
                                        <th scope="col-sm-2">ID</th>
                                        <th scope="col-sm-2">CEDULA</th>
                                        <th scope="col-sm-2">NOMBRE</th>
                                        <th scope="col-sm-2">TELÉFONO</th>
                                        <th scope="col-sm-2">DIRECCIÓN</th>
                                        <th scope="col-sm-2">ACCIONES</th>
                                    </tr>
                                </thead>
                                <?php

                                //paginador
                                $sql_reg =  mysqli_query($conexion, "SELECT COUNT(*) as registros_totales FROM cliente WHERE estatus = 1");
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

                                $query = mysqli_query($conexion, "SELECT * FROM cliente 
                                    WHERE estatus = 1 ORDER BY idcliente ASC LIMIT $desde_pg,$pag_num ");
                                $result = mysqli_num_rows($query);
                                if ($result > 0) {
                                    while ($data = mysqli_fetch_array($query)) {
                                        if ($data['ruc'] == 0) {
                                            $ruc = 'C/F';
                                        } else {
                                            $ruc = $data['ruc'];
                                        }
                                ?>
                                        <tbody>
                                            <tr class="text-center">
                                                <th scope="row"><?php echo $data['idcliente']; ?></th>
                                                <td><?php echo $ruc; ?></td>
                                                <td><?php echo $data['nombre']; ?></td>
                                                <td><?php echo $data['telefono']; ?></td>
                                                <td><?php echo $data['direccion']; ?></td>
                                                <td>
                                                    <a href="editar_clients.php?id=<?php echo $data["idcliente"]; ?>" class="btn bg-warning"><i class="nav-icon fas fa-edit"></i> Editar Cliente</a>
                                                    <?php
                                                    if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
                                                        if ($data['idcliente'] != 1) {
                                                    ?>
                                                            <a href="delete_clients.php?id=<?php echo $data["idcliente"]; ?>" class="btn bg-danger"><i class="nav-icon fas fa-trash"></i> Eliminar Cliente</a>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
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
                                                <a class="page-link" href="?pagina=<?php echo 1; ?>">Inicio</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="?pagina=<?php echo $pag - 1; ?>" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
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
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="?pagina=<?php echo $total_pg; ?>">Fin</a>
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