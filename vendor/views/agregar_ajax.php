<?php
include_once '../../config/conexion.php';
session_start();
//print_r($_POST);exit;
if (!empty($_POST)) {
    //extraer datos stock
    if ($_POST['action'] == 'infoStock') {
        $stock_id = $_POST['producto'];
        
        $query = mysqli_query($conexion,"SELECT codproducto, descripcion FROM producto 
                                        WHERE codproducto = $stock_id AND estatus = 1");
        mysqli_close($conexion);

        $result = mysqli_num_rows($query);

        if ($result > 0){
            $data = mysqli_fetch_array($query);
            echo json_encode($data,JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo "error";
        exit;


    }
}
