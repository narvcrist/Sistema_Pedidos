<?php
    session_start();
    if($_SESSION['rol'] !=1 AND $_SESSION['rol'] !=2){
        header('location: ./');
    }
    include "../conexion.php";

    if(!empty($_POST)){ //Accion del boton guardar

        $alert='';
        if(empty($_POST['proveedor']) || empty($_POST['nombre']) || empty($_POST['precio'])
        || empty($_POST['cantidad'])){
           
            $alert='<p class="mensage">Todos los campos son abligatorios</p>';
        }else{
            $proveedor = $_POST['proveedor'];
            $nombre = $_POST['nombre'];
            $precio = $_POST['precio'];
            $cantidad = $_POST['cantidad'];
            $usuarioId = $_SESSION['Usu_id'];

            $foto = $_FILES['foto'];
            $nombre_foto = $foto['name'];
            $type = $foto['type'];
            $url_temp = $foto['tmp_name'];

            $imgProducto = 'img_producto.png';

            if($nombre_foto != ''){ //Condicion cuando no lleva foto  
                $destino = 'img/subidas/';
                $img_nombre = 'img_'.md5(date('d-m-Y H:m:s')); //Para generar un nombre alterno de la foto (encriptamos la foto)
                $imgProducto = $img_nombre.'.jpg'; //Todas las fotos se van a guardar en formato jpg
                $src = $destino.$imgProducto; //Almceno el dstino y la foto 
            }
                $consulta_insert = mysqli_query($conection,"INSERT INTO producto(pro_descripcion, pro_idProveedor, pro_precio, pro_stock, pro_foto, pro_idUsuario)
                VALUES('$nombre','$proveedor','$precio','$cantidad', '$imgProducto', '$usuarioId' )");

                if($consulta_insert){
                    if($nombre_foto != ''){ //Condicion cuando la foto si va un archivo
                        move_uploaded_file($url_temp, $src);
                    }    
                    $alert='<p class="mensage">Producto guardado con exito </p>';
                }else{
                    $alert='<p class="mensage">Ocurrio un error</p>';
                }
            }
    }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Registrar producto</title>
  <?php include "estructura/estructura.php"; ?>
</head>
<body>
<?php include "estructura/header.php"; ?>
    <div class="bloque">
    <div class = "publicar">
    <h2>Registrar producto</h2>
        <div class="card">
            
            </h5></strong></div>
            <div class="alerta"><?php echo isset($alert)? $alert : ''; ?></div>
            <div class="card-content p-2">
                <form class="ingresar" action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                        <label for="proveedor">Proveedor:</label>

                        <?php
                            $consulta_proveedor = mysqli_query($conection, "SELECT prov_id, prov_nombre FROM proveedor
                                                                      WHERE prov_estado = 1 ORDER BY prov_nombre ASC");
                            $resultado_proveedor = mysqli_num_rows($consulta_proveedor);
                            mysqli_close($conection);
                        ?>
                        <select name="proveedor" id='proveedor' class="unaVez">

                            <?php
                                
                                if ($resultado_proveedor > 0){
                                    while ($proveedor = mysqli_fetch_array($consulta_proveedor)){
                            ?>
                                    <option value="<?php echo $proveedor['prov_id']; ?>"><?php echo $proveedor['prov_nombre'] ?></option>
                            <?php    
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Producto:</label>
                        <input type="text" name="nombre" placeholder="Nombre del producto"/>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio:</label>
                        <input type="number" name="precio" placeholder="Precio del producto"/>
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Cantidad:</label>
                        <input type="text" data-role="spinner" data-buttons-position="right" name="cantidad" data-min-value="1" data-max-value="500" name="cantidad" />
                    </div>
                    <div class="form-group">
                        <div class="photo">
	                    <label for="foto">Imagen del producto:</label>
                            <div class="prevPhoto">
                                <span class="delPhoto notBlock"><span class="mif-cancel mif-2x fg-red"></span></span>
                                <label for="foto"></label>
                            </div>
                            <div class="upimg">
                                <input type="file" name="foto" id="foto">
                            </div>
                            <div id="form_alert"></div>
                        </div>
                    </div>
                    <br>
                    <div class="col-12 text-right">
                        <button class="button success">Registrar producto</button>
                    </div>
                </form>  
            </div>
        </div>
    </div>  
    </div>
</body>
</html>