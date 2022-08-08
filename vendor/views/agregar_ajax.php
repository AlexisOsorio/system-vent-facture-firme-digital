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
                            <a href="#" class="btn btn-dark" onclick="event.preventDefault(); del_product_detalle(' . $data['correlativo'] . ');"><i class="nav-icon fas fa-trash"></i> Eliminar</a>
                        </td>
                    </tr>
                    ';
                }

                $impuesto = round($sub_total * ($iva / 100), 2);
                $tl_sin_iva = round($sub_total - $impuesto, 2);
                $total = round($tl_sin_iva + $impuesto, 2);

                $detall_Totals = '
                <tr>
                    <td scope="row" colspan="5" class="text-right" style="font-weight: bold;">SUBTOTAL $</td>
                    <td style="font-weight: bold;">' . $tl_sin_iva . '</td>
                </tr>
                <tr>
                    <td scope="row" colspan="5" class="text-right" style="font-weight: bold;">IVA(' . $iva . '%)</td>
                    <td style="font-weight: bold;">' . $impuesto . '</td>
                </tr>
                <tr>
                    <td scope="row" colspan="5" class="text-right" style="font-weight: bold;">TOTAL</td>
                    <td style="font-weight: bold;">' . $total . '</td>
                </tr>
                ';

                $DateArray['detalle'] = $detallTable;
                $DateArray['totales'] = $detall_Totals;

                echo json_encode($DateArray, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
            mysqli_close($conexion);
        }
        exit;
    }

    //extraer datos de detalle temp
    if ($_POST['action'] == 'searchForDetalle') {
        if (empty($_POST['user'])) {
            echo 'error';
        } else {
            $token = md5($_SESSION['idUser']);

            $query = mysqli_query($conexion, "SELECT tmp.correlativo,
                                                    tmp.token_user,
                                                    tmp.cantidad,
                                                    tmp.precio_venta,
                                                    p.codproducto,
                                                    p.descripcion
                                                FROM detalle_temp tmp INNER JOIN producto p
                                                ON tmp.codproducto = p.codproducto 
                                                WHERE token_user = '$token'");

            $result_temp = mysqli_num_rows($query);

            $query_iva = mysqli_query($conexion, "SELECT iva FROM configuracion");
            $result_iva = mysqli_num_rows($query_iva);


            $detallTable = '';
            $sub_total = 0;
            $iva = 0;
            $total = 0;
            $data = "";
            $DateArray = array();

            if ($result_temp > 0) {
                if ($result_iva > 0) {
                    $info_iva = mysqli_fetch_assoc($query_iva);
                    $iva = $info_iva['iva'];
                }

                while ($data = mysqli_fetch_assoc($query)) {
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
                            <a href="#" class="btn btn-dark" onclick="event.preventDefault(); del_product_detalle(' . $data['correlativo'] . ');"><i class="nav-icon fas fa-trash"></i> Eliminar</a>
                        </td>
                    </tr>
                    ';
                }

                $impuesto = round($sub_total * ($iva / 100), 2);
                $tl_sin_iva = round($sub_total - $impuesto, 2);
                $total = round($tl_sin_iva + $impuesto, 2);

                $detall_Totals = '
                <tr>
                    <td scope="row" colspan="5" class="text-right" style="font-weight: bold;">SUBTOTAL $</td>
                    <td style="font-weight: bold;">' . $tl_sin_iva . '</td>
                </tr>
                <tr>
                    <td scope="row" colspan="5" class="text-right" style="font-weight: bold;">IVA(' . $iva . '%)</td>
                    <td style="font-weight: bold;">' . $impuesto . '</td>
                </tr>
                <tr>
                    <td scope="row" colspan="5" class="text-right" style="font-weight: bold;">TOTAL</td>
                    <td style="font-weight: bold;">' . $total . '</td>
                </tr>
                ';

                $DateArray['detalle'] = $detallTable;
                $DateArray['totales'] = $detall_Totals;

                echo json_encode($DateArray, JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $data = 0;
                exit;
            }
            mysqli_close($conexion);
        }
        exit;
    }

    //eliminar datos de detalle temp
    if ($_POST['action'] == 'delProductoDetalle') {
        //print_r($_POST); exit;
        if (empty($_POST['id_detalle'])) {
            echo 'error';
        } else {
            $id_detalle = $_POST['id_detalle'];
            $token = md5($_SESSION['idUser']);


            $query_iva = mysqli_query($conexion, "SELECT iva FROM configuracion");
            $result_iva = mysqli_num_rows($query_iva);

            $query_detalle_tmp = mysqli_query($conexion, "CALL del_detalle_temp($id_detalle,'$token')");
            $result = mysqli_num_rows($query_detalle_tmp);

            $detalleTabla = '';
            $sub_total = 0;
            $iva = 0;
            $total = 0;
            $data = "";
            $DateArray = array();
            if ($result > 0) {
                if ($result_iva > 0) {
                    $info_iva = mysqli_fetch_assoc($query_iva);
                    $iva = $info_iva['iva'];
                }
                while ($data = mysqli_fetch_assoc($query_detalle_tmp)) {
                    $precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
                    $total = round($total + $precioTotal, 2);
                    $sub_total = round($sub_total + $precioTotal, 2);

                    $detallTable .= '
                    <tr>
                        <td scope="row">' . $data['codproducto'] . '</td>
                        <td colspan="2">' . $data['descripcion'] . '</td>
                        <td>' . $data['cantidad'] . '</td>
                        <td>' . $data['precio_venta'] . '</td>
                        <td>' . $precioTotal . '</td>
                        <td>
                            <a href="#" class="btn btn-dark" onclick="event.preventDefault(); del_product_detalle(' . $data['correlativo'] . ');"><i class="nav-icon fas fa-trash"></i> Eliminar</a>
                        </td>
                    </tr>
                    ';
                }
                $impuesto = round($sub_total * ($iva / 100), 2);
                $tl_sniva = round($sub_total - $impuesto, 2);
                $total = round($tl_sniva + $impuesto, 2);

                $detall_Totals = '
                <tr>
                    <td scope="row" colspan="5" class="text-right" style="font-weight: bold;">SUBTOTAL $</td>
                    <td style="font-weight: bold;">' . $tl_sin_iva . '</td>
                </tr>
                <tr>
                    <td scope="row" colspan="5" class="text-right" style="font-weight: bold;">IVA(' . $iva . '%)</td>
                    <td style="font-weight: bold;">' . $impuesto . '</td>
                </tr>
                <tr>
                    <td scope="row" colspan="5" class="text-right" style="font-weight: bold;">TOTAL</td>
                    <td style="font-weight: bold;">' . $total . '</td>
                </tr>
                ';

                $DateArray['detalle'] = $detalleTabla;
                $DateArray['totales'] = $detalleTotales;

                echo json_encode($DateArray, JSON_UNESCAPED_UNICODE);
            } else {
                $data = 0;
                exit;
            }
            mysqli_close($conexion);
        }
        exit;
    }

    //anular venta
    if ($_POST['action'] == 'anularVenta') {
        $data = "";
        $token = md5($_SESSION['idUser']);
        $query_del = mysqli_query($conexion, "DELETE FROM detalle_temp WHERE token_user = '$token'");
        mysqli_close($conexion);
        if ($query_del) {
            echo 'ok';
        } else {
            $data = 0;
            echo 'error';
        }
        exit;
    }

    //procesar venta
    if ($_POST['action'] == 'procesarVenta') {
        if (empty($_POST['codCliente'])) {
            $codCliente = 1;
        } else {
            $codCliente = $_POST['codCliente'];
        }
        $token = md5($_SESSION['idUser']);
        $usuario = $_SESSION['idUser'];
        $query_pr = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE token_user = '$token'");
        $result_pr = mysqli_num_rows($query_pr);

        if ($result_pr > 0) {
            $query_pr_alm = mysqli_query($conexion, "CALL procesar_venta($usuario, $codCliente, '$token')");
            $result_alm = mysqli_num_rows($query_pr_alm);

            if ($result_alm > 0) {
                $data = mysqli_fetch_assoc($query_pr_alm);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
        } else {
            echo 'error';
        }
        mysqli_close($conexion);
        exit;
    }

    //cambiar contra
    if ($_POST['action'] == 'changeContra') {
        if (!empty($_POST['pass_actual']) && !empty($_POST['pass_new'])) {
            $password_c = md5($_POST['pass_actual']);
            $new_pass = md5($_POST['pass_new']);
            $idUser = $_SESSION['idUser'];

            $codeP = '';
            $mesg = '';
            $DateArray = array();

            $query_user_p = mysqli_query($conexion, "SELECT * FROM usuario 
                                                    WHERE clave = '$password_c' AND idusuario = $idUser");
            $result_pass = mysqli_num_rows($query_user_p);

            if ($result_pass > 0) {
                $query_update_pas = mysqli_query($conexion, "UPDATE usuario SET clave = '$new_pass' 
                                                            WHERE idusuario = $idUser");
                mysqli_close($conexion);

                if ($query_update_pas) {
                    $codeP = '00';
                    $mesg = "Su contraseña se actualizo correctamente";
                } else {
                    $codeP = '2';
                    $mesg = "No se puede cambiar la contraseña";
                }
            } else {
                $codeP = '1';
                $mesg = "La contraseña es incorrecta";
            }
            $DateArray = array('codeP' => $codeP, 'mesg' => $mesg);
            echo json_encode($DateArray,JSON_UNESCAPED_UNICODE);
        } else {
            echo 'error';
        }
        exit;
    }

    //cambiar datos empresa 
    
}
exit;
