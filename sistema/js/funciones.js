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

});

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
//Funcion para eliminar productos
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