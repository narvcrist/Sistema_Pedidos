<?php
    
    include "../conexion.php";
    session_start();
    //print_r($_POST); exit;

    if(!empty($_POST)){
        // TRAER  DATOS DEL PRODUCTO
        if($_POST['action'] == 'infoProducto'){
            //echo "Consultar";
            $producto_id = $_POST['producto'];

            $consulta = mysqli_query($conection,"SELECT pro_id, pro_descripcion, pro_stock, pro_precio
                                                    FROM producto
                                                    WHERE pro_id = $producto_id 
                                                    AND pro_estado = 1");
            mysqli_close($conection);
            $resultado = mysqli_num_rows($consulta);
            if($resultado > 0){
                $data = mysqli_fetch_assoc($consulta);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }
            echo 'error';
            exit;
        }
        //Eliminar producto
        if($_POST['action'] == 'delProduct'){
            
            if(empty($_POST['producto_id']) || !is_numeric($_POST['producto_id'])){
                echo 'error';
            }else{

                $idProducto = $_POST['producto_id']; 

                //$consulta = mysqli_query($conection, " DELETE FROM usuario WHERE usu_id = $idTendero");
                $consulta = mysqli_query($conection, " UPDATE producto SET pro_estado = 0 WHERE pro_id = $idProducto");
                mysqli_close($conection);
                if($consulta){
                    echo "Eliminado";
                }else{
                    echo "Error al eliminar";
                }
            }
            echo "error";  
            exit;
        }

        //Buscar tendero
        if($_POST['action'] == 'searchCliente'){
            
            if(!empty($_POST['cliente'])){
                $ced = $_POST['cliente'];

               $consulta = mysqli_query($conection,"SELECT *FROM tendero WHERE ten_cedula LIKE '$ced' AND estado = 1");
                mysqli_close($conection);
                $resultado = mysqli_num_rows($consulta);

                $data = '';
                if($resultado > 0){
                    $data = mysqli_fetch_assoc($consulta);
                }else{
                    $data = 0;
                }
               echo json_encode($data, JSON_UNESCAPED_UNICODE);
            }
            exit;
        }

        //Registar tendero desde nueva factura
        if($_POST['action'] == 'addTendero'){

            $cedula = $_POST['ced_cliente'];
            $nombre = $_POST['nom_cliente'];
            $telefono = $_POST['tel_cliente'];
            $direccion = $_POST['dir_cliente'];
            $usuarioId = $_SESSION['Usu_id'];

            $consulta_insert = mysqli_query($conection,"INSERT INTO tendero(ten_nombre, ten_cedula, ten_direccion, ten_telefono, ten_idUsuario)
            VALUES('$nombre','$cedula','$direccion','$telefono','$usuarioId')");

            if($consulta_insert){
                $codCliente = mysqli_insert_id($conection);
                $msg = $codCliente;
            }else{
                $msg = "error";
            }
            mysqli_close($conection);
            echo $msg;
            exit;


        }

        // Agregar producto al detalle temporal
        if($_POST['action'] == 'addProductoDetalle'){
            if(empty($_POST['producto']) || empty($_POST['cantidad'])){
                echo 'error';
            }else{
                $codproducto = $_POST['producto'];
                $cantidad = $_POST['cantidad'];
                $token = md5($_SESSION['Usu_id']);

                $consulta_iva = mysqli_query($conection, "SELECT con_iva FROM configuracion");
                $resultado_iva = mysqli_num_rows($consulta_iva);

                $query_detalletemporal = mysqli_query($conection, "CALL add_detalle_temp($codproducto, $cantidad, '$token')");
                $result = mysqli_num_rows($query_detalletemporal);

                $detalleTabla = '';
                $sub_total = 0;
                $iva = 0;
                $total = 0;
                $arrayData = array();

                if($result >0){
                    if($resultado_iva >0){
                        $info_iva = mysqli_fetch_assoc($consulta_iva);
                        $iva = $info_iva['con_iva'];
                    }
                    while ($data = mysqli_fetch_assoc($query_detalletemporal)){
                        $precioTotal = round($data['deTe_cantidad'] * $data['deTe_precioTotal'], 2);
                        $sub_total = round($sub_total + $precioTotal, 2);
                        $total = round($total + $precioTotal, 2);

                        $detalleTabla .= '<tr>
                                            <td data-sortable="true" data-sort-dir="asc">'.$data['deTe_idProducto'].'</td>
                                            <td data-sortable="true" colspan="2">'.$data['pro_descripcion'].'</th>
                                            <td data-sortable="true">'.$data['deTe_cantidad'].'</td>
                                            <td data-sortable="true">'.$data['deTe_precioTotal'].'</td>
                                            <td data-sortable="true">'.$precioTotal.'</td>
                                            <td class="">
                                            <a href="" class="link_delete" onclick="event.preventDefault();
                                            del_product_detalle('.$data['deTe_idProducto'].');"><div class="mif-bin fg-red"  data-role="hint" data-hint-text="Eliminar producto"></div></a>
                                            </td>
                                        </tr>';
                    }

                    $impuesto = round($sub_total * ($iva / 100), 2);
                    $sin_iva = round($sub_total - $impuesto, 2);
                    $total = round($sin_iva + $impuesto, 2);

                    $detalleTotales = ' <tr>
                                            <td id="iz" colspan="5">SUBTOTAL Q.</td>
                                            <td class="textright">'.$sin_iva.'</td>
                                        </tr>
                                        <tr>
                                            <td id="iz" colspan="5">IVA ('.$iva.')</td>
                                            <td class="textright">'.$impuesto.'</td>
                                        </tr>
                                        <tr>
                                            <td id="iz" colspan="5">TOTAL Q.</td>
                                            <td class="textright">'.$total.'</td>
                                        </tr>';

                    $arrayData['detalle'] = $detalleTabla;
                    $arrayData['totales'] = $detalleTotales;
                    
                    echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
                }else{
                    echo 'error';
                }
                mysqli_close($conection);
            }
        }

        // Extraer al detalle temporal
        if($_POST['action'] == 'serchForDetalle'){
            if(empty($_POST['user'])){

                echo 'error';
            }else{
                
                $token = md5($_SESSION['Usu_id']);

                $query = mysqli_query($conection, "SELECT tmp.deTe_id,
                                                            tmp.token_user,
                                                            tmp.deTe_cantidad,
                                                            tmp.deTe_precioTotal,
                                                            tmp.deTe_idProducto,
                                                            p.pro_id,
                                                            p.pro_descripcion
                                                            FROM detalletemporal tmp
                                                            INNER JOIN producto p
                                                            ON tmp.deTe_idProducto = p.pro_id
                                                            WHERE token_user = '$token'");

                $result = mysqli_num_rows($query);

                $consulta_iva = mysqli_query($conection, "SELECT con_iva FROM configuracion");
                $resultado_iva = mysqli_num_rows($consulta_iva);

                $detalleTabla = '';
                $sub_total = 0;
                $iva = 0;
                $total = 0;
                $arrayData = array();

                if($result >0){
                    if($resultado_iva >0){
                        $info_iva = mysqli_fetch_assoc($consulta_iva);
                        $iva = $info_iva['con_iva'];
                    }
                    while ($data = mysqli_fetch_assoc($query)){
                        $precioTotal = round($data['deTe_cantidad'] * $data['deTe_precioTotal'], 2);
                        $sub_total = round($sub_total + $precioTotal, 2);
                        $total = round($total + $precioTotal, 2);

                        $detalleTabla .= '<tr>
                                            <td data-sortable="true" data-sort-dir="asc">'.$data['deTe_idProducto'].'</td>
                                            <td data-sortable="true" colspan="2">'.$data['pro_descripcion'].'</th>
                                            <td data-sortable="true">'.$data['deTe_cantidad'].'</td>
                                            <td data-sortable="true">'.$data['deTe_precioTotal'].'</td>
                                            <td data-sortable="true">'.$precioTotal.'</td>
                                            <td class="">
                                            <a href="" class="link_delete" onclick="event.preventDefault();
                                            del_product_detalle('.$data['deTe_idProducto'].');"><div class="mif-bin fg-red"  data-role="hint" data-hint-text="Eliminar producto"></div></a>
                                            </td>
                                        </tr>';
                    }

                    $impuesto = round($sub_total * ($iva / 100), 2);
                    $sin_iva = round($sub_total - $impuesto, 2);
                    $total = round($sin_iva + $impuesto, 2);

                    $detalleTotales = ' <tr>
                                            <td id="iz" colspan="5">SUBTOTAL Q.</td>
                                            <td class="textright">'.$sin_iva.'</td>
                                        </tr>
                                        <tr>
                                            <td id="iz" colspan="5">IVA ('.$iva.')</td>
                                            <td class="textright">'.$impuesto.'</td>
                                        </tr>
                                        <tr>
                                            <td id="iz" colspan="5">TOTAL Q.</td>
                                            <td class="textright">'.$total.'</td>
                                        </tr>';

                    $arrayData['detalle'] = $detalleTabla;
                    $arrayData['totales'] = $detalleTotales;
                    
                    echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
                }else{
                    echo 'error';
                }
                mysqli_close($conection);
            }
        }
        
    }
    exit; 
?>