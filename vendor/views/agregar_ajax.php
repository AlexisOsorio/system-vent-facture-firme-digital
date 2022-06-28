<?php
include_once '../../config/conexion.php';
session_start();
//print_r($_POST);exit;
if (!empty($_POST)) {
    //extraer datos stock
    if ($_POST['action'] == 'infoStock') {
        $stock_id = $_POST['producto'];

        $query = mysqli_query($conexion, "SELECT codproducto, descripcion,existencia,precio FROM producto 
                                        WHERE codproducto = $stock_id AND estatus = 1");
        mysqli_close($conexion);

        $result = mysqli_num_rows($query);

        if ($result > 0) {
            $data = mysqli_fetch_array($query);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            echo "error";
        }
        exit;
    }

    //Agregar datos stock a entrada
    if ($_POST['action'] == 'addProd') {
        if (!empty($_POST['cantidad']) || !empty($_POST['precio']) || !empty($_POST['producto_id'])) {
            $cantidad = $_POST['cantidad'];
            $precio = $_POST['precio'];
            $producto_id = $_POST['producto_id'];
            $usuario_id = $_SESSION['idUser'];

            $query_insert = mysqli_query($conexion, "INSERT INTO entradas(codproducto ,cantidad,precio,usuario_id) 
                                        VALUES ($producto_id,$cantidad,$precio,$usuario_id)");

            if ($query_insert) {
                //execute store procedure 
                $query_update = mysqli_query($conexion, "CALL actualizar_precio_producto
                                                        ($cantidad,$precio,$producto_id)");
                $result_stk = mysqli_num_rows($query_update);
                if ($result_stk > 0) {
                    $data = mysqli_fetch_array($query_update);
                    $data['producto_id'] = $producto_id;
                    echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    exit;
                }
            } else {
                echo "error";
                exit;
            }
            mysqli_close($conexion);
        } else {
            echo "error";
        }
        exit;
    }

    //Eliminar Producto
    if ($_POST['action'] == 'delProd') {

        if (empty($_POST['producto_id']) || !is_numeric($_POST['producto_id'])) {
            echo 'Error';
        } else {
            # code...

            $idproductoD = $_POST['producto_id'];

            $query_D = mysqli_query($conexion, "UPDATE producto SET estatus = 0 WHERE codproducto = $idproductoD");
            mysqli_close($conexion);
            if ($query_D) {
                echo 'OK';
            } else {
                echo 'error';
            }
        }
        echo 'error';
        exit;
    }

    //Buscar Cliente
    if ($_POST['action'] == 'searchCliente') {
        if (!empty($_POST['cliente'])) {
            $cedula = $_POST['cliente'];

            $query = mysqli_query($conexion, "SELECT * FROM cliente WHERE ruc LIKE '$cedula' AND estatus = 1");
            mysqli_close($conexion);
            $result = mysqli_num_rows($query);

            $data = '';
            if (($result > 0)) {
                $data = mysqli_fetch_assoc($query);
            } else {
                $data = 0;
            }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        exit;
    }

    //register clients mod sales
    if ($_POST['action'] == 'addClient') {

        $cedula = $_POST['cedula_client'];
        $nombre = $_POST['nombre_client'];
        $telefono = $_POST['telefono_client'];
        $direccion = $_POST['direccion_client'];
        $usuario_id = $_SESSION['idUser'];

        $query_insert = mysqli_query($conexion, "INSERT INTO cliente(ruc,nombre,telefono,direccion, usuario_id) 
                                        VALUES('$cedula', '$nombre', '$telefono', '$direccion', '$usuario_id')");
        if ($query_insert) {
            $codClient = mysqli_insert_id($conexion);
            $mensaje = $codClient;
        } else {
            $mensaje = 'error';
        }
        mysqli_close($conexion);
        echo $mensaje;
        exit;
    }

    //add product detall temp
    if ($_POST['action'] == 'addProductDetall') {
        if (empty($_POST['producto'] || empty($_POST['cantidad']))) {
            echo 'error';
        } else {
            $codproducto = $_POST['producto'];
            $cantidad = $_POST['cantidad'];
            $token = md5($_SESSION['idUser']);

            $query_iva = mysqli_query($conexion, "SELECT iva FROM configuracion");
            $result_iva = mysqli_num_rows($query_iva);

            $query_detalle_temp = mysqli_query($conexion, "CALL add_detalle_temp($codproducto,$cantidad,'$token')");
            $result_temp = mysqli_num_rows($query_detalle_temp);

            $detallTable = '';
            $sub_total = 0;
            $iva = 0;
            $total = 0;
            $DateArray = array();

            if ($result_temp > 0) {
                if ($result_iva > 0) {
                    $info_iva = mysqli_fetch_assoc($query_iva);
                    $iva = $info_iva['iva'];
                }

                while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
                    $precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
                    $sub_total = round($sub_total + $precioTotal, 2);
                    $total = round($total + $precioTotal, 2);

                    $detallTable .= '
                    <tr>
                        <td scope="row">' . $data['codproducto'] . '</td>
                        <td colspan="2">' . $data['descripcion'] . '</td>
                        <td>' . $data['cantidad'] . '</td>
                        <td>' . $data['precio_venta'] . '</td>
                        <td>' . $precioTotal . '</td>
                        <td>
                            <a href="#" class="btn btn-dark link_delete" onclick="event.preventDefault(); 
                            del_product_detalle(' . $data['codproducto'] . ');"><i class="nav-icon fas fa-trash"></i> Eliminar</a>
                        </td>
                    </tr>
                    ';
                }

                $impuesto = $sub_total * ($iva / 100);
                $tl_sin_iva = $sub_total - $impuesto;
                $total = round($tl_sin_iva + $impuesto, 2);

                $detall_Totals = '
                <tr>
                    <td scope="row" colspan="5" class="text-right" style="font-weight: bold;">SUBTOTAL $</td>
                    <td style="font-weight: bold;">'.$tl_sin_iva.'</td>
                </tr>
                <tr>
                    <td scope="row" colspan="5" class="text-right" style="font-weight: bold;">IVA('.$iva.'%)</td>
                    <td style="font-weight: bold;">'.$impuesto.'</td>
                </tr>
                <tr>
                    <td scope="row" colspan="5" class="text-right" style="font-weight: bold;">TOTAL</td>
                    <td style="font-weight: bold;">'.$total.'</td>
                </tr>
                ';

                $DateArray['detalle'] = $detallTable;
                $DateArray['totales'] = $detall_Totals;

                echo json_encode($DateArray,JSON_UNESCAPED_UNICODE);
            }else {
                echo 'error';
            }
            mysqli_close($conexion);
        }
        exit;
    }
}
exit;
