<?php

if (empty($_SESSION['active'])) {
    header('location: ../index.php');
}
?>
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="../views/index.php" class="nav-link">Inicio</a>
            </li>
        </ul>
        <?php
        date_default_timezone_set('America/Guayaquil');

        function fechaC()
        {
            $mes = array(
                "", "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            );
            return date('d') . " de " . $mes[date('n')] . " de " . date('Y');
        }
        ?>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <p style="padding: 7px;"><b>La Maná, <?php echo fechaC(); ?> </b></p>
            </li>
            <li class="nav-item">

                <a href="../core/leave.php" class="btn bg-danger text-center"><b>Cerrar Sesion <i class="fa-solid fa-right-from-bracket"></i></b></a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <div class="modal">
        <div class="bodyModal">
            <div class="col-md-4">
                <div class="card card-success">
                    <div class="card-header">
                        <h1 class="card-title"><i class="nav-icon fas fa-cubes"></i> Agregar Producto</h1>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" name="form_add_stock" id="form_add_stock" onsubmit="event.preventDefault(); sendDataProd();">
                            <h2 class="name_prod" style="font-size: 25px; text-align: center; font-weight: bolder;"></h2>
                            <div class="form-group row">
                                <input type="number" name="cantidad" id="txtCantidad" placeholder="Cantidad del Producto" class="form-control" required>
                            </div>
                            <div class="form-group row">
                                <input type="text" name="precio" id="txtPrecio" placeholder="Precio del Producto" class="form-control" required>
                            </div>
                            <input type="hidden" name="producto_id" id="producto_id" class="form-control">
                            <input type="hidden" name="action" class="form-control" value="addProd">
                            <div class="alerta alertAddProd"></div>
                            <button type="submit" class="btn btn-success"><i class="nav-icon fas fa-plus"></i> Agregar</button>
                            <a href="#" class="btn bg-danger closeModal" style="float: right;" onclick="closeModal();"><i class="nav-icon fas fa-ban"></i> Cerrar</a>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include_once "nav.php"
    ?>