<?php
    session_start();
    include "../conexion.php";
    if(!empty($_POST)){
        $alert='';
        if(empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['correo'])
        || empty($_POST['direccion'])){
           
            $alert='<p class="mensage">Todos los campos son abligatorios</p>';
        }else{
            $nombre = $_POST['nombre'];
            $telefono = $_POST['telefono'];
            $correo = $_POST['correo'];
            $direccion = $_POST['direccion'];
            $consulta= mysqli_query($conection,"SELECT *FROM proveedor WHERE prov_email = '$correo'");
            $resultado = mysqli_fetch_array($consulta);
            if($resultado > 0){
                $alert='<p class="mensage">El proveedor ya existe</p>';
            }else{
                $consulta_insert = mysqli_query($conection,"INSERT INTO proveedor(prov_nombre, prov_telefono, prov_email, prov_direccion)
                VALUES('$nombre','$telefono','$correo','$direccion')");
                if($consulta_insert){
                    $alert='<p class="mensage">Proveedor registrado con exito </p>';
                }else{
                    $alert='<p class="mensage">Ocurrio un error</p>';
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Registrar proveedor</title>
  <?php include "estructura/estructura.php"; ?>
</head>
<body>
<?php include "estructura/header.php"; ?>
    <div class="bloque">
    <div class = "publicar">
    <h2>Registrar proveedor</h2>
        <div class="card">
            
            </h5></strong></div>
            <div class="alerta"><?php echo isset($alert)? $alert : ''; ?></div>
            <div class="card-content p-2">
                <form class="ingresar" action="" method="post" data-role="validator" action="javascript:">
                    <div class="form-group">
                        <label for="nombre">Nombre del proveedor:</label>
                        <input type="text" name="nombre" placeholder="Ingresa el nombre del proveedor"  data-validate="required"/>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="number" name="telefono" placeholder="Ingrese el número telefónico" />
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo electrónico:</label>
                        <input type="email" name="correo" placeholder="Correo electrónico" data-validate="required email"/>
                        <span class="invalid_feedback">
                            Ingresa un correo electrónico válido
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección:</label>
                        <input type="text" name="direccion" placeholder="Ingrese la dirección"  data-validate="required"/>
                    </div>
                    <br>
                    <div class="col-12 text-right">
                        <button class="button success">Registrar</button>
                    </div>
                </form>  
            </div>
        </div>
    </div>  
    </div>
</body>
</html>