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
                                    '</form>'+
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
});

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

function closeModal() {
    $('.alertAddProd').html('');
    $('#txtCantidad').val('');
    $('#txtPrecio').val('');
    $('.modal').fadeOut();
}