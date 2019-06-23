<?php
    session_start();
    if($_SESSION['rol'] !=1){
        header('location: ./');
    }
    include "../conexion.php";

    if(!empty($_POST)){
        if($_POST['idUsuario']==1){
            header('location: listaUsuarios.php');
            exit;
        }
        $idUsuario = $_POST['idUsuario']; 

        //$consulta = mysqli_query($conection, " DELETE FROM usuario WHERE usu_id = $idUsuario");
        $consulta = mysqli_query($conection, " UPDATE usuario SET estado = 0 WHERE usu_id = $idUsuario");
        if($consulta){
            header('location: listaUsuarios.php');
            mysqli_close($conection);
        }else{
            echo "Error al eliminar";
        }
    }

    if(empty($_REQUEST['id'])){
        header('location: listaUsuarios.php');
        mysqli_close($conection);
    }else{
       
       $idUsuario = $_REQUEST['id'];

       $consulta = mysqli_query($conection, "SELECT u.usu_nombre, u.usu_usuario, r.rol_nombre
                                            FROM usuario u
                                            INNER JOIN rol r
                                            ON u.id_rol = r.rol_id
                                            WHERE u.usu_id = $idUsuario");
        mysqli_close($conection);
        $resultado = mysqli_num_rows($consulta);

        if($resultado > 0){
            while($data = mysqli_fetch_array($consulta)){
                
                $nombre = $data['usu_nombre'];
                $usuario = $data['usu_usuario'];
                $rol = $data['rol_nombre'];
            }
        }else{
            header('location: listaUsuarios.php');
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Eliminar usuario</title>
    <?php include "estructura/estructura.php"; ?>

</head>
<body>
<?php include "estructura/header.php"; ?>
    <div class="bloque">
  
        <div class = "publicar">
        <div class="dialog">
    <div class="dialog-title">¿Estas seguro de eliminar al usuario?</div>
    <div class="dialog-content">
            <p><strong>Nombre: </strong><span><?php echo $nombre; ?></span></p>
            <p><strong>Usuario: </strong><span><?php echo $usuario; ?></span></p>
            <p><strong>Rol de usuario: </strong><span><?php echo $rol; ?></span></p>
    </div>
    <div class="dialog-actions">
        <form action="" method="post">
            <input type="hidden" name="idUsuario" value="<?php echo $idUsuario; ?>">
            <div class="col-12 text-right">
            <a href="listaUsuarios.php"><input type="button" class="button" value="Cerrar"></a>
            <button class="button alert">Eliminar</button></div>
            
        </form>
    </div>
</div>

    </div>
</div>

</body>
</html>




