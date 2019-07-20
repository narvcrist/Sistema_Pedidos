$(document).ready(function(){

    //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------
    $("#foto").on("change",function(){
    	var uploadFoto = document.getElementById("foto").value;
        var foto       = document.getElementById("foto").files;
        var nav = window.URL || window.webkitURL;
        var contactAlert = document.getElementById('form_alert');
        
            if(uploadFoto !='')
            {
                var type = foto[0].type;
                var name = foto[0].name;
                if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png')
                {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';                        
                    $("#img").remove();
                    $(".delPhoto").addClass('notBlock');
                    $('#foto').val('');
                    return false;
                }else{  
                        contactAlert.innerHTML='';
                        $("#img").remove();
                        $(".delPhoto").removeClass('notBlock');
                        var objeto_url = nav.createObjectURL(this.files[0]);
                        $(".prevPhoto").append("<img id='img' src="+objeto_url+">");
                        $(".upimg label").remove();
                        
                    }
              }else{
              	alert("No selecciono foto");
                $("#img").remove();
              }              
    });

    $('.delPhoto').click(function(){
    	$('#foto').val('');
    	$(".delPhoto").addClass('notBlock');
        $("#img").remove();
        
        if($("#foto_actual") && $("#foto_remove")){
            $("#foto_remove").val('img_producto.png');
        }

    });
    //Modal
   /* $('.add_product').click(function(e){
        e.preventDefault();
        var producto = $(this).attr('product');
        var action = 'infoProducto';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action, producto:producto},
            
            success: function(response){
               
                if(response != 'error'){
                    var info = JSON.parse(response);
                    $('#producto_id').val(info.pro_id);
                    $('.nameProducto').html(info.pro_descripcion);
                }
            },
            error: function(error){
                console.log(error);
            }
        });

        $('.modal').fadeIn();
    });
*/
    //Modal para eliminar productos
    $('.del_product').click(function(e){
        e.preventDefault();
        var producto = $(this).attr('product');
        var action = 'infoProducto';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action, producto:producto},
            
            success: function(response){
               
                if(response != 'error'){
                    var info = JSON.parse(response);
                   // $('#producto_id').val(info.pro_id);
                //$('.nameProducto').html(info.pro_descripcion);

                $('.cuerpoModal').html('<form name="form_del_product" id="form_del_product" action="" method="post" onsubmit="event.preventDefault(); delProduct();">'+
                '<h3><span class="mif-shopping-basket2">&nbsp;</span>Eliminar producto</h3> '+
                
                '<p class="dialog-title">¿Estas seguro de eliminar al producto?</p>'+
                
                '<h4 class="nameProducto">'+info.pro_descripcion+'</h4>'+
            
                '<input type="hidden" name="producto_id" id="producto_id" value="'+info.pro_id+'" required="">'+
                '<input type="hidden" name="action" value="delProduct" required="">'+
                '<div class="alertAddProduct"></div>'+

                '<div class="col-12 text-right">'+
                '<a href="#" class="closeModal" onclick="closeModal();"><input type="button" class="button" value="Cerrar"></a>'+
                '&nbsp;'+
                '<button type="submit" class="button alert eliminar">Eliminar</button>'+
                '</div>'+

                
                '</form>')
                }
            },
            error: function(error){
                console.log(error);
            }
        });

        $('.modal').fadeIn();
    });

    //Activar inputs en nueva factura para crear clientes
    $('.btn_new_cliente').click(function(e){
        e.preventDefault();
        $('#ced_cliente').removeAttr('disabled');
        $('#nom_cliente').removeAttr('disabled');
        $('#tel_cliente').removeAttr('disabled');
        $('#dir_cliente').removeAttr('disabled');

        $('#div_registro_cliente').slideDown();
    });
    //Buscar tendero
    $('#ced_cliente').keyup(function(e){
        e.preventDefault();

        var cl = $(this).val();
        var action = 'searchCliente';

        $.ajax({
            url: 'ajax.php',
            type: "POST",
            async: true,
            data: {action : action, cliente : cl},

            success: function(response){
                if(response == 0){
                    $('#idCliente').val(''); //Limpío los campos
                    $('#nom_cliente').val('');
                    $('#tel_cliente').val('');
                    $('#dir_cliente').val('');
                    //Mostrar boton nuevo tendero
                    $('.btn_new_cliente').slideDown();
                }else{
                    var data = $.parseJSON(response); //Paeso el json a objeto
                    $('#idCliente').val(data.ten_id);
                    $('#nom_cliente').val(data.ten_nombre);
                    $('#tel_cliente').val(data.ten_telefono);
                    $('#dir_cliente').val(data.ten_direccion);
                    //Ocultar boton nuevo tendero
                    $('.btn_new_cliente').slideUp();

                    //Bloqueo de campos
                    $('#nom_cliente').attr('disabled', 'disabled');
                    $('#tel_cliente').attr('disabled', 'disabled');
                    $('#dir_cliente').attr('disabled', 'disabled');

                    //Ocultar boton guardar
                    $('#div_registro_cliente').slideUp();

                }
            },
            error: function(error){

            }
        });
    });

    //Crear tendero desde nueva factura
    $('#form_new_cliente').submit(function(e){
        e.preventDefault();

        $.ajax({
            url: 'ajax.php',
            type: "POST",
            async: true,
            data: $('#form_new_cliente').serialize(),

            success: function(response){
               
                if(response != 'error'){
                    //Agregamos id al input hidden
                    $('#idCliente').val(response);
                    //Bloqueo de campos
                    $('#nom_cliente').attr('disabled', 'disabled');
                    $('#tel_cliente').attr('disabled', 'disabled');
                    $('#dir_cliente').attr('disabled', 'disabled');

                    //Ocultar boton nuevo tendero
                    $('.btn_new_cliente').slideUp();
                    //Ocultar boton guardar
                    $('#div_registro_cliente').slideUp();

                }
            },
            error: function(error){

            }
        });
    });

    // Buscar producto
    $('#txt_cod_producto').keyup(function(e){
        e.preventDefault();

        var producto = $(this).val();
        var action = 'infoProducto'

            if(producto != ''){
                $.ajax({
                    url: 'ajax.php',
                    type: "POST",
                    async: true,
                    data: {action : action, producto : producto},
        
                    success: function(response){
                      if(response != 'error'){

                        var info = JSON.parse(response);
                        $('#txt_descripcion').html(info.pro_descripcion);
                        $('#txt_existencia').html(info.pro_stock);
                        $('#txt_cant_producto').val('1');
                        $('#txt_precio').html(info.pro_precio);
                        $('#txt_precio_total').html(info.pro_precio);

                        // Activar cantidad
                        $('#txt_cant_producto').removeAttr('disabled');

                        // Mostrar boton AGREGAR
                        $('#add_product_venta').slideDown();
                      }else{
                        $('#txt_descripcion').html('-');
                        $('#txt_existencia').html('-');
                        $('#txt_cant_producto').html('0');
                        $('#txt_precio').html('00.00');
                        $('#txt_precio_total').html('00.00');

                        // Bloquear cantidad
                        $('#txt_cant_producto').attr('disabled', 'disabled');
                        //Ocultar boton guardar
                        $('#add_product_venta').slideUp();
                      }
                    },
                    error: function(error){
                    }
                });
            } 
    });

    // Calcular el precio total del producto de acuerdo a la cantidad
    $('#txt_cant_producto').keyup(function(e){ // keyup evento q se ejecuta cuando se suelta la tecla
        e.preventDefault();

        var precio_total = $(this).val() * $('#txt_precio').html(); // Guardo en una variable la operacion de multiplicacion
        var stock = parseInt($('#txt_existencia').html());

        $('#txt_precio_total').html(precio_total .toFixed(2));

        // Ocultar boton AGREGAR si la cantodad es menor a 1
        if( ($(this).val() <1 || isNaN($(this).val())) || ($(this).val() > stock) ){
            $('#add_product_venta').slideUp();
        }else{
            $('#add_product_venta').slideDown();
        }
    });

    // Agregar productos al detalle
    $('#add_product_venta').click(function(e){
        e.preventDefault();

        if($('#txt_cant_producto').val() >0){

            var codproducto = $('#txt_cod_producto').val();
            var cantidad = $('#txt_cant_producto').val();
            var action = 'addProductoDetalle';

            $.ajax({
                url : 'ajax.php',
                type : "POST",
                async : true,
                data : {action : action , producto : codproducto, cantidad : cantidad},

                success: function(response){
                    
                    if(response != 'error'){

                        var info = JSON.parse(response);
                        $('#detalle_venta').html(info.detalle);
                        $('#detalle_totales').html(info.totales);

                        $('#txt_cod_producto').val('');
                        $('#txt_descripcion').html('-');
                        $('#txt_existencia').html('-');
                        $('#txt_cant_producto').val('0');
                        $('#txt_precio').html('00.00');
                        $('#txt_precio_total').html('00.00');

                        // Bloquear input de cantidad
                        $('#txt_cant_producto').attr('disabled', 'disabled');

                        // Ocultar boton AGREGAR
                        $('#add_product_venta').slideUp();
                    }else{
                        console.log('No hay datos');                    
                    }
                    viewProcesar();
                },
                error: function(error){

                }
            });
        }
    });

    // Funcion para el boton ANULAR venta
    $('#btn_anular_venta').click(function(e){
        e.preventDefault();

        var rows = $('#detalle_venta tr').length;
        if(rows > 0){
            var action = 'anularVenta';

            $.ajax({
                url : 'ajax.php',
                type: "POST",
                async : true,
                data : {action : action},

                success: function(response){
                    
                    if(response != 'error'){
                        location.reload(); // Refresca toda la pagina
                    }
                },
                error: function(error){

                }
            });
        }
    });

     // Funcion para facturar venta
     $('#btn_facturar_venta').click(function(e){
        e.preventDefault();

        var rows = $('#detalle_venta tr').length;
        if(rows > 0){
            var action = 'procesarVenta';
            var codcliente = $('#idCliente').val();

            $.ajax({
                url : 'ajax.php',
                type: "POST",
                async : true,
                data : {action : action, codcliente : codcliente},

                success: function(response){
                   
                    if(response != 'error'){

                        var info = JSON.parse(response);
                        //console.log(info);

                        generarPDF(info.fac_tendero, info.fac_id);

                        location.reload(); // Refresca toda la pagina
                    }else{
                        console.log('no data');
                    }
                },
                error: function(error){

                }
            });
        }
    });


}); //END 

// Funcion para crear la factura en PDF
function generarPDF(cliente, factura){
    var ancho = 1000;
    var alto = 800;
    // Calcular posicion x,y para centrar la ventana
    var x = parseInt((window.screen.width/2) - (ancho/2));
    var y = parseInt((window.screen.height/2) - (alto/2));

    $url = 'factura/generaFactura.php?cl='+cliente+'&f='+factura;
    window.open($url, "Factura","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");

}

// Funcion para eliminar productos de detalle factura
function del_product_detalle(id){
    var action = 'delProductoDetalle';
    var id_detalle = id;

    $.ajax({
        url : 'ajax.php',
        type : "POST",
        async : true,
        data : {action : action , id_detalle : id_detalle},

        success: function(response){
            
            if(response != 'error'){
                var info = JSON.parse(response);

                $('#detalle_venta').html(info.detalle);
                $('#detalle_totales').html(info.totales);
                // Limpiamos todos los campos
                $('#txt_cod_producto').val('');
                $('#txt_descripcion').html('-');
                $('#txt_existencia').html('-');
                $('#txt_cant_producto').val('0');
                $('#txt_precio').html('00.00');
                $('#txt_precio_total').html('00.00');

                // Bloquear input de cantidad
                $('#txt_cant_producto').attr('disabled', 'disabled');

                // Ocultar boton AGREGAR
                $('#add_product_venta').slideUp();

            }else{
                $('#detalle_venta').html('');
                $('#detalle_totales').html('');
            }
            viewProcesar();
        },
        error: function(error){

        }
    });
}

// Funcion para mostrar/ocultar el boton procesar
function viewProcesar(){
    if($('#detalle_venta tr').length > 0){
        $('#btn_facturar_venta').show();
    }else{
        $('#btn_facturar_venta').hide();
    }
}

function serchForDetalle(id){
    var action = 'serchForDetalle';
    var user = id;

    $.ajax({
        url : 'ajax.php',
        type : "POST",
        async : true,
        data : {action : action , user : user},

        success: function(response){
            if(response != 'error'){

                var info = JSON.parse(response);
                $('#detalle_venta').html(info.detalle);
                $('#detalle_totales').html(info.totales);
            }else{
                console.log('No hay datos');                    
            } 
            viewProcesar();                   
        },
        error: function(error){

        }
    });
}
//Funcion para agregar mas productos
function sendDataProduct(){
    $('.alertAddProduct').html('');

    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: $('#form_add_product').serialize(),
        
        success: function(response){
           
            console.log(response);
        },
        error: function(error){
            console.log(error);
        }
    });
}
//Funcion para eliminar productos (Desplegar modal)
function delProduct(){

    var pr = $('#producto_id').val();
    $('.alertAddProduct').html('');

    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: $('#form_del_product').serialize(),
        
        success: function(response){
            console.log(response);
           if(response == 'error'){
            $('alertAddProduct').html('<p style="color: red;">Error al eliminar producto</p>');
           }else{
               
               $('.rows'+pr).remove();
               $('#form_del_product .eliminar').remove();
               $('.alertAddProduct').html('<p style="color: green;">Producto eliminado</p>');
           }
        },
        error: function(error){
            console.log(error);
        }
    });
}
function closeModal(){
    $('.alertAddProduct').html('');
    $('#txtCantidad').val('');
    $('.modal').fadeOut();
}
// Funcion solo para el ingreso de numeros
function solonumeros(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    numeros = "0123456789";
    especiales = [8, 37, 39, 46];

    tecla_especial = false
    for(var i in especiales) {
        if(key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }
    if(numeros.indexOf(tecla) == -1 && !tecla_especial)
        return false;
}