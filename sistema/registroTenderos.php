<?php
    session_start();
    include "../conexion.php";
    if(!empty($_POST)){
        $alert='';
        if(empty($_POST['nombre']) || empty($_POST['cedula']) || empty($_POST['telefono'])
        || empty($_POST['direccion'])){
           
            $alert='<p class="mensage">Todos los campos son abligatorios</p>';
        }else{
            $nombre = $_POST['nombre'];
            $cedula = $_POST['cedula'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $usuarioId = $_SESSION['Usu_id'];

            $resultado = 0;
            if(is_numeric($cedula)){

                $consulta= mysqli_query($conection,"SELECT *FROM tendero WHERE  ten_cedula = '$cedula'");
                $resultado = mysqli_fetch_array($consulta);

            }
            if($resultado > 0){
                $alert='<p class="mensage">La cédula ya existe</p>';
            }else{
                $consulta_insert = mysqli_query($conection,"INSERT INTO tendero(ten_nombre, ten_cedula, ten_direccion, ten_telefono, ten_idUsuario)
                VALUES('$nombre','$cedula','$direccion','$telefono','$usuarioId')");
             
                if($consulta_insert){
                    $alert='<p class="mensage">Tendero registrado con exito </p>';
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
  <title>Registrar tendero</title>
  <?php include "estructura/estructura.php"; ?>
</head>
<body>
<?php include "estructura/header.php"; ?>
    <div class="bloque">
    <div class = "publicar">
    <h2>Registrar tendero</h2>
        <div class="card">
            
            </h5></strong></div>
            <div class="alerta"><?php echo isset($alert)? $alert : ''; ?></div>
            <div class="card-content p-2">
                <form class="ingresar" action="" method="post">
                    <div class="form-group">
                        <label for="nombre">Nombres y apellidos:</label>
                        <input type="text" name="nombre" placeholder="Ingresa tus nombres completos"/>
                    </div>
                    <div class="form-group">
                        <label for="cedula">Cédula:</label>
                        <input type="text" name="cedula" placeholder="Ingresa el número de cédula"/>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="number" name="telefono" placeholder="Ingrese el número telefónico"/>
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección:</label>
                        <input type="text" name="direccion" placeholder="Ingrese la dirección"/>
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

