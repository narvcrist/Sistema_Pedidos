<?php 
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistema de gestión de pedidos</title>
    <?php include "estructura/estructura.php"; ?>

</head>
<body>
<?php include "estructura/header.php"; ?>
    <div class="bloque">
        <h4>Bienvenido&nbsp;<?php echo $_SESSION['usuario'];?></h4>
    </div>

</body>
</html>