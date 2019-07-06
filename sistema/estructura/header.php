<?php 
    
    if(empty($_SESSION['active'])){
        header('location: ../');
    }
?>
<div class="pie" >SISTEMA DE PEDIDOS</div>
<div data-role="navview" class="side">
    <div class="navview-pane">
        <button class="pull-button">
            <span class="default-icon-menu"></span>
        </button>
        <ul class="navview-menu">
    <li class="item-header">Menú</li>
    <li>
        <a href="index.php">
            <span class="icon"><span class="mif-home fg-green"></span></span>
            <span class="caption">Inicio</span>
        </a>
    </li>
    <li>
        <a>
            <span class="icon"><span class="mif-user fg-green"></span></span>
            <span class="caption"><?php echo $_SESSION['usuario'];?></span>
        </a>
    </li>
    <!---<li>
        <a>
            <span class="caption"><?php echo $_SESSION['rol'];?></span>
        </a>
    </li>-->
    <li class="item-header">Operaciones</li>

    <?php
        if($_SESSION['rol'] ==1){
    ?>

    <li>
        <a href="#" class="dropdown-toggle">
            <span class="icon"><span class="mif-users fg-blue"></span></span>
            <span class="caption">Usuarios</span></a>
            <ul data-role="dropdown">
              <li><a href="registroUsuarios.php"><span class="icon"><span class="mif-add fg-green"></span></span> <span class="caption">Nuevo usuario</span></a></li>
              <li><a href="listaUsuarios.php"><span class="icon"><span class="mif-file-text fg-green"></span></span> <span class="caption">Usuarios</span></a></li>
            </ul>
    </li>

    <?php } ?>
    <?php
        if($_SESSION['rol'] ==1 || $_SESSION['rol'] ==2){
    ?>       
    <li>
        <a href="#" class="dropdown-toggle">
        <span class="icon"><span class="mif-truck fg-blue"></span></span>
            <span class="caption">Proveedores</span></a>
            <ul data-role="dropdown">
              <li><a href="registroProveedores.php"><span class="icon"><span class="mif-add fg-green"></span></span> <span class="caption">Nuevo proveedor</span></a></li>
              <li><a href="listaProveedores.php"><span class="icon"><span class="mif-file-text fg-green"></span></span> <span class="caption">Proveedores</span></a></li>
            </ul>      
    </li>
    <?php } ?>
    <li>
        <a href="#" class="dropdown-toggle">
            <span class="icon"><span class="mif-shop fg-blue"></span></span>
            <span class="caption">Tenderos</span></a>
            <ul data-role="dropdown">
              <li><a href="registroTenderos.php"><span class="icon"><span class="mif-add fg-green"></span></span> <span class="caption">Nuevo tendero</span></a></li>
              <li><a href="listaTenderos.php"><span class="icon"><span class="mif-file-text fg-green"></span></span> <span class="caption">Tenderos</span></a></li>
            </ul>
    </li>
    <li>
        <a href="#" class="dropdown-toggle">
            <span class="icon"><span class="mif-shopping-basket fg-blue"></span></span>
            <span class="caption">Productos</span></a>
            <ul data-role="dropdown">
              <li><a href="registroProductos.php"><span class="icon"><span class="mif-add fg-green"></span></span> <span class="caption">Nuevo producto</span></a></li>
              <li><a href="listaProductos.php"><span class="icon"><span class="mif-file-text fg-green"></span></span> <span class="caption">Productos</span></a></li>
            </ul>
    </li>
    <li>
        <a href="#" class="dropdown-toggle">
            <span class="icon"><span class="mif-files-empty fg-blue"></span></span>
            <span class="caption">Facturas</span></a>
            <ul data-role="dropdown">
              <li><a href="nuevaFactura.php"><span class="icon"><span class="mif-add fg-green"></span></span> <span class="caption">Nuevo factura</span></a></li>
              <li><a href="listaFacturas.php"><span class="icon"><span class="mif-file-text fg-green"></span></span> <span class="caption">Facturas</span></a></li>
            </ul>
    </li>
    <br>
    <br>
    <li>
        <a href="salir.php" class="close" >
            <span class="icon"><span class="mif-exit fg-red"></span></span>
            <span class="caption">Salir</span>
        </a>
    </li>	
    </ul>
    </div> 
    
</div>


