/** Update  */
$('#frmEditarEncuentro').on('submit', function(ev){
    ev.preventDefault();

    ajaxUpdateEncuentro(this);
});

$(document).ready(function() {

    /* Pintando los jugadores */
    $('#mdl_ue_equipo').on('change', function() {
        var stateID = $(this).val();

        if(stateID) {
            $.ajax({
                url: '/admin/fixtures/administrar-fixture/showJugadoresXEquipo/'+stateID,
                type: "GET",
                data : {"_token":"{{ csrf_token() }}"},
                dataType: "json",
                success:function(data) {

                    if(data.jugadores) {
                        $('#mdl_ue_jugador').empty();
                        $('#mdl_ue_jugador').focus;
                        $('#mdl_ue_jugador').append('<option value="">-- Seleccionar jugador --</option>');
                        $.each(data.jugadores, function(key, value) {
                            var jugador_nombre = value.nombres+' '+value.ape_paterno+' '+value.ape_materno;
                            $('select[name="mdl_ue_jugador"]').append('<option value="'+value.id+'">'+jugador_nombre+'</option>');
                        });
                }else{
                    $('#mdl_ue_jugador').empty();
                }
            }
            });
        }else{
        $('#mdl_ue_jugador').empty();
        }
    });

});


/** Mostrando modal para fixture */
function generarFixture(id)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: flagUrl+'admin/fixtures/generar-fixture/'+id,
        dataType : 'json',
        type: 'GET',
        cache: false,
        processData: true,

        beforeSend: function()
        {
            //console.log('Mostrando torneo con ID: '+id);
        }
    })

    .done(function(data)
    {

        //window.location.href= 'fixture-generado';
    })

    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        console.log(jqXHR.statusText);
    });

    //$('#mdlFixture').modal('show');
}

/** Administrar fixture */
function administrarFixture(id)
{
    window.location.href = 'fixtures/administrar-fixture/'+id

}

/** Mostrando modal de encuentro para registrar goles, arbitros, expulsiones */
function registrarFixture(id)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '/admin/fixtures/administrar-fixture/show/'+id,
        dataType : 'json',
        type: 'GET',
        cache: false,
        processData: true,

        beforeSend: function()
        {
            //console.log('Mostrando fixture con ID: '+id);
        }
    })

    .done(function(data)
    {
        /* Cargando datatable expulsiones */
        $("#tblExpulsiones").dataTable().fnDestroy();
        //$('#obs_ficha_id').val(data.ficha.id);

        $('#tblExpulsiones').DataTable( {
            "searching" : false,
            "paging" : false,
            "info": false,
            bSort: false,
            "ajax": "/admin/fixtures/administrar-fixture/expulsiones/datatable/"+id,
            "columnDefs": [ {
                className: "text-center", "targets": [1],
                className: "w-50", "targets": [0],

            }],
            "aoColumnDefs": [
                { 'bSortable': false, 'aTargets': [0,1,2] }
            ],
            "columns" : [
                { data: 'equipo' },
                { data: 'jugador' },
                { data: 'fecha_reg' }
            ],

            language: {
                    "sProcessing":     "Procesando...",
                    "sEmptyTable":     "No hay registros de expulsiones<br>Agregue jugadores desde el botón Agregar",
                    "sLoadingRecords": "Cargando...",
                }

        } );
        /** **************************************************************************** */

        $('#mdl_ue_torneo_id').val(data.ficha.torneo_id);
        $('#mdl_ue_campo_id').val(data.ficha.campo_id);
        $('#mdl_ue_fixture_id').val(data.ficha.id);

        $('#mdl_ue_ficha_id').val(data.ficha.id);
        $('#mdl_show_fecha_nro').html(data.ficha.fecha_nro);
        $('#mdl_show_local').html(data.ficha.locales.nombre);
        $('#mdl_show_visitante').html(data.ficha.visitantes.nombre);
        $('#mdl_equipo_1_goles').val(data.ficha.equipo_1_goles);
        $('#mdl_equipo_2_goles').val(data.ficha.equipo_2_goles);
        $('#mdl_ue_partido_hora').val(data.ficha.partido_hora);
        $('#mdl_ue_partido_fecha').val(data.ficha.partido_fecha);
        $('#mdl_ue_status').val(data.ficha.status);

        if(data.campos) {
            $('#mdl_ue_campo').empty();
            $('#mdl_ue_campo').focus;
            $('#mdl_ue_campo').append('<option value="">-- Seleccione campo --</option>');

            $.each(data.campos, function(key, value) {
                var sel = (data.ficha.campo_id === value.campos.id) ? 'selected': '';
                $('select[name="mdl_ue_campo"]').append('<option value="'+value.campos.id+'" '+sel+'>'+value.campos.nombre+'</option>');
            });

        }else {
            $('#mdl_ue_arbitro').empty();
        }

        if(data.arbitros) {
            $('#mdl_ue_arbitro').empty();
            $('#mdl_ue_arbitro').focus;
            $('#mdl_ue_arbitro').append('<option value="">-- Seleccione arbitro --</option>');

            $.each(data.arbitros, function(key, value) {
                var sel = (data.ficha.arbitro_id === value.id) ? 'selected': '';
                var arbitro_nombre = value.nombres+' '+value.ape_paterno+' '+value.ape_materno;
                $('select[name="mdl_ue_arbitro"]').append('<option value="'+value.id+'" '+sel+'>'+arbitro_nombre+'</option>');
            });

        }else {
            $('#mdl_ue_arbitro').empty();
        }

        if(data.equipos) {
            $('#mdl_ue_equipo').empty();
            $('#mdl_ue_equipo').focus;
            $('#mdl_ue_equipo').append('<option value="">-- Seleccione equipo --</option>');

            $.each(data.equipos[0], function(key, value) {
                $('select[name="mdl_ue_equipo"]').append('<option value="'+key+'">'+value+'</option>');
            });

        }else {
            $('#mdl_ue_equipo').empty();
        }

        /** Pintando los expulsados */
        if(data.expulsados_local || data.expulsados_visitante) {

            $('#show_expulsados').show();
        }else {
            $('#show_expulsados').hide();
        }

        /* Mostrando expulsados del equipo local */
        getExpulsadosLocal(data);

        /* Mostrando expulsados del equipo visitante */
        getExpulsadosVisitante(data);

    })

    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        console.log(jqXHR.statusText);
    });

    $('#mdlEditarEncuentro').modal('show');
}

/** Mostrando expulsados del equipo local */
function getExpulsadosLocal(data)
{
    if(data.expulsados_local) {
        $('#mdl_show_expulsados_local').empty();

        $.each(data.expulsados_local, function(key, value) {

            var jugador = value.jugadores.nombres+' '+value.jugadores.ape_paterno+' '+value.jugadores.ape_materno;
            var fecha_expulsion = value.fixtures.fecha_nro;
            var fecha_actual = data.ficha.fecha_nro;
            var fecha_descanso = (fecha_expulsion + 1);
            var fecha_descanso_2 = (fecha_expulsion + 2);

            if ( (fecha_actual == fecha_descanso) || (fecha_actual == fecha_descanso_2) ) {
                $('#mdl_show_expulsados_local').append('<i class="ico-arbitro mr-1 text-danger"></i> <span class="text-danger">'+jugador+'</span> (Fecha: '+fecha_expulsion+')<br>');
            }

        });

    }else {
        $('#mdl_show_expulsados_local').empty();
    }

}

/** Mostrando expulsados del equipo visitante */
function getExpulsadosVisitante(data)
{
    if(data.expulsados_visitante) {

        $('#mdl_show_expulsados_visitante').empty();

        $.each(data.expulsados_visitante, function(key, value) {

            var jugador = value.jugadores.nombres+' '+value.jugadores.ape_paterno+' '+value.jugadores.ape_materno;
            var fecha_expulsion = value.fixtures.fecha_nro;
            var fecha_actual = data.ficha.fecha_nro;
            var fecha_descanso = (fecha_expulsion + 1);
            var fecha_descanso_2 = (fecha_expulsion + 2);

            if ( (fecha_actual == fecha_descanso) || (fecha_actual == fecha_descanso_2) ) {
                $('#mdl_show_expulsados_visitante').append('<i class="ico-arbitro mr-1 text-danger fa-lg"></i> <span class="text-danger">'+jugador+'</span> (Fecha: '+fecha_expulsion+')<br>');
            }

        });

    }else {
        $('#mdl_show_expulsados_visitante').empty();
    }
}


/** Actualizando encuentro */
function ajaxUpdateEncuentro(form) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var dataString = new FormData( $('#frmEditarEncuentro')[0] );

    $.ajax({
        url: flagUrl+'admin/fixtures/administrar-fixture/update-process',
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
            $('#btnActualizarEncuentro').prop("disabled", true);
        }
    })

    .done(function(data)
    {
        if(data.status === "updated-encuentro") {
            $("#fa_ue_guardar").show();
            $("#fa_ue_spinner").hide();
            $('#btnActualizarEncuentro').prop("disabled", false);

            $('#campo_'+data.ficha_id).html(data.campo);
            $('#fecha_'+data.ficha_id).html(data.partido_fecha);
            $('#hora_'+data.ficha_id).html(data.partido_hora);
            $('#goles_local_'+data.ficha_id).html(data.goles_local);
            $('#goles_visitante_'+data.ficha_id).html(data.goles_visitante);
            $('#status_'+data.ficha_id).html(data.estado);

            if (data.estado == 'finalizado') {
                $('#status_'+data.ficha_id).addClass('text-success');
                $('#status_'+data.ficha_id).removeClass('text-danger');
            }else {
                $('#status_'+data.ficha_id).addClass('text-danger');
                $('#status_'+data.ficha_id).removeClass('text-success');
            }


            $('#mdlEditarEncuentro').modal('hide');

            toastr.success('El encuentro ha sido actualizado', 'Success');
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

/** Mostrando tabla de posiciones */
function verTablaPosiciones(id)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: flagUrl+'admin/fixtures/administrar-fixture/tabla-posiciones/generar/'+id,
        dataType : 'json',
        type: 'GET',

        beforeSend: function()
        {

        }
    })

    .done(function(response)
    {
        $('#tabla-cuerpo').empty();

        // Generar las filas de la tabla de posiciones con los datos recibidos
        $.each(response, function (index, equipo) {
            var fila = '<tr>' +
                '<td>' + equipo.team + '</td>' +
                '<td class="text-center">' + equipo.played + '</td>' +
                '<td class="text-center">' + equipo.won + '</td>' +
                '<td class="text-center">' + equipo.tie + '</td>' +
                '<td class="text-center">' + equipo.lost + '</td>' +
                '<td class="text-center">' + equipo.favor + '</td>' +
                '<td class="text-center">' + equipo.against + '</td>' +
                '<td class="text-center">' + equipo.diff + '</td>' +
                '<td class="text-center">' + equipo.points + '</td>' +
                '</tr>';

            $('#tabla-cuerpo').append(fila);

            $('#mdlTablaPosiciones').modal('show');
        });

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
