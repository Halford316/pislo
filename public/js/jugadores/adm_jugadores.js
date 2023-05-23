/** Store  */
$('#frmNuevoJugador').on('submit', function(ev) {
    ev.preventDefault();

    ajaxStoreJugador(this);
});

/** Update  */
$('#frmEditarJugador').on('submit', function(ev){
    ev.preventDefault();

    ajaxUpdateJugador(this);
});

function verJugadores(id)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //var dataString = new FormData(form);

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
        $("#tblJugadores").dataTable().fnDestroy();
        $('#mdl_nj_equipo_id').val(id);

        /* Cargando Jugadores  */
        $('#tblJugadores').DataTable( {
            "searching" : false,
            "paging" : false,
            "info": false,
            bSort: false,
            "ajax": flagUrl+'admin/equipos/jugadores/'+id+'/datatable',

            "aoColumnDefs": [
                { 'bSortable': false, 'aTargets': [0,1,2,3,4,5,6] },
                { className: "text-center", "targets": [0,1,3,4,5,6] }
            ],
            "columns" : [
                { data: 'id' },
                { data: 'foto' },
                { data: 'nombre' },
                { data: 'edad' },
                { data: 'telefono' },
                { data: 'status' },
                { data: 'fecha_reg' },
                { data: 'fecha_mod' },
                { data: 'acciones' },
            ],

            language: {
                    "sProcessing":     "Procesando...",
                    "sEmptyTable":     "No tiene ningún jugador registrado al equipo",
                    "sLoadingRecords": "Cargando...",
                }

        } );
        /** ************************************************************************************ */

    })

    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        console.log(jqXHR.statusText);
    });

    $('#mdlJugadores').modal('show');
}

function nuevoJugador()
{
    //$("#frmNuevoJugador")[0].reset();

    var foto_default = "/images/foto_default.png";
    document.getElementById('imagePreview').innerHTML =
        '<img src="'+foto_default+'" width="114" height="114" border="1" class="rounded" />';

    $('#mdlNuevoJugador').modal('show');
}


/** Guardando jugador */
function ajaxStoreJugador(form) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var dataString = new FormData(form);

    $.ajax({
        url: flagUrl+'admin/equipos/jugadores/store-process',
        datatType : 'json',
        type: 'POST',
        data: dataString,
        cache: false,
        contentType: false,
        processData: false,

        beforeSend:function(){
            $("#fa_nj_guardar").hide();
            $("#fa_nj_spinner").show();
            $('#btnGuardarJugador').prop("disabled", true);
        },

        success:function(result) {
            $("#fa_nj_guardar").show();
            $("#fa_nj_spinner").hide();
            $('#btnGuardarJugador').prop("disabled", false);

            if(result.success) {

                Swal.fire(
                    'Registro exitoso!',
                    'Se ha registrado correctamente el jugador.',
                    'success'
                )

                $("#frmNuevoJugador")[0].reset();
                $('#mdlNuevoJugador').modal('hide');
                $('#tblJugadores').DataTable().ajax.reload();
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

/** Mostrando modal para editar */
function editarJugador(id)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: flagUrl+'admin/equipos/jugadores/show/'+id,
        dataType : 'json',
        type: 'GET',
        cache: false,
        processData: true,

        beforeSend: function()
        {
            console.log('Mostrando jugador con ID: '+id);
        }
    })

    .done(function(data)
    {
        $('#mdl_uj_jugador_id').val(data.ficha.id);
        $('#mdl_uj_ape_paterno').val(data.ficha.ape_paterno);
        $('#mdl_uj_ape_materno').val(data.ficha.ape_materno);
        $('#mdl_uj_nombres').val(data.ficha.nombres);
        $('#mdl_uj_fecha_nac').val(data.ficha.fecha_nac);
        $('#mdl_uj_telefono').val(data.ficha.telefono);
        $('#mdl_uj_sexo').val(data.ficha.sexo);
        $('#mdl_uj_status').val(data.ficha.status);
        $('#mdl_uj_foto_jugador').val('');

        var foto = data.ficha.foto;
        document.getElementById('mdl_imagePreview').innerHTML =
        '<img src="/storage/jugador_fotos/'+foto+'" width="114" height="114" border="1" class="rounded" />';

    })

    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        console.log(jqXHR.statusText);
    });

    $('#mdlEditarJugador').modal('show');
}


/** Actualizando jugador */
function ajaxUpdateJugador(form) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //var id = $('#ex_ficha_id').val();
    var dataString = new FormData( $('#frmEditarJugador')[0] );

    $.ajax({
        url: flagUrl+'admin/equipos/jugadores/update-process',
        dataType : 'json',
        type: 'POST',
        data: dataString,
        cache: false,
        contentType: false,
        processData: false,

        beforeSend: function()
        {
            $("#fa_uj_guardar").hide();
            $("#fa_uj_spinner").show();
            $('#btnActualizarJugador').prop("disabled", true);
        }
    })

    .done(function(data)
    {
        if(data.status === "updated-jugador") {
            $("#fa_uj_guardar").show();
            $("#fa_uj_spinner").hide();
            $('#btnActualizarJugador').prop("disabled", false);

            $('#mdlEditarJugador').modal('hide');
            $('#tblJugadores').DataTable().ajax.reload(null, false);

            toastr.success('El jugador ha sido actualizado', 'Success');
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
