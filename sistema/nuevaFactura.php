<?php 
    session_start();
    include "../conexion.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Nueva Factura</title>
    <?php include "estructura/estructura.php"; ?>
</head>
<body>
<?php include "estructura/header.php"; ?>
<div class="bloque">
    <div class = "subloque">
    <h2><span class="icon"><span class="mif-files-empty fg-blue"></span>&nbsp;Nueva Factura</h2>
        <div class="card"></div>
        <h5>Datos del tendero:</h5>   
        <a href="#"><button class="button success btn_new_cliente"><span class="mif-add">&nbsp;</span>Nuevo tendero</button></a>
            <div class="alerta"><?php echo isset($alert)? $alert : ''; ?></div>
            <div class="card-content p-2">
            <br>
            <form name="form_new_cliente" id="form_new_cliente" class="datos">
                <input type="hidden" name="action" value="addTendero">
                <input type="hidden" name="idCliente" id="idCliente" value="">

                <div class="wd30">
                    <label for="cedula">Cédula:</label>
                    <input type="number" name="ced_cliente" id="ced_cliente" placeholder="Número de cédula del tendero" required  onkeypress="return solonumeros(event);"/>
                </div>
                <div class="wd30">
                    <label for="nombre">Nombres:</label>
                    <input type="text" name="nom_cliente" id="nom_cliente" placeholder="Nombre del tendero" disabled required />
                </div>
                <div class="wd30">
                    <label for="precio">Teléfono:</label>
                    <input type="number" name="tel_cliente" id="tel_cliente" placeholder="Teléfono del tendero" disabled required/>
                </div>
                <div class="wd100">
                    <label for="cantidad">Dirección:</label>
                    <input type="text" name="dir_cliente" id="dir_cliente" disabled required />
                </div>
                
                <div class="col-12 text-right wd100" id="div_registro_cliente">
                <br>
                    <button class="button success">Guardar</button>
                </div>
            </form>
            <div class="datos_venta">
                <h5>Datos de la factura:</h5> 
                <div class="datos">
                    <div class="wd50">
                        <label>Vendedor</label>
                        <p><?php echo $_SESSION['usuario'];?></p>
                    </div>
                    <div class="wd50">
                        <label>Acciones</label>
                        <div id="acciones_venta">
                            <button type="submit" class="button alert textcenter" id="btn_anular_venta">Anular</button>
                            <a href="#"><input type="button" class="button success"  id="btn_facturar_venta" value="Procesar" style="display: none;"></a>
                        </div>
                    </div>  
                </div> 
            </div>  
            </div>
            <div>
            <table class="table">
            <thead>
                <tr >
                    <th class="table_color" data-sortable="true" data-sort-dir="asc" width="100px">Código</th>
                    <th class="table_color" data-sortable="true">Nombre del producto</th>
                    <th class="table_color" data-sortable="true">Stock</th>
                    <th class="table_color" data-sortable="true" width="100px">Cantidad</th>
                    <th class="table_color" class="textright" data-sortable="true">Precio</th>
                    <th class="table_color" class="textright" data-sortable="true">Total</th>
                    <th class="table_color" >Acciones</th>
                </tr>
                </thead>
                <tbody>
    
                <tr>
                    <td><input type="text" name="txt_cod_producto" id="txt_cod_producto"  onkeypress="return solonumeros(event);"></td>
                    <td id="txt_descripcion">-</td>
                    <td id="txt_existencia">-</td>
                    <td><input type="text" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled onkeypress="return solonumeros(event);"></td>
                    <td id="txt_precio">00.00</td>
                    <td id="txt_precio_total">00.00</td>
                    <td><a href="#" id="add_product_venta" class="link_add"><input type="button" class="button success" value="Agregar"></a></td>
                </tr>
                 </tbody>
                 <thead>
                <tr>
                    <th class="table_color"  data-sortable="true" data-sort-dir="asc">Código</th>
                    <th class="table_color" data-sortable="true" colspan="2">Nombre del producto</th>
                    <th class="textright table_color" data-sortable="true">Cantidad</th>
                    <th class="textright table_color" data-sortable="true">Precio</th>
                    <th class="textright table_color" data-sortable="true">Total</th>
                    <th class="table_color">Acciones</th>
                </tr>
                </thead>
                <tbody id="detalle_venta">
                    <!-- AJAX -->
                </tbody>
                <tfoot id="detalle_totales">
                   <!-- AJAX -->
                </tfoot>
            </table>
            </div>
        </div>
    </div>  
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var usuarioid = '<?php echo $_SESSION['Usu_id']; ?>';
        serchForDetalle(usuarioid);
    });
</script>
</body>
</html>