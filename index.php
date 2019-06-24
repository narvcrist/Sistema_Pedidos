<?php
	
	$alert='';
	session_start();
	if(!empty($_SESSION['active']))
	{
		header('location: sistema/');
	}else{
		if(!empty($_POST))
		{
			if(empty($_POST['usuario']) || empty($_POST['clave']))
			{
				$alert='Ingrese sus credenciales';
			
			}else{
				require_once('conexion.php');
			
			$user= $_POST['usuario'];
			$pass= $_POST['clave'];
			//$query= mysqli_query($conection,"SELECT *FROM usuario WHERE usu_usuario= '$user' AND usu_password= $pass'");
			//$result = mysqli_num_rows($query);
			//	if($result > 0){
			
			$conection=mysqli_connect("remotemysql.com","vN3T9N3HJT","65oLG7iRbU","vN3T9N3HJT");
			$consulta="SELECT * FROM usuario WHERE usu_correo='$user' and usu_password='$pass'";
			
			$resultado=mysqli_query($conection,$consulta);
			mysqli_close($conection);
			$filas=mysqli_num_rows($resultado);
			if($resultado > 0)
				{
				$data = mysqli_fetch_array($resultado);
				$_SESSION['active']= true;
				$_SESSION['Usu_id']= $data['usu_id'];
				$_SESSION['usuario']= $data['usu_nombre'];
				$_SESSION['user']= $data['usu_usuario'];
				$_SESSION['rol']= $data['id_rol'];
				header('location: sistema/');
				}else{
					$alert='Correo o clave estan incorrectas';
					session_destroy();
			}
		}	
		
	}
}	
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Sist-Pedidos</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/estilo.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="pie" >SISTEMA DE PEDIDOS</div>
	<form class="ingresar" action="" method="post">
        <h2>Inicia sesión</h2> 
        <input  class="campo1" id="email" type="email" name="usuario" placeholder="Usuario" >
        <input  class="campo" id="clave" type="password" name="clave" placeholder="Contraseña" >
        <br>
		<div class="alert"><?php echo isset($alert)? $alert :''; ?></div>
        <input type="submit" value="Iniciar sesión">
    </form>
</body>
</html>