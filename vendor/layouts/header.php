<?php
session_start();
if (empty($_SESSION['active'])) {
    header('location: ../index.php');
}
?>
<header>
    <div class="header">
        <h1>Sistema de Venta y Facturación</h1>
        <div class="optionsBar">
            <p>La Maná,  <?php echo fechaC(); ?></p>
            <span>|</span>
            <span class="user">Alx Os</span>
            <img class="photouser" src="img/user.png" alt="Usuario">
            <a href="./core/leave.php"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a>
        </div>
    </div>
    <?php
    include_once "nav.php"
    ?>
</header>