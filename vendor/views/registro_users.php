<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrar Usuarios</title>

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
                        <h2><b>Usuarios</b></h2>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="./index.php">Inicio</a></li>
                            <li class="breadcrumb-item active">Registrar Usuarios</li>
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
                            <div class="card card-success ">
                                <div class="card-header">
                                    <h3 class="card-title text-center">Registar Nuevo Usuario</h3>
                                </div>
                                <div class="card-body">
                                    <div class="alerta alert-danger text-center" style="display:none;">
                                    </div>
                                    <form action="" class="form-horizontal" method="POST">
                                        <div class="form-group row">
                                            <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="correo" class="col-sm-2 col-form-label">Correo Electrónico</label>
                                            <div class="col-sm-10">
                                                <input type="email" name="correo" id="correo" placeholder="Correo Electrónico" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="usuario" class="col-sm-2 col-form-label">Usuario</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="usuario" id="usuario" placeholder="Ingrese el Usuario" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="pass" class="col-sm-2 col-form-label">Contraseña</label>
                                            <div class="col-sm-10">
                                                <input type="password" name="pass" id="pass" placeholder="Ingrese la Contraseña" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="rol" class="col-sm-2 col-form-label">Tipo de Usuario</label>
                                            <div class="col-sm-5">
                                                <select name="rol" id="rol" class="form-control">
                                                    <option value="0">Selecciona un rol</option>
                                                    <option value="1">Administrador</option>
                                                    <option value="2">Supervisor</option>
                                                    <option value="3">Vendedor</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10 float-right">
                                                <input type="submit" class="btn btn-block btn-outline-success" value="Registrar Usuario">
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