<?php
    session_start();
     
    include "../conexion.php";
    if(!empty($_POST)){
        $alert='';
        if(empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['correo'])
        || empty($_POST['direccion'])){
           
            $alert='<p class="mensage">Todos los campos son abligatorios</p>';
        }else{
            
            $idProveedor= $_POST['idProveedor'];
            $nombre =  $_POST['nombre'];
            $telefono =  $_POST['telefono'];
            $correo = $_POST['correo'];
            $direccion =  $_POST['direccion'];
            $resultado = 0;
            if(is_numeric($idProveedor)){
                $consulta= mysqli_query($conection,"SELECT *FROM proveedor
                                                    WHERE  (prov_nombre = '$nombre' AND prov_id != $idProveedor)
                                                    ");
                 $resultado = mysqli_fetch_array($consulta);
                // $resultado = count($resultado);
            }   
            if($resultado > 0){
                $alert='<p class="mensage">El proveedor ya existe</p>';
            }else{
                    $consulta = mysqli_query($conection,"UPDATE proveedor
                                                        SET prov_nombre = '$nombre', prov_telefono = '$telefono', prov_email = '$correo', prov_direccion = '$direccion'
                                                        WHERE prov_id = $idProveedor");
                }
                if($consulta){
                    $alert='<p class="mensage">Proveedor actualizado con exito</p>';
                }else{
                    $alert='<p class="mensage">Ocurrio un error</p>';
                }
            }
        }
    
    if(empty($_REQUEST['id'])){
        header('location: listaProveedores.php');
    }
    $idProveedor= $_REQUEST['id'];
    $consulta = mysqli_query($conection,"SELECT *FROM proveedor
                                        WHERE prov_id = $idProveedor");
    $resultado = mysqli_num_rows($consulta);
    if($resultado == 0){
        header('location : listaProveedores.php');
    }else{
        $option = '';
        while($data = mysqli_fetch_array($consulta)){
            $idProveedor = $data['prov_id'];
            $nombre = $data['prov_nombre'];
            $telefono = $data['prov_telefono'];
            $correo = $data['prov_email'];
            $direccion = $data['prov_direccion'];
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Actualizar proveedor</title>
  <?php include "estructura/estructura.php"; ?>
</head>
<body>
<?php include "estructura/header.php"; ?>
    <div class="bloque">
    <div class = "publicar">
    <h2>Actualizar proveedor</h2>
        <div class="card">
            
            </h5></strong></div>
            <div class="alerta"><?php echo isset($alert)? $alert : ''; ?></div>
            <div class="card-content p-2">
            <form class="ingresar" action="" method="post" data-role="validator" action="javascript:">
                    <div class="form-group">
                        <label for="nombre">Nombres y apellidos:</label>
                        <input type="hidden" name="idProveedor" value="<?php echo $idProveedor; ?>">
                        <input type="text" name="nombre" placeholder="Ingresa tus nombres completos" value="<?php echo $nombre; ?>" data-validate="required"/>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="number" name="telefono" placeholder="Ingrese el número telefónico" value="<?php echo $telefono; ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo electrónico:</label>
                        <input type="email" name="correo" placeholder="Correo electrónico" value="<?php echo $correo; ?>" data-validate="required email"/>
                        <span class="invalid_feedback">
                            Ingresa un correo electrónico válido
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección:</label>
                        <input type="text" name="direccion" placeholder="Ingrese la dirección"value="<?php echo $direccion; ?>"  data-validate="required"/>
                    </div>
                    <br>
                    <div class="col-12 text-right">
                        <button class="button success">Actualizar</button>
                    </div>
                </form>    
            </div>
        </div>
    </div>  
    </div>
</body>
</html>