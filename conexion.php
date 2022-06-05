<?php
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $db = 'facturacion';

    $conexion = @mysqli_connect($host,$user,$password,$db);

    if(!$conexion){
        echo 'Error al conectar con el servidor del sistema';
    }
?>