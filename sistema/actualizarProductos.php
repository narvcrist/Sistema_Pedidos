<?php
    session_start();
    if($_SESSION['rol'] !=1 AND $_SESSION['rol'] !=2){
        header('location: ./');
    }
    include "../conexion.php";

    if(!empty($_POST)){ //Accion del boton guardar

        $alert='';
        if(empty($_POST['proveedor']) || empty($_POST['nombre']) || empty($_POST['precio'])
        || empty($_POST['cantidad']) || empty($_POST['id']) || empty($_POST['foto_actual']) || empty($_POST['foto_remove'])){
           
            $alert='<p class="mensage">Todos los campos son abligatorios</p>';
        }else{
            $producto_id = $_POST['id'];
            $proveedor = $_POST['proveedor'];
            $nombre = $_POST['nombre'];
            $precio = $_POST['precio'];
            $cantidad = $_POST['cantidad'];
            $imgProducto = $_POST['foto_actual'];
            $imgRemove = $_POST['foto_remove'];

            $foto = $_FILES['foto'];
            $nombre_foto = $foto['name'];
            $type = $foto['type'];
            $url_temp = $foto['tmp_name'];

            $upd = '';

            if($nombre_foto != ''){ //Condicion cuando no lleva foto  
                $destino = 'img/subidas/';
                $img_nombre = 'img_'.md5(date('d-m-Y H:m:s')); //Para generar un nombre alterno de la foto (encriptamos la foto)
                $imgProducto = $img_nombre.'.jpg'; //Todas las fotos se van a guardar en formato jpg
                $src = $destino.$imgProducto; //Almceno el dstino y la foto 
            }else{
                if($_POST['foto_actual'] != $_POST['foto_remove']){ //Condicion cuando se elimina la foto toma el nombre de la foto por defecto img_producto.png
                    $imgProducto = 'img_producto.png';
                }
            }
                $consulta_update = mysqli_query($conection,"UPDATE producto
                                                            SET pro_descripcion = '$nombre', 
                                                            pro_idProveedor = $proveedor, 
                                                            pro_precio = $precio, 
                                                            pro_stock = $cantidad,
                                                            pro_foto = '$imgProducto'
                                                            WHERE pro_id = $producto_id");

                if($consulta_update){

                    if(($nombre_foto != '' && ($_POST['foto_actual'] != 'img_producto.png')) || ($_POST['foto_actual'] != $_POST['foto_remove'])){
                        unlink('img/subidas/'.$_POST['foto_actual']);
                    }
                    if($nombre_foto != ''){ //Condicion cuando la foto si va un archivo crea la foto y la copia a la ruta img/subidas
                        move_uploaded_file($url_temp, $src);
                    }    
                    $alert='<p class="mensage">Producto actualizado con exito </p>';
                }else{
                    $alert='<p class="mensage">Ocurrio un error</p>';
                }
            }
    }
    //Validacion del producto
    if(empty($_REQUEST['id'])){
        header("location: listaProductos.php");
    }else{
        $id_producto = $_REQUEST['id'];
        if(!is_numeric($id_producto)){ //Condicion para ver si el id no es numerico
            header("location: listaProductos.php");
        }

        $consulta_producto = mysqli_query($conection, "SELECT p.pro_id, p.pro_descripcion, p.pro_precio, p.pro_stock, p.pro_foto, pr.prov_id, pr.prov_nombre 
                                                        FROM producto p
                                                        INNER JOIN proveedor pr
                                                        ON p.pro_idProveedor = pr.prov_id
                                                        WHERE p.pro_id = $id_producto AND p.pro_estado = 1");
        $resultado_producto = mysqli_num_rows($consulta_producto);

        $foto ='';
        $classRemove = 'notBlock';

        if($resultado_producto > 0){
            $data_producto = mysqli_fetch_assoc($consulta_producto);

            if($data_producto['pro_foto'] != 'img_producto.png'){
                $classRemove = '';
                $foto = '<img id="img" src="img/subidas/'.$data_producto['pro_foto'].'" alt="Producto">';
            }
            //print_r($data_producto);
        }else{
            header("location: listaProductos.php");
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Actualizar producto</title>
  <?php include "estructura/estructura.php"; ?>
</head>
<body>
<?php include "estructura/header.php"; ?>
    <div class="bloque">
    <div class = "publicar">
    <h2>Actualizar producto</h2>
        <div class="card">
            
            </h5></strong></div>
            <div class="alerta"><?php echo isset($alert)? $alert : ''; ?></div>
            <div class="card-content p-2">
                <form class="ingresar" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $data_producto['pro_id']; ?>"> 
                <input type="hidden" id="foto_actual" name="foto_actual" value="<?php echo $data_producto['pro_foto']; ?>"> 
                <input type="hidden" id="foto_remove" name="foto_remove" value="<?php echo $data_producto['pro_foto']; ?>"> 
                <div class="form-group">
                        <label for="proveedor">Proveedor:</label>

                        <?php
                            $consulta_proveedor = mysqli_query($conection, "SELECT prov_id, prov_nombre FROM proveedor
                                                                      WHERE prov_estado = 1 ORDER BY prov_nombre ASC");
                            $resultado_proveedor = mysqli_num_rows($consulta_proveedor);
                            mysqli_close($conection);
                        ?>
                        <select name="proveedor" id='proveedor' class="unaVez">
                            <option value="<?php echo $data_producto ['prov_id']; ?>" selected ><?php echo $data_producto['prov_nombre']; ?></option>
                            <?php
                                
                                if ($resultado_proveedor > 0){
                                    while ($proveedor = mysqli_fetch_array($consulta_proveedor)){
                            ?>
                                    <option value="<?php echo $proveedor['prov_id']; ?>"><?php echo $proveedor['prov_nombre']; ?></option>
                            <?php    
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Producto:</label>
                        <input type="text" name="nombre" placeholder="Nombre del producto" value="<?php echo $data_producto['pro_descripcion']; ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio:</label>
                        <input type="text" name="precio" placeholder="Precio del producto" value="<?php echo $data_producto['pro_precio']; ?>" onkeypress="return solonumeros(event);"/>
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Cantidad:</label>
                        <input type="text" data-role="spinner" data-buttons-position="right" name="cantidad" data-min-value="1" data-max-value="500" value="<?php echo $data_producto['pro_stock']; ?>" onkeypress="return solonumeros(event);"/>
                    </div>
                    <div class="form-group">
                        <div class="photo">
	                    <label for="foto">Imagen del producto:</label>
                            <div class="prevPhoto">
                                <span class="delPhoto <?php echo $classRemove; ?>"><span class="mif-cancel mif-2x fg-red"></span></span>
                                <label for="foto"></label>
                                <?php echo $foto; ?>
                            </div>
                            <div class="upimg">
                                <input type="file" name="foto" id="foto">
                            </div>
                            <div id="form_alert"></div>
                        </div>
                    </div>
                    <br>
                    <div class="col-12 text-right">
                        <button class="button success">Actualizar producto</button>
                    </div>
                </form>  
            </div>
        </div>
    </div>  
    </div>
</body>
</html>