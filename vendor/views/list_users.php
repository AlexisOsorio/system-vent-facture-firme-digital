<?php
include_once "../../config/conexion.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de usuarios</title>

    <?php
    include_once "../layouts/style.php"
    ?>

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
                        <h2><b>Lista Usuarios</b></h2>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../views/index.php">Inicio</a></li>
                            <li class="breadcrumb-item active">Lista Usuarios</li>
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
                            <ul class="nav justify-content-end">
                                <li class="nav-item">
                                    <a href="../views/registro_users.php" class=" btn bg-primary">Crear Usuario</a>
                                </li>
                            </ul>

                        </div>
                        <div class="col-md-12">
                            
                            <table class="table table-striped table-inverse">
                                <thead class="bg-info thead-inverse">
                                    <tr class="text-center">
                                        <th scope="col-sm-2">ID</th>
                                        <th scope="col-sm-2">NOMBRE</th>
                                        <th scope="col-sm-2">CORREO</th>
                                        <th scope="col-sm-2">ROL</th>
                                        <th scope="col-sm-2">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center">
                                        <th scope="row">1</th>
                                        <td>ALX OS</td>
                                        <td>AlexisOsorio@gmail.com</td>
                                        <td>ADMINISTRADOR</td>
                                        <td>
                                            <a href="#" class="btn bg-warning"><i class="nav-icon fas fa-edit"></i> Editar Usuario</a>
                                            <a href="#" class="btn bg-danger"><i class="nav-icon fas fa-trash"></i> Eliminar Usuario</a>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
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