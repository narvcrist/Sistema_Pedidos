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
        <h2>Lista de proveedores</h2>
        <form action="buscarProveedor.php" method="get">
            <div class="row">
                <div class="cell-md-6">
                    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar proveedores" required>
                </div>
            </div>
        </form>
        <br>
        <a href="registroProveedores.php"><button class="button success"><span class="mif-add">&nbsp;</span>Nuevo proveedor</button></a>
    <table class="table">
    <thead>
    <tr>
        <th data-sortable="true" data-sort-dir="asc">ID</th>
        <th data-sortable="true">Nombre</th>
        <th data-sortable="true">Télefono</th>
        <th data-sortable="true">Correo</th>
        <th data-sortable="true">Dirección</th>
        <th >Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php
        $consulta_registros = mysqli_query($conection,"SELECT COUNT(*) AS registros FROM proveedor WHERE prov_estado = 1");
        $resultado_registros = mysqli_fetch_array($consulta_registros);
        $registros = $resultado_registros['registros'];

        $por_pagina = 6;
        if(empty($_GET['pagina'])){
            $pagina = 1;
        }else{
            $pagina = $_GET['pagina'];
        }
        $desde = ($pagina-1) * $por_pagina;
        $total_paginas = ceil($registros / $por_pagina);
        $consulta = mysqli_query($conection,"SELECT *FROM proveedor 
                                             WHERE prov_estado = 1 ORDER BY prov_id ASC
                                            LIMIT $desde, $por_pagina ");
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
        <td><?php echo $data["prov_direccion"] ?></td>
        <td>
        <div data-role="charms" data-position="top"><div>top</div></div>
            <a href="actualizarProveedores.php?id=<?php echo $data["prov_id"] ?>"><div class="mif-pencil fg-green" data-role="hint" data-hint-text="Editar proveedor"></div></a>
        <?php
            if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 ){ ?>
            |
            <a href="eliminarProveedores.php?id=<?php echo $data["prov_id"] ?>"><div class="mif-bin fg-red" data-role="hint" data-hint-text="Eliminar proveedor"></div></a>
        <?php } ?>        
        </td>
    </tr>
    <?php
            }
        }
    ?>
    </tbody>
    </table>
    <div class="paginador">
        <ul>
        <?php
            if($pagina !=1){
        ?>
            <li><a href="?pagina=<?php echo 1; ?>"><span class="mif-first"></span></a></li>
            <li><a href="?pagina=<?php echo $pagina-1; ?>"><span class="mif-previous"></span></a></li>
            <?php
        }
            for ($i=1; $i <= $total_paginas; $i++){
                if($i == $pagina){
                    echo '<li class="pagina">'.$i.'</li>';
                }else{
                    echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
                }
            }
            if($pagina != $total_paginas){?>
            <li><a href="?pagina=<?php echo $pagina+1; ?>"><span class="mif-next"></span></a></li>
            <li><a href="?pagina=<?php echo $total_paginas; ?>"><span class="mif-last"></span></a></li>
        <?php } ?>
        </ul>
    </div>
    </div>
</div>
</body>
</html>