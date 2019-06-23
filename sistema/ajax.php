<?php
    
    include "../conexion.php";
    session_start();
    //print_r($_POST); exit;

    if(!empty($_POST)){
        //tRAER  DATOS DEL PRODUCTO
        if($_POST['action'] == 'infoProducto'){
            //echo "Consultar";
            $producto_id = $_POST['producto'];

            $consulta = mysqli_query($conection,"SELECT pro_id, pro_descripcion
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
        }
        exit;
    }
    exit; 
?>