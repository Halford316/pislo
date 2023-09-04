/** Store  */
$('#frmNuevo').on('submit', function(ev) {
    ev.preventDefault();

    ajaxStore(this);
});

$('#frmNuevoPago').on('submit', function(ev) {
    ev.preventDefault();

    ajaxStorePago(this);
});

/** Update  */
$('#frmEditarEquipo').on('submit', function(ev){
    ev.preventDefault();

    ajaxUpdate(this);
});

$('#frmEditarPago').on('submit', function(ev){
    ev.preventDefault();

    ajaxUpdatePago(this);
});

function nuevoEquipo()
{
    $('#mdlNuevo').modal('show');
}

function nuevoPago()
{
    $("#registracion").on('change keydown paste input', function() {
        var num_1 = $("#registracion").val();
        var num_2 = $("#adelanto").val();
        resta(num_1, num_2);
    });

    $("#adelanto").on('change keydown paste input', function() {
        var num_1 = $("#registracion").val();
        var num_2 = $("#adelanto").val();
        resta(num_1, num_2);
    });

    $("#frmNuevoPago")[0].reset();
    $('#mdlNuevoPago').modal('show');
}

function resta(num1, num2) {
    // Obtener los valores de los inputs de texto
    /** Modal Nuevo */
    //var num1 = parseFloat(document.getElementById("registracion").value);
    //var num2 = parseFloat(document.getElementById("adelanto").value);

    /** Modal Editar */
    //var num_1 = parseFloat(document.getElementById("mdl_up_registracion").value);
    //var num_2 = parseFloat(document.getElementById("mdl_up_adelanto").value);

    // Restar los valores
    var result = num1 - num2;
    var up_result = num1 - num2;

    // Mostrar el resultado en el input de texto correspondiente
    document.getElementById("saldo").value = result;
    document.getElementById("mdl_up_saldo").value = up_result;

}

/** Modal que muestra el pago x equipo */
function verPagos(id)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: flagUrl+'admin/equipos/show/'+id,
        dataType : 'json',
        type: 'GET',
        cache: false,
        processData: true,

        beforeSend: function()
        {
            //console.log('Mostrando equipo con ID: '+id);
        }
    })

    .done(function(data)
    {
        $('#torneo_id').val('');
        $('#equipo_id').val(id);
        $('#costo').val('');
        $('#registracion').val('');

        $("#registracion").on('change keydown paste input', function() {
            var num_1 = $("#registracion").val();
            var num_2 = $("#adelanto").val();
            resta(num_1, num_2);
        });

        $("#adelanto").on('change keydown paste input', function() {
            var num_1 = $("#registracion").val();
            var num_2 = $("#adelanto").val();
            resta(num_1, num_2);
        });

        listarPagosxTorneo(id);

    })

    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        console.log(jqXHR.statusText);
    });

    $('#mdlPagos').modal('show');
}

/** Listado pagos x torneo */
function listarPagosxTorneo(equipo_id, torneo_id)
{
    $("#tblPagos").dataTable().fnDestroy();

    /* Cargando pagos  */
    $('#tblPagos').DataTable( {
        "searching" : false,
        "paging" : false,
        "info": false,
        bSort: false,
        "ajax": flagUrl+'admin/equipos/pagos/'+equipo_id+'/'+torneo_id+'/datatable',

        "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': [0,1,2,3,4,5,6,7,8] }
        ],
        "columns" : [
            { data: 'equipo' },
            { data: 'torneo' },
            { data: 'pago' },
            { data: 'adelanto' },
            { data: 'saldo' },
            { data: 'status' },
            { data: 'usuario' },
            { data: 'fecha_reg' },
            { data: 'fecha_mod' },
            { data: 'acciones' },
        ],

        language: {
                "sProcessing":     "Procesando...",
                "sEmptyTable":     "Para ver los pagos hecho por el equipo, seleccione un torneo.",
                "sLoadingRecords": "Cargando...",
            }

    } );

}


/** Guardando */
function ajaxStore(form) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var dataString = new FormData(form);

    $.ajax({
        url: flagUrl+'admin/equipos/store-process',
        datatType : 'json',
        type: 'POST',
        data: dataString,
        cache: false,
        contentType: false,
        processData: false,

        beforeSend:function(){
            $("#fa_n_guardar").hide();
            $("#fa_n_spinner").show();
            $('#btnGuardar').prop("disabled", true);
        },

        success:function(result) {
            $("#fa_n_guardar").show();
            $("#fa_n_spinner").hide();
            $('#btnGuardar').prop("disabled", false);

            if(result.success) {

                Swal.fire(
                    'Registro exitoso!',
                    'Se ha registrado correctamente el equipo.',
                    'success'
                )

                $("#frmNuevo")[0].reset();
                $('#mdlNuevo').modal('hide');
                $('#tblEquipos').DataTable().ajax.reload();


            }
        },

        error: function(xhr) {
            swal.fire("Error", "No se puede procesar el formulario", "error");
            //console.log(xhr.statusText);
            //console.log(xhr.responseText);
        }

    }).fail( function( jqXHR, textStatus, errorThrown ) {

        if (jqXHR.status === 0) {
            alert('Not connect: Verify Network.');
        } else if (jqXHR.status == 404) {
            alert('Requested page not found [404]');
        } else if (jqXHR.status == 500) {
            alert('Internal Server Error [500].');
        } else if (textStatus === 'parsererror') {
            alert('Requested JSON parse failed.');
        } else if (textStatus === 'timeout') {
            alert('Time out error.');
        } else if (textStatus === 'abort') {
            alert('Ajax request aborted.');
        } else {
            alert('Uncaught Error: ' + jqXHR.responseText);
        }

    });
}

/** Guardando pago */
function ajaxStorePago(form) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var dataString = new FormData(form);

    $.ajax({
        url: flagUrl+'admin/equipos/pagos/store-process',
        datatType : 'json',
        type: 'POST',
        data: dataString,
        cache: false,
        contentType: false,
        processData: false,

        beforeSend:function(){
            $("#fa_np_guardar").hide();
            $("#fa_np_spinner").show();
            $('#btnGuardarPago').prop("disabled", true);
        },

        success:function(result) {
            $("#fa_np_guardar").show();
            $("#fa_np_spinner").hide();
            $('#btnGuardarPago').prop("disabled", false);

            if(result.success) {

                Swal.fire(
                    'Pago registrado!',
                    'Se ha registrado correctamente el pago del equipo al torneo.',
                    'success'
                )

                $("#frmNuevoPago")[0].reset();
                $('#mdlNuevoPago').modal('hide');
                $('#tblPagos').DataTable().ajax.reload();

            }
        },

        error: function(xhr) {
            swal.fire("Error", "No se puede procesar el formulario", "error");
            //console.log(xhr.statusText);
            //console.log(xhr.responseText);
        }

    }).fail( function( jqXHR, textStatus, errorThrown ) {

        if (jqXHR.status === 0) {
            alert('Not connect: Verify Network.');
        } else if (jqXHR.status == 404) {
            alert('Requested page not found [404]');
        } else if (jqXHR.status == 500) {
            alert('Internal Server Error [500].');
        } else if (textStatus === 'parsererror') {
            alert('Requested JSON parse failed.');
        } else if (textStatus === 'timeout') {
            alert('Time out error.');
        } else if (textStatus === 'abort') {
            alert('Ajax request aborted.');
        } else {
            alert('Uncaught Error: ' + jqXHR.responseText);
        }

    });
}

/** Mostrando modal para editar pago */
function editarPago(id)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: flagUrl+'admin/equipos/pagos/show/'+id,
        dataType : 'json',
        type: 'GET',
        cache: false,
        processData: true,

        beforeSend: function()
        {
            console.log('Mostrando pago del equipo con ID: '+id);
        }
    })

    .done(function(data)
    {
        $('#mdl_up_pago_id').val(data.ficha.id);
        $('#mdl_up_torneo_id').val(data.ficha.torneo_id);
        $('#mdl_up_registracion').val(data.ficha.registracion);
        $('#mdl_up_adelanto').val(data.ficha.adelanto);
        $('#mdl_up_saldo').val(data.ficha.saldo);

        $("#mdl_up_registracion").on('change keydown paste input', function() {
            var num_1 = $("#mdl_up_registracion").val();
            var num_2 = $("#mdl_up_adelanto").val();
            resta(num_1, num_2);
        });

        $("#mdl_up_adelanto").on('change keydown paste input', function() {
            var num_1 = $("#mdl_up_registracion").val();
            var num_2 = $("#mdl_up_adelanto").val();
            resta(num_1, num_2);
        });

    })

    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        console.log(jqXHR.statusText);
    });

    $('#mdlEditarPago').modal('show');
}

/** Actualizando pago */
function ajaxUpdatePago(form) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //var id = $('#ex_ficha_id').val();
    var dataString = new FormData( $('#frmEditarPago')[0] );

    $.ajax({
        url: flagUrl+'admin/equipos/pagos/update-process',
        dataType : 'json',
        type: 'POST',
        data: dataString,
        cache: false,
        contentType: false,
        processData: false,

        beforeSend: function()
        {
            //you can show your loader
            $("#fa_up_guardar").hide();
            $("#fa_up_spinner").show();
            $('#btnActualizarPago').prop("disabled", true);
        }
    })

    .done(function(data)
    {
        if(data.status === "updated-pago") {
            $("#fa_up_guardar").show();
            $("#fa_up_spinner").hide();
            $('#btnActualizarPago').prop("disabled", false);

            $('#mdlEditarPago').modal('hide');
            $('#tblPagos').DataTable().ajax.reload(null, false);

            toastr.success('El pago ha sido actualizado', 'Success');
            toastr.options.timeOut = 3000;
        }
    })

    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        var errors = $.parseJSON(jqXHR.responseText);
        console.log(errors);

        var errorsHtml = '';
        $.each(errors['errors'], function (index, value) {
            errorsHtml += '<li>' + value + '</li>';
        });

        if(jqXHR.status === 401) {
            errorsHtml = '<li>Error en la autenticación.</li>';
        }

        if(jqXHR.status === 500) {
            errorsHtml = '<li>Hubo un error en el sistema.</li>';
        }

    });

}

/* Pintando precio x torneo */
$('#torneo_id').on('change', function() {
    var stateID = $(this).val();
    var equipo_id = $('#equipo_id').val();

    if(stateID) {
        $.ajax({
            url: flagUrl+'admin/equipos/pagos/showPrecioXTorneo/'+equipo_id+'/'+stateID,
            type: "GET",
            data : {"_token":"{{ csrf_token() }}"},
            dataType: "json",

            success:function(data) {

                console.log(data.ficha);

                if(data) {
                    $('#costo').val(data.precio);

                    if (data.ficha.length) {
                        $('#registracion').val(data.ficha[0].saldo);
                        listarPagosxTorneo(equipo_id, stateID);

                    }else {
                        $('#registracion').val(data.precio);
                        $('#tblPagos').dataTable().fnClearTable();
                    }

                }else{

                    //$('#registracion').val('');

                }
            }
        });
    }else {
        $('#registracion').val('');
    }
});


/** Eliminado pago */
function eliminarPago(id) {

    swal.fire({
        title: '¿Desea eliminar el registro?',
        //text: "Ud. enviará una constancia adjunto en pdf al correo electrónico del alumno.",
        icon: "question",
        showCancelButton: true,
        cancelButtonText: 'Cancelar <i class="fa fa-times ml-1"></i>',
        confirmButtonColor: '#DA332E',
        cancelButtonColor: '#312C37',
        confirmButtonText: 'Confirmar <i class="fa fa-check-circle ml-1"></i>'
    })
    .then((result) => {
        if (result.isConfirmed) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: flagUrl+'admin/equipos/pagos/delete/'+id,
                dataType : 'json',
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,

                beforeSend: function()
                {
                }
            })

            .done(function(data)
            {
                if (data.status === "deleted") {

                    $('#tblPagos').DataTable().ajax.reload();

                    swal.fire({
                        title: 'Registro eliminado!',
                        text: 'El registro ha sido eliminado del sistema de forma permanente.',
                        icon: 'success'
                    }).then(function() {
                        //console.log('Ejecutando...');
                    });
                }

            })

            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                //console.log(jqXHR.statusText);
                console.log(xhr.responseText);
            });

        }

    });

}


function valideKey(evt){

    // code is the decimal ASCII representation of the pressed key.
    var code = (evt.which) ? evt.which : evt.keyCode;

    if(code==8) { // backspace.
        return true;
    } else if(code>=48 && code<=57) { // is a number.
        return true;
    } else{ // other keys.
        return false;
    }
}

/** Mostrando modal para editar equipo */
function editarEquipo(id)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: flagUrl+'admin/equipos/show/'+id,
        dataType : 'json',
        type: 'GET',
        cache: false,
        processData: true,

        beforeSend: function()
        {
            console.log('Mostrando equipo con ID: '+id);
        }
    })

    .done(function(data)
    {
        $('#mdl_ue_equipo_id').val(data.ficha.id);
        $('#mdl_ue_nombre').val(data.ficha.nombre);
        $('#mdl_uj_foto_jugador').val('');

        var foto = data.ficha.foto;

        if (foto) {
            document.getElementById('mdl_imagePreview').innerHTML =
            '<img src="/storage/equipo_fotos/'+foto+'" width="114" height="114" border="1" class="rounded" />';
        }


    })

    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        console.log(jqXHR.statusText);
    });

    $('#mdlEditarEquipo').modal('show');
}

/** Actualizando */
function ajaxUpdate(form) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var dataString = new FormData( $('#frmEditarEquipo')[0] );

    $.ajax({
        url: flagUrl+'admin/equipos/update-process',
        dataType : 'json',
        type: 'POST',
        data: dataString,
        cache: false,
        contentType: false,
        processData: false,

        beforeSend: function()
        {
            $("#fa_ue_guardar").hide();
            $("#fa_ue_spinner").show();
            $('#btnActualizarEquipo').prop("disabled", true);
        }
    })

    .done(function(data)
    {
        if(data.status === "updated-equipo") {
            $("#fa_ue_guardar").show();
            $("#fa_ue_spinner").hide();
            $('#btnActualizarEquipo').prop("disabled", false);

            $('#mdlEditarEquipo').modal('hide');
            $('#tblEquipos').DataTable().ajax.reload(null, false);

            toastr.success('El equipo ha sido actualizado', 'Success');
            toastr.options.timeOut = 3000;
        }
    })

    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        var errors = $.parseJSON(jqXHR.responseText);
        console.log(errors);

        var errorsHtml = '';
        $.each(errors['errors'], function (index, value) {
            errorsHtml += '<li>' + value + '</li>';
        });

        if(jqXHR.status === 401) {
            errorsHtml = '<li>Error en la autenticación.</li>';
        }

        if(jqXHR.status === 500) {
            errorsHtml = '<li>Hubo un error en el sistema.</li>';
        }

    });

}
