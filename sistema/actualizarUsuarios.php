<?php
    session_start();
    if($_SESSION['rol'] !=1){   
        header('location: ./');
    }   
    include "../conexion.php";
    if(!empty($_POST)){
        $alert='';
        if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario'])
        || empty($_POST['rol'])){
           
            $alert='<p class="mensage">Todos los campos son abligatorios</p>';
        }else{
            
            $idUsuario= $_POST['idUsuario'];
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $user = $_POST['usuario'];
            $clave = $_POST['clave'];
            $rol = $_POST['rol'];

            $consulta= mysqli_query($conection,"SELECT *FROM usuario 
                                                        WHERE  (usu_usuario = '$user' AND usu_id != $idUsuario)
                                                        OR (usu_correo = '$correo' AND usu_id != $idUsuario) ");
            $resultado = mysqli_fetch_array($consulta);

            if($resultado > 0){
                $alert='<p class="mensage">El usuario ya existe</p>';
            }else{
                if(empty($_POST['clave'])){
                    $consulta = mysqli_query($conection,"UPDATE usuario
                                                        SET usu_nombre = '$nombre', usu_correo = '$correo', usu_usuario = '$user', id_rol = '$rol'
                                                        WHERE usu_id = $idUsuario");
                }else{
                    $consulta = mysqli_query($conection,"UPDATE usuario
                                                        SET usu_nombre = '$nombre', usu_correo = '$correo', usu_usuario = '$user', usu_password = '$clave', id_rol = '$rol'
                                                        WHERE usu_id = $idUsuario");
                    }
                }

                if($consulta){
                    $alert='<p class="mensage">Usuario actualizado con exito </p>';
                }else{
                    $alert='<p class="mensage">Ocurrio un error</p>';
                }
            }
        }
    
    if(empty($_GET['id'])){
        header('location: listaUsuarios.php');
    }
    $idUsuario= $_GET['id'];
    $consulta = mysqli_query($conection,"SELECT u.usu_id, u.usu_correo, u.usu_nombre, u.usu_usuario, (u.id_rol) AS rol_id, (r.rol_nombre) AS rol 
                                        FROM usuario u 
                                        INNER JOIN rol r 
                                        ON u.id_rol = r.rol_id
                                        WHERE usu_id = $idUsuario");
    $resultado = mysqli_num_rows($consulta);
    if($resultado == 0){
        header('location : listaUsuarios.php');
    }else{
        $option = '';
        while($data = mysqli_fetch_array($consulta)){
            $idUsuario = $data['usu_id'];
            $nombre = $data['usu_nombre'];
            $correo = $data['usu_correo'];
            $usuario = $data['usu_usuario'];
            $idRol = $data['rol_id'];
            $rol = $data['rol'];
        
            if($idRol == 1){
                $option = '<option value="'.$idRol.'" select>'.$rol.'</option>';
            }else if($idRol == 2){
                $option = '<option value="'.$idRol.'" select>'.$rol.'</option>';
            }else if ($idRol == 3){
                $option = '<option value="'.$idRol.'" select>'.$rol.'</option>';
            }
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Actualizar usuario</title>
  <?php include "estructura/estructura.php"; ?>
</head>
<body>
<?php include "estructura/header.php"; ?>
    <div class="bloque">
    <div class = "publicar">
    <h2>Actualizar usuario</h2>
        <div class="card">
            
            </h5></strong></div>
            <div class="alerta"><?php echo isset($alert)? $alert : ''; ?></div>
            <div class="card-content p-2">
                <form class="ingresar" action="" method="post">
                    <div class="form-group">
                        <label for="nombre">Nombres y apellidos:</label>
                        <input type="hidden" name="idUsuario" value="<?php echo $idUsuario; ?>">
                        <input type="text" name="nombre" placeholder="Ingresa tus nombres completos" value="<?php echo $nombre; ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo eléctronico:</label>
                        <input type="email" name="correo" placeholder="Correo eléctronico" value="<?php echo $correo; ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="usuario">Nombre de usuario:</label>
                        <input type="text" name="usuario" placeholder="Usuario" value="<?php echo $usuario; ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="clave">Contraseña:</label>
                        <input type="password" name="clave" placeholder="Contraseña"/>
                    </div>
                    <div class="form-group">
                        <label for="rol">Rol de usuario:</label>

                        <?php
                            $consulta_rol = mysqli_query($conection, "SELECT *FROM rol");
                            $resultado_rol = mysqli_num_rows($consulta_rol);
                        ?>
                        <select name="rol" id='rol' class="unaVez">

                            <?php
                                echo $option;
                                if ($resultado_rol > 0){
                                    while ($rol = mysqli_fetch_array($consulta_rol)){
                            ?>
                                    <option value="<?php echo $rol['rol_id']; ?>"><?php echo $rol['rol_nombre'] ?></option>
                            <?php    
                                    }
                                }
                            ?>
                        </select>
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

