<?php
    session_start();
    if($_SESSION['rol'] !=1 AND $_SESSION['rol'] !=2 ){
        header('location: ./');
    }
    include "../conexion.php";

    if(!empty($_POST)){
        
        $idTendero = $_POST['idTendero']; 

        //$consulta = mysqli_query($conection, " DELETE FROM usuario WHERE usu_id = $idTendero");
        $consulta = mysqli_query($conection, " UPDATE tendero SET estado = 0 WHERE ten_id = $idTendero");
        if($consulta){
            header('location: listaTenderos.php');
            mysqli_close($conection);
        }else{
            echo "Error al eliminar";
        }
    }

    if(empty($_REQUEST['id'])){
        header('location: listaTenderos.php');
        mysqli_close($conection);
    }else{
       
       $idTendero = $_REQUEST['id'];

       $consulta = mysqli_query($conection, "SELECT * FROM tendero
                                            WHERE ten_id = $idTendero");
        mysqli_close($conection);
        $resultado = mysqli_num_rows($consulta);

        if($resultado > 0){
            while($data = mysqli_fetch_array($consulta)){
                
                $nombre = $data['ten_nombre'];
                $cedula = $data['ten_cedula'];
                $telefono = $data['ten_telefono'];
            }
        }else{
            header('location: listaTenderos.php');
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Eliminar tendero</title>
    <?php include "estructura/estructura.php"; ?>

</head>
<body>
<?php include "estructura/header.php"; ?>
    <div class="bloque">
  
        <div class = "publicar">
        <div class="dialog">
    <div class="dialog-title">¿Estas seguro de eliminar al tendero?</div>
    <div class="dialog-content">
            <p><strong>Nombre: </strong><span><?php echo $nombre; ?></span></p>
            <p><strong>Cédula: </strong><span><?php echo $cedula; ?></span></p>
            <p><strong>Telefono: </strong><span><?php echo $telefono; ?></span></p>
    </div>
    <div class="dialog-actions">
        <form action="" method="post">
            <input type="hidden" name="idTendero" value="<?php echo $idTendero; ?>">
            <div class="col-12 text-right">
            <a href="listaTenderos.php"><input type="button" class="button" value="Cerrar"></a>
            <button class="button alert">Eliminar</button>
        </div>    
        </form>
    </div>
</div>

    </div>
</div>

</body>
</html>




