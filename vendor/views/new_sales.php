<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva Venta</title>

    <?php
    include_once "../layouts/style.php"
    ?>
    <style>

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
                    <div class="col-sm-12">
                        <h2 style="font-size: 27px;text-align: center; margin: 0;padding: 0; font-weight: bold;">Nueva Venta</h2>
                        <div class="form-group row action_client">
                            <label for="Datos" class="col-sm-2 col-form-label">Datos del Cliente</label>
                            <a href="registro_clients.php" class="btn btn-primary btn_new_client"><i class="nav-icon fas fa-user-plus"></i> Nuevo Cliente</a>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="card border-primary">
                <div class="card-body">
                    <form action="" name="form_new_client" id="form_new_client">
                        <input type="hidden" name="action" value="addClient">
                        <input type="hidden" name="idclient" id="idclient" value="addClient" required>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="cedula" class="col-form-label">Cedula</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="cedula" id="cedula" placeholder="Cedula del cliente" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="nombre" class="col-form-label">Nombre</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre del cliente" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label class="col-form-label">Telefono</label>
                                <div class="col-sm-12">
                                    <input type="number" disabled class="form-control" name="telefono" id="telefono" placeholder="Telefono del cliente" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-sm-2 col-form-label">Dirección</label>
                                <div class="col-sm-12">
                                    <input type="text" disabled class="form-control" name="direccion" id="direccion" placeholder="Dirección del cliente" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row text-center" id="div_register_client">
                            <div class="col-sm-12">
                                <button type="submit" class="btn  btn-success"><i class="nav-icon fas fa-save"></i> Registrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h2 style="font-size: 27px;text-align: center; margin: 0;padding: 0; font-weight: bold;">Datos Venta</h2>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <i class="nav-icon fas fa-user"></i><label for="Datos" class="col-sm-2 col-form-label">Vendedor</label>
                                <label class="d-block text-danger"><?php echo $_SESSION['user']; ?></label>
                            </div>
                            <div class="col-sm-6 ">
                                <label for="Datos" class="col-sm-2 col-form-label">Acciones</label>
                                <div class="form-group">
                                    <a href="#" class="btn btn-secondary"><i class="nav-icon fas fa-ban"></i> Anular</a>
                                    <a href="#" class="btn btn-danger"><i class="nav-icon fas fa-save"></i> Generar Venta</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-inverse">
                            <thead class="bg-info thead-inverse">
                                <tr>
                                    <th>CODIGO</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th>STOCK</th>
                                    <th>CANTIDAD</th>
                                    <th>PRECIO</th>
                                    <th>PRECIO TOTAL</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row"><input type="number" class="form-control"></td>
                                    <td>
                                        <p>-</p>
                                    </td>
                                    <td>
                                        <p>-</p>
                                    </td>
                                    <td><input type="number" class="form-control"></td>
                                    <td><p>-</p></td>
                                    <td><p>-</p></td>
                                    <td><button type="submit"  class="btn btn-success"><i class="nav-icon fas fa-check"></i> Agregar</button></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                    <div class="col-md-12">
                        <table class="table table-striped table-inverse">
                            <thead class="bg-info thead-inverse">
                                <tr>
                                    <th>CODIGO</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th>CANTIDAD</th>
                                    <th>PRECIO</th>
                                    <th>PRECIO TOTAL</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row"><p>-</p></td>
                                    <td><p>-</p></td>
                                    <td><p>-</p></td>
                                    <td><p>-</p></td>
                                    <td><p>-</p></td>
                                    <td><a href="#" class="btn btn-dark"><i class="nav-icon fas fa-trash"></i> Eliminar</a></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td scope="row">Sub Total</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><p>-</p></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td scope="row">Iva</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><p>-</p></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td scope="row">Total</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><p>-</p></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>

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