<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema Venta | Dashboard</title>

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
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="text-center" style="font-weight: bold;
                                                        background-color: #fff; 
                                                        padding: 5px 10px;
                                                        margin-bottom: 10px;
                                                        font-size: 25px;
                                                        color: #0A4661;">Panel de Control</h1>
                    </div>
                </div>
                <div class="container">
                    <div class="container-fluid">
                        <div class="row text-center">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-2">
                                <div class="card bg-primary" style="padding: 2px;">
                                    <a href="list_users.php">
                                        <i class="nav-icon fas fa-users" style="font-size: 40px;"></i>
                                        <p>
                                            <strong>Usuarios</strong><br>
                                            <span>100</span>
                                        </p>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="card bg-success" style="padding: 2px;">
                                    <a href="list_clients.php">
                                        <i class="nav-icon fas fa-hand-holding-dollar" style="font-size: 40px;"></i>
                                        <p>
                                            <strong>Clientes</strong><br>
                                            <span>100</span>
                                        </p>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="card bg-info" style="padding: 2px;">
                                    <a href="list_proveedor.php">
                                        <i class="nav-icon fas fa-truck" style="font-size: 40px;"></i>
                                        <p>
                                            <strong>Proveedores</strong><br>
                                            <span>100</span>
                                        </p>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="card bg-warning" style="padding: 2px;">
                                    <a href="list_stock.php">
                                        <i class="nav-icon fas fa-cubes" style="font-size: 40px;"></i>
                                        <p>
                                            <strong>Productos</strong><br>
                                            <span>100</span>
                                        </p>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="card bg-danger" style="padding: 2px;">
                                    <a href="list_sales.php">
                                        <i class="nav-icon fas fa-file-invoice-dollar" style="font-size: 40px;"></i>
                                        <p>
                                            <strong>Ventas</strong><br>
                                            <span>100</span>
                                        </p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section>
            <div class="container">
                <div class="container-fluid">
                    <div class="col-sm-12">
                        <h1 class="text-center" style="font-weight: bold;
                                                        background-color: #fff; 
                                                        padding: 5px 10px;
                                                        margin-bottom: 10px;
                                                        font-size: 25px;
                                                        color: #0A4661;">Confuguración</h1>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title" style="font-weight: bold;">Informacion del Usuario</h3>
                                </div>
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img src="../utils/img/photo-users.png" class="profile-user-img img-fluid img-circle">
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-control">Nombre: <span><?php echo $_SESSION['nombre'] ?></span></label>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-control">Correo: <span><?php echo $_SESSION['email'] ?></span></label>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-control">Rol: <span><?php echo $_SESSION['rol_name'] ?></span></label>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-control">Usuario: <span><?php echo $_SESSION['user'] ?></strong></label>
                                    </div>
                                    <ul class="list-group">
                                        <li class="list-group-item active">Cambiar Contraseña</li>
                                        <form action="" method="POST" name="frmElegirPass" id="frmElegirPass">
                                            <div class="form-group">
                                                <label for="txt_actualP" class="col-sm-5 col-form-label">Contraseña Actual</label>
                                                <div class="col-sm-12">
                                                    <input type="password" name="txt_actualP" id="txt_actualP" placeholder="Contraseña Actual" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="txt_nuevaP" class="col-sm-5 col-form-label">Contraseña Nueva</label>
                                                <div class="col-sm-12">
                                                    <input type="password" name="txt_nuevaP" id="txt_nuevaP" placeholder="Contraseña Nueva" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="txt_confirmarP" class="col-sm-5 col-form-label">Confirmar Contraseña</label>
                                                <div class="col-sm-12">
                                                    <input type="password" name="txt_confirmarP" id="txt_confirmarP" placeholder="Confirmar Contraseña" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="alertElejirPass" style="display:none;"></div>
                                            <div class="form-group text-center">
                                                <button type="submit" class="btn btn-primary btnElegirPass"><i class="nav-icon fas fa-key"></i> Cambiar Contraseña</button>
                                            </div>
                                        </form>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                        <!-- Default box -->
                        <div class="col-sm-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title" style="font-weight: bold;">Datos de la Empresa</h3>
                                </div>
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img src="../utils/img/img-logo-empresa.jpg" class="profile-user-img img-fluid img-circle">
                                    </div>
                                    <form action="" method="POST" name="frmEmpresa" id="frmEmpresa">
                                        <input type="hidden" name="action" value="udpateDate_Empresa">
                                        <div class="form-group">
                                            <label for="txtRuc" class="col-sm-5 col-form-label">Ruc/Cedula: </label>
                                            <div class="col-sm-12">
                                                <input type="text" name="txtRuc" id="txtRuc" placeholder="Ruc/Cedula de la Empresa" value="" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="txtNombre" class="col-sm-5 col-form-label">Nombre: </label>
                                            <div class="col-sm-12">
                                                <input type="text" name="txtNombre" id="txtNombre" placeholder="Nombre de la Empresa" value="" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="txtRSocial" class="col-sm-5 col-form-label">Razon Social: </label>
                                            <div class="col-sm-12">
                                                <input type="text" name="txtRSocial" id="txtRSocial" placeholder="Razon Social de la Empresa" value="" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="txtTelefono" class="col-sm-5 col-form-label">Telefono: </label>
                                            <div class="col-sm-12">
                                                <input type="text" name="txtTelefono" id="txtTelefono" placeholder="Telefono de la Empresa" value="" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="txtCElectronico" class="col-sm-5 col-form-label">Correo Electrónico: </label>
                                            <div class="col-sm-12">
                                                <input type="text" name="txtCElectronico" id="txtCElectronico" placeholder="Correo Electronico de la Empresa" value="" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="txtDireccion" class="col-sm-5 col-form-label">Direccion: </label>
                                            <div class="col-sm-12">
                                                <input type="text" name="txtDireccion" id="txtDireccion" placeholder="Dirección de la Empresa" value="" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="txtIva" class="col-sm-5 col-form-label">IVA (%): </label>
                                            <div class="col-sm-12">
                                                <input type="text" name="txtIva" id="txtIva" placeholder="Iva de la Empresa" value="" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group text-center">
                                                <button type="submit" class="btn btn-primary btnElegirPass"><i class="nav-icon fas fa-save"></i> Guardar Datos</button>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
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