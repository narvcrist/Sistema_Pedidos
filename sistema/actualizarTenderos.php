<?php
    session_start();
     
    include "../conexion.php";
    if(!empty($_POST)){
        $alert='';
        if(empty($_POST['nombre']) || empty($_POST['cedula']) || empty($_POST['telefono'])
        || empty($_POST['direccion'])){
           
            $alert='<p class="mensage">Todos los campos son abligatorios</p>';
        }else{
            
            $idTendero= $_POST['idTendero'];
            $nombre =  $_POST['nombre'];
            $cedula =  $_POST['cedula'];
            $direccion = $_POST['direccion'];
            $telefono =  $_POST['telefono'];

            $resultado = 0;
            if(is_numeric($cedula)){
                $consulta= mysqli_query($conection,"SELECT *FROM tendero
                                                    WHERE  (ten_cedula = '$cedula' AND ten_id != $idTendero)
                                                    ");
                 $resultado = mysqli_fetch_array($consulta);
                // $resultado = count($resultado);
            }   
            if($resultado > 0){
                $alert='<p class="mensage">El tendero ya existe</p>';
            }else{
                    $consulta = mysqli_query($conection,"UPDATE tendero
                                                        SET ten_nombre = '$nombre', ten_cedula = '$cedula', ten_direccion = '$direccion', ten_telefono = '$telefono'
                                                        WHERE ten_id = $idTendero");
                }
                if($consulta){
                    $alert='<p class="mensage">Tendero actualizado con exito </p>';
                }else{
                    $alert='<p class="mensage">Ocurrio un error</p>';
                }
            }
        }
    
    if(empty($_REQUEST['id'])){
        header('location: listaTenderos.php');
    }
    $idTendero= $_REQUEST['id'];
    $consulta = mysqli_query($conection,"SELECT *FROM tendero
                                        WHERE ten_id = $idTendero");
    $resultado = mysqli_num_rows($consulta);
    if($resultado == 0){
        header('location : listaTenderos.php');
    }else{
        $option = '';
        while($data = mysqli_fetch_array($consulta)){
            $idTendero = $data['ten_id'];
            $nombre = $data['ten_nombre'];
            $cedula = $data['ten_cedula'];
            $direccion = $data['ten_direccion'];
            $telefono = $data['ten_telefono'];
           
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Actualizar usuario</title>
  <?php include "estructura.php"; ?>
</head>
<body>
<?php include "header.php"; ?>
    <div class="bloque">
    <div class = "publicar">
    <h2>Actualizar tendero</h2>
        <div class="card">
            
            </h5></strong></div>
            <div class="alerta"><?php echo isset($alert)? $alert : ''; ?></div>
            <div class="card-content p-2">
            <form class="ingresar" action="" method="post">
                    <div class="form-group">
                        <label for="nombre">Nombres y apellidos:</label>
                        <input type="hidden" name="idTendero" value="<?php echo $idTendero; ?>">
                        <input type="text" name="nombre" placeholder="Ingresa tus nombres completos" value="<?php echo $nombre; ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="cedula">Cédula:</label>
                        <input type="text" name="cedula" placeholder="Ingresa el número de cédula" value="<?php echo $cedula; ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="number" name="telefono" placeholder="Ingrese el número telefónico" value="<?php echo $telefono; ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección:</label>
                        <input type="text" name="direccion" placeholder="Ingrese la dirección"value="<?php echo $direccion; ?>"/>
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
