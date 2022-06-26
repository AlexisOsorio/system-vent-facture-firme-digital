//========================= JQUERY =====================

$(document).ready(function () {

    //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------
    $("#foto").on("change", function () {
        var uploadFoto = document.getElementById("foto").value;
        var foto = document.getElementById("foto").files;
        var nav = window.URL || window.webkitURL;
        var contactAlert = document.getElementById('form_alert');

        if (uploadFoto != '') {
            var type = foto[0].type;
            var name = foto[0].name;
            if (type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png') {
                contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';
                $("#img").remove();
                $(".delPhoto").addClass('notBlock');
                $('#foto').val('');
                return false;
            } else {
                contactAlert.innerHTML = '';
                $("#img").remove();
                $(".delPhoto").removeClass('notBlock');
                var objeto_url = nav.createObjectURL(this.files[0]);
                $(".prevPhoto").append("<img id='img' src=" + objeto_url + ">");
                $(".upimg label").remove();

            }
        } else {
            alert("No selecciono foto");
            $("#img").remove();
        }
    });

    $('.delPhoto').click(function () {
        $('#foto').val('');
        $(".delPhoto").addClass('notBlock');
        $("#img").remove();

        if ($("#foto_actual") && $("foto_remove")) {
            $("#foto_remove").val('imgproducto.png');

        }
    });

    //modal add stock
    $('.add_stock').click(function (e) {
        e.preventDefault();
        var producto = $(this).attr('stock');
        var action = 'infoStock';
        $.ajax({
            type: "POST",
            url: "agregar_ajax.php",
            async: true,
            data: {
                action,
                producto
            },
            success: function (response) {
                if (response != 'error') {
                    var info = JSON.parse(response);

                    //$('#producto_id').val(info.codproducto);
                    // $('.name_prod').html(info.descripcion);

                    $('.bodyModal').html(
                        '<div class="col-md-4">' +
                        '<div class="card card-success">' +
                        '<div class="card-header">' +
                        '<h1 class="card-title"><i class="nav-icon fas fa-cubes"></i> Agregar Producto</h1>' +
                        '</div>' +
                        '<div class="card-body">' +
                        '<form action="" method="POST" name="form_add_stock" id="form_add_stock" onsubmit="event.preventDefault(); sendDataProd();">' +
                        '<h2 class="name_prod" style="font-size: 25px; text-align: center; font-weight: bolder;">' + info.descripcion + '</h2>' +
                        '<div class="form-group row">' +
                        '<input type="number" name="cantidad" id="txtCantidad" placeholder="Cantidad del Producto" class="form-control" required>' +
                        '</div>' +
                        '<div class="form-group row">' +
                        '<input type="text" name="precio" id="txtPrecio" placeholder="Precio del Producto" class="form-control" required>' +
                        '</div>' +
                        '<input type="hidden" name="producto_id" id="producto_id" class="form-control" value="' + info.codproducto + '">' +
                        '<input type="hidden" name="action" class="form-control" value="addProd">' +
                        '<div class="alerta alertAddProd"></div>' +
                        '<button type="submit" class="btn btn-success"><i class="nav-icon fas fa-plus"></i> Agregar</button>' +
                        '<a href="#" class="btn bg-danger closeModal" style="float: right;" onclick="closeModal();"><i class="nav-icon fas fa-ban"></i> Cerrar</a>' +
                        '</form>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                }
            },
            /**/
            error: function (error) {
                console.log(error);
            },
        });
        $('.modal').fadeIn();
    });

    //modal delete product
    $('.del_stock').click(function (e) {
        e.preventDefault();
        var producto = $(this).attr('stock');
        var action = 'infoStock';
        $.ajax({
            type: "POST",
            url: "agregar_ajax.php",
            async: true,
            data: {
                action,
                producto
            },
            success: function (response) {
                if (response != 'error') {
                    var info = JSON.parse(response);

                    //$('#producto_id').val(info.codproducto);
                    // $('.name_prod').html(info.descripcion);

                    $('.bodyModal').html(
                        '<div class="col-md-4">' +
                        '<div class="card card-danger">' +
                        '<div class="card-header">' +
                        '<h1 class="card-title"><i class="nav-icon fas fa-cubes"></i> Eliminar Producto</h1>' +
                        '</div>' +
                        '<div class="card-body">' +
                        '<form action="" method="POST" name="form_del_stock" id="form_del_stock" onsubmit="event.preventDefault(); delProd();">' +
                        '<h4 class="text-center"><b>¿Esta seguro de eliminar este producto?</b></h4>' +
                        '<h2 class="name_prod" style="font-size: 25px; text-align: center; font-weight: bolder;">' + info.descripcion + '</h2>' +
                        '<input type="hidden" name="producto_id" id="producto_id" class="form-control" value="' + info.codproducto + '">' +
                        '<input type="hidden" name="action" class="form-control" value="delProd">' +
                        '<div class="alerta alertAddProd"></div>' +
                        '<button type="submit" class="btn btn-danger btn_delete"><i class="nav-icon fas fa-trash"></i> Eliminar</button>' +
                        '<a href="#" class="btn bg-secondary closeModal" style="float: right;" onclick="closeModal();"><i class="nav-icon fas fa-ban"></i> Cerrar</a>' +
                        '</form>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                }
            },
            /**/
            error: function (error) {
                console.log(error);
            },
        });
        $('.modal').fadeIn();
    });

    $('#search_proveedor').change(function (e) {
        e.preventDefault();
        var vendor = getUrl();
        location.href = vendor + 'search_products.php?proveedor=' + $(this).val();
    });

    //campos para registrar cliente
    $('.btn_new_client').click(function (e) {
        e.preventDefault();
        $('#nombre_client').removeAttr('disabled');
        $('#telefono_client').removeAttr('disabled');
        $('#direccion_client').removeAttr('disabled');

        $('#div_register_client').slideDown();
    });


    //buscar cliente
    $('#cedula_client').keyup(function (e) {
        e.preventDefault();

        var client = $(this).val();
        var action = 'searchCliente';

        $.ajax({
            type: "POST",
            url: "agregar_ajax.php",
            async: true,
            data: {
                action: action,
                cliente: client
            },
            success: function (response) {
                if (response == 0) {
                    $('#idclient').val('');
                    $('#nombre_client').val('');
                    $('#telefono_client').val('');
                    $('#direccion_client').val('');
                    //mostrar datos
                    $('#btn_new_client').slideDown();
                } else {
                    var data = $.parseJSON(response);
                    $('#idclient').val(data.idcliente);
                    $('#nombre_client').val(data.nombre);
                    $('#telefono_client').val(data.telefono);
                    $('#direccion_client').val(data.direccion);
                    //ocultar boton
                    $('.btn_new_client').slideUp();

                    //bloquear campos
                    $('#nombre_client').attr('disabled', 'disabled');
                    $('#telefono_client').attr('disabled', 'disabled');
                    $('#direccion_client').attr('disabled', 'disabled');

                    //ocultar boton guardar
                    $('#div_register_client').slideUp();
                }
            },
            error: function (error) {

            },
        });
    });

    //crear cliente - ventas
    $('#form_new_client').submit(function(e){
        e.preventDefault();
        
    })

}); //end ready

function getUrl() {
    var local = window.location;
    var pathName = local.pathname.substring(0, local.pathname.lastIndexOf('/') + 1);
    return local.href.substring(0, local.href.length - ((local.pathname + local.search + local.hash).length - pathName.length))
}

function sendDataProd() {
    $('.alertAddProd').html('');
    $.ajax({
        type: "POST",
        url: "agregar_ajax.php",
        async: true,
        data: $('#form_add_stock').serialize(),
        success: function (response) {
            if (response == 'error') {
                $('.alertAddProd').html('<p style="color: red;">Error al agregar producto</p>')
            } else {
                var info = JSON.parse(response);
                $('.row' + info.producto_id + '.celPrecio').html(info.nuevo_precio);
                $('.row' + info.producto_id + '.celStock').html(info.nueva_existencia);
                $('#txtCantidad').val('');
                $('#txtPrecio').val('');
                $('.alertAddProd').html('<p style="color: #28A745">Producto agregado con exito</p>')
            }
        },

        error: function (error) {
            console.log(error);
        },
    });
}

//eliminar producto
function delProd() {
    var prd = $('#producto_id').val();
    $('.alertAddProd').html('');
    $.ajax({
        type: "POST",
        url: "agregar_ajax.php",
        async: true,
        data: $('#form_del_stock').serialize(),
        success: function (response) {
            console.log(response);

            if (response == 'error') {
                $('.alertAddProd').html('<p style="color: red;">Error al eliminar producto</p>')
            } else {
                $('.row' + prd).remove();
                $('#form_del_stock .btn_delete').remove();
                $('.alertAddProd').html('<p style="color: #28A745">Producto eliminado con exito</p>')
            }
        },

        error: function (error) {
            console.log(error);
        },
    });
}

function closeModal() {
    $('.alertAddProd').html('');
    $('#txtCantidad').val('');
    $('#txtPrecio').val('');
    $('.modal').fadeOut();
}