<?php
    session_start();
    include "../conexion.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistema de gestion de pedidos</title>
    <?php include "estructura/estructura.php"; ?>

</head>
<body>
<?php include "estructura/header.php"; ?>
    <div class="bloque">
        <div class = "subloque">

        <?php
            $busqueda = strtolower($_REQUEST['busqueda']);
            if(empty($busqueda)){
                header('location: listaProveedores.php');
                mysqli_close($conection);
            }
        ?>

        <h2>Lista de proveedores</h2>
        <form action="buscarProveedor.php" method="get">
            <div class="row">
                <div class="cell-md-6">
                    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar proveedor" value="<?php echo $busqueda; ?>">
                </div>
            </div>
        </form>
    <table class="table">
    <thead>
    <tr>
        <th data-sortable="true" data-sort-dir="asc">ID</th>
        <th data-sortable="true">Nombre</th>
        <th data-sortable="true">Telefono</th>
        <th data-sortable="true">Correo</th>
        <th >Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php
        
        $consulta_registros = mysqli_query($conection,"SELECT COUNT(*) AS registros FROM tendero WHERE  estado = 1");
        $resultado_registros = mysqli_fetch_array($consulta_registros);
        $registros = $resultado_registros['registros'];
        
        $por_pagina = 8;
        if(empty($_GET['pagina'])){
            $pagina = 1;
        }else{
            $pagina = $_GET['pagina'];
        }
        $desde = ($pagina-1) * $por_pagina;
        $total_paginas = ceil($registros / $por_pagina);
        


        $consulta = mysqli_query($conection,"SELECT *FROM proveedor
                                                WHERE 
                                                ( prov_id LIKE '%$busqueda%'
                                                        OR prov_nombre LIKE '%$busqueda%'
                                                        OR prov_telefono LIKE '%$busqueda%'
                                                        OR prov_email LIKE '%$busqueda%')
                                                        AND prov_estado = 1 ORDER BY prov_id ASC LIMIT $desde, $por_pagina");
        mysqli_close($conection);
        $resultado = mysqli_num_rows($consulta);
        if($resultado > 0 ){
            while($data = mysqli_fetch_array($consulta)){
    ?>
    <tr>
        <td><?php echo $data["prov_id"] ?></td>
        <td><?php echo $data["prov_nombre"] ?></td>
        <td><?php echo $data["prov_telefono"] ?></td>
        <td><?php echo $data["prov_email"] ?></td>
        <td>
        <div data-role="charms" data-position="top"><div>top</div></div>
            <a href="actualizarProveedores.php?id=<?php echo $data["ten_id"] ?>"><div class="mif-pencil fg-green"></div></a>
            <?php
            if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 ){ ?>
            |
            <a href="eliminarProveedores.php?id=<?php echo $data["prov_id"] ?>"><div class="mif-bin fg-red"></div></a>
            <?php } ?> 
        </td>
    </tr>
    <?php
            }
        }
    ?>
    </tbody>
    </table>
    <?php 
        if($registros !=0){
    ?>
    <div class="paginador">
        <ul>
        <?php
            if($pagina !=1){
        ?>
            <li><a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda; ?>">|<</a></li>
            <li><a href="?pagina=<?php echo $pagina-1; ?>&busqueda=<?php echo $busqueda; ?>"><<</a></li>
            <?php
        }
            for ($i=1; $i <= $total_paginas; $i++){
                if($i == $pagina){
                    echo '<li class="pagina">'.$i.'</li>';
                }else{
                    echo '<li><a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a></li>';
                }
            }
            if($pagina != $total_paginas){
            ?>
            <li><a href="?pagina=<?php echo $pagina+1; ?>&busqueda=<?php echo $busqueda; ?>">>></a></li>
            <li><a href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo $busqueda; ?>">>|</a></li>
        <?php } ?>
        </ul>
    </div>
<?php } ?>
    </div>
</div>
</body>
</html>