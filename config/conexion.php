<?php
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $db = 'facturacion';

    $conexion = mysqli_connect($host,$user,$password,$db);

    if (mysqli_connect_errno()){
        echo "No se pudo conectar a la base de datos";
        exit();
    }
 
    mysqli_select_db($conexion,$db) or die("No se encuentra la base de datos");

    mysqli_set_charset($conexion,"utf8");
?>