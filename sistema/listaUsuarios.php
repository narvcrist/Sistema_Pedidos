<?php
session_start();
if($_SESSION['rol'] !=1){
    header('location: ./');
}
    include "../conexion.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistema de gestion de pedidos</title>
    <?php include "estructura.php"; ?>

</head>
<body>
<?php include "header.php"; ?>
    <div class="bloque">
        <div class = "subloque">
        <h2>Lista de usuarios</h2>
        <form action="buscarUsuario.php" method="get">
            <div class="row">
                <div class="cell-md-6">
                    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar usuarios" required>
                </div>
            </div>
        </form>
    <table class="table">
    <thead>
    <tr>
        <th data-sortable="true" data-sort-dir="asc">ID</th>
        <th data-sortable="true">Nombre</th>
        <th data-sortable="true">Correo</th>
        <th data-sortable="true">Rol</th>
        <th >Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php
        $consulta_registros = mysqli_query($conection,"SELECT COUNT(*) AS registros FROM usuario WHERE estado = 1");
        $resultado_registros = mysqli_fetch_array($consulta_registros);
        $registros = $resultado_registros['registros'];

        $por_pagina = 7;
        if(empty($_GET['pagina'])){
            $pagina = 1;
        }else{
            $pagina = $_GET['pagina'];
        }
        $desde = ($pagina-1) * $por_pagina;
        $total_paginas = ceil($registros / $por_pagina);

        $consulta = mysqli_query($conection,"SELECT u.usu_id, u.usu_correo, u.usu_nombre, u.usu_usuario, r.rol_nombre 
                                            FROM usuario u 
                                            INNER JOIN rol r 
                                            ON u.id_rol = r.rol_id 
                                            WHERE estado = 1 ORDER BY usu_id ASC
                                            LIMIT $desde, $por_pagina ");
        mysqli_close($conection);
        $resultado = mysqli_num_rows($consulta);
        if($resultado > 0 ){
            while($data = mysqli_fetch_array($consulta)){
    ?>
    <tr>
        <td><?php echo $data["usu_id"] ?></td>
        <td><?php echo $data["usu_nombre"] ?></td>
        <td><?php echo $data["usu_correo"] ?></td>
        <td><?php echo $data["rol_nombre"] ?></td>
        <td>
        <div data-role="charms" data-position="top"><div>top</div></div>
            <a href="actualizarUsuarios.php?id=<?php echo $data["usu_id"] ?>"><div class="mif-pencil fg-green"></div></a>
            <?php if($data['usu_id']!=1){?>
            |
            <a href="eliminarUsuarios.php?id=<?php echo $data["usu_id"] ?>"><div class="mif-bin fg-red"></div></a>
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
            <li><a href="?pagina=<?php echo 1; ?>">|<</a></li>
            <li><a href="?pagina=<?php echo $pagina-1; ?>"><<</a></li>
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
            <li><a href="?pagina=<?php echo $pagina+1; ?>">>></a></li>
            <li><a href="?pagina=<?php echo $total_paginas; ?>">>|</a></li>
        <?php } ?>
        </ul>
    </div>
    </div>
</div>
</body>
</html>

