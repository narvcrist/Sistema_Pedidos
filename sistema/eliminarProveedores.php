<?php
    session_start();
    if($_SESSION['rol'] !=1 AND $_SESSION['rol'] !=2 ){
        header('location: ./');
    }
    include "../conexion.php";
    if(!empty($_POST)){
        
        $idProveedor = $_POST['idProveedor']; 
        //$consulta = mysqli_query($conection, " DELETE FROM usuario WHERE usu_id = $idTendero");
        $consulta = mysqli_query($conection, " UPDATE proveedor SET prov_estado = 0 WHERE prov_id = $idProveedor");
        if($consulta){
            header('location: listaProveedores.php');
            mysqli_close($conection);
        }else{
            echo "Error al eliminar";
        }
    }
    if(empty($_REQUEST['id'])){
        header('location: listaProveedores.php');
        mysqli_close($conection);
    }else{
       
       $idProveedor = $_REQUEST['id'];
       $consulta = mysqli_query($conection, "SELECT * FROM proveedor
                                            WHERE prov_id = $idProveedor");
        mysqli_close($conection);
        $resultado = mysqli_num_rows($consulta);
        if($resultado > 0){
            while($data = mysqli_fetch_array($consulta)){
                
                $nombre = $data['prov_nombre'];
                $telefono = $data['prov_telefono'];
                $correo = $data['prov_email'];
    
            }
        }else{
            header('location: listaProveedores.php');
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Eliminar proveedor</title>
    <?php include "estructura/estructura.php"; ?>

</head>
<body>
<?php include "estructura/header.php"; ?>
    <div class="bloque">
  
        <div class = "publicar">
        <div class="dialog">
    <div class="dialog-title">¿Estas seguro de eliminar al proveedor?</div>
    <div class="dialog-content">
            <p><strong>Nombre: </strong><span><?php echo $nombre; ?></span></p>
            <p><strong>Teléfono: </strong><span><?php echo $telefono; ?></span></p>
            <p><strong>Correo: </strong><span><?php echo $correo; ?></span></p>
            
    </div>
    <div class="dialog-actions">
        <form action="" method="post">
            <input type="hidden" name="idProveedor" value="<?php echo $idProveedor; ?>">
            <div class="col-12 text-right">
            <a href="listaProveedores.php"><input type="button" class="button" value="Cerrar"></a>
            <button class="button alert">Eliminar</button></div>
            
        </form>
    </div>
</div>

    </div>
</div>

</body>
</html>