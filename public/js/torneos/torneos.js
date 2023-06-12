
$('#agregarCampos').on('click', function(ev){

    $('#mdlCampos').modal('show');

});

$('#agregarSponsors').on('click', function(ev){

    $('#mdlSponsors').modal('show');

});

/** Store  */
$('#frmTorneo').on('submit', function(ev) {
    ev.preventDefault();

    ajaxStore(this);
});

/** Update  */
$('#frmEditar').on('submit', function(ev){
    ev.preventDefault();

    ajaxUpdate(this);
});

/** Guardando */
function ajaxStore(form) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var dataString = new FormData(form);

    $.ajax({
        url: '/admin/torneos/store-process',
        datatType : 'json',
        type: 'POST',
        data: dataString,
        cache: false,
        contentType: false,
        processData: false,

        beforeSend:function(){
            $("#fa_guardar").hide();
            $("#fa_spinner").show();
            $('#btnGuardar').prop("disabled", true);
        },

        success:function(result) {
            $("#fa_guardar").show();
            $("#fa_spinner").hide();
            $('#btnGuardar').prop("disabled", false);

            if (result.status=='no-hay-campos') {
                swal.fire("No hay campos", "No hay campos asignados al torneo. Debe asignar al menos un campo para registrar correctamente el torneo.", "warning");
            }else {
                if (result.success) {

                    Swal.fire(
                        'Registro exitoso!',
                        'Se ha registrado correctamente el torneo',
                        'success'
                    )

                    $("#frmTorneo")[0].reset();

                }else {
                    swal.fire("Mensaje", "No se puede registrar el torneo", "warning");
                }
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

/** Seleccionar campos */
function mostrarElementosSeleccionados() {
    // Obtiene todos los elementos seleccionados en la ventana modal
    const elementosSeleccionados = document.querySelectorAll('#mdlCampos input[type=checkbox]:checked');
    const total = (elementosSeleccionados.length);

    // Crea un array con los valores de los elementos seleccionados
    const valoresSeleccionados = [];
    var campo = '';

    if (total) {

        elementosSeleccionados.forEach((elemento) => {

            var cadena = elemento.value;
            var campos = cadena.split('-');
            var id = campos[0];
            var campo_nombre = campos[1];

            campo += '<li id="row_'+id+'" class="alert alert-secondary">';
            campo += campo_nombre;
            campo += '<input type="hidden" name="campos[]" id="campo_'+id+'" value="'+id+'" />'
            campo += '<button type="button" name="remove" id="'+id+'" class="btn btn-danger btn-sm btn_remove float-right">'+
                    '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</button>';
            campo += '</li>';

            $('#selected-campos').empty();
        });

        $('#campo_existe').val(total);
        $('#mdlCampos').modal('hide');
        $('#selected-campos').append(campo);
    }else {
        $('#selected-campos').empty();
        $('#campo_existe').val('');
    }


    $('.btn_remove').on('click', function() {
        var button_id = $(this).attr("id");
        var campo_existe = $('#campo_existe').val();

        $('#row_'+button_id+'').remove();
        $('#campo_existe').val(campo_existe-1);
    });

}



// Obtiene el botón que cierra la ventana modal
const seleccionar = document.getElementById('seleccionarCampos');

// Agrega un evento de clic al botón que cierra la ventana modal
seleccionar.addEventListener('click', () => {

    // Muestra los elementos seleccionados en la ventana principal
    mostrarElementosSeleccionados();
});


/** Seleccionar sponsors */
function mostrarElementosSeleccionados2() {

    const elementosSeleccionados = document.querySelectorAll('#mdlSponsors input[type=checkbox]:checked');
    const total = (elementosSeleccionados.length);
    var sponsor = '';

    if (total) {

        elementosSeleccionados.forEach((elemento) => {

            var cadena = elemento.value;
            var sponsors = cadena.split('-');
            var id = sponsors[0];
            var sponsor_nombre = sponsors[1];

            sponsor += '<li id="row_'+id+'" class="alert alert-secondary">';
            sponsor += sponsor_nombre;
            sponsor += '<input type="hidden" name="sponsors[]" id="sponsor_'+id+'" value="'+id+'" />'
            sponsor += '<button type="button" name="remove" id="'+id+'" class="btn btn-danger btn-sm btn_remove float-right">'+
                    '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</button>';
            sponsor += '</li>';

            $('#selected-sponsors').empty();
        });

        $('#sponsor_existe').val(total);
        $('#mdlSponsors').modal('hide');
        $('#selected-sponsors').append(sponsor);
    }else {
        $('#selected-sponsors').empty();
    }


    $('.btn_remove').on('click', function() {
        var button_id = $(this).attr("id");
        var sponsor_existe = $('#sponsor_existe').val();

        $('#row_'+button_id+'').remove();
        $('#sponsor_existe').val(sponsor_existe-1);
    });

}


const seleccionar_sponsor = document.getElementById('seleccionarSponsors');

seleccionar_sponsor.addEventListener('click', () => {
    mostrarElementosSeleccionados2();
});

/** Ver listado de equipos */
function verEquipos(id)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: flagUrl+'admin/torneos/show/'+id,
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
        $("#tblEquipos").dataTable().fnDestroy();

        /* Cargando Equipos  */
        $('#tblEquipos').DataTable( {
            "searching" : false,
            "paging" : false,
            "info": false,
            bSort: false,
            "ajax": flagUrl+'admin/torneos/datatable/'+id,

            "aoColumnDefs": [
                { 'bSortable': false, 'aTargets': [0,1,2,3,4] },
                { className: "text-center", "targets": [0,1,3,4] }
            ],
            "columns" : [
                { data: 'id' },
                { data: 'nombre' },
                { data: 'nro_jugadores' },
                { data: 'status' },
                { data: 'fecha_reg' },
                { data: 'fecha_mod' }
            ],

            language: {
                    "sProcessing":     "Procesando...",
                    "sEmptyTable":     "No tiene ningún equipos registrado en el torneo",
                    "sLoadingRecords": "Cargando...",
                }

        } );
        /** ************************************************************************************ */

    })

    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        console.log(jqXHR.statusText);
    });

    $('#mdlEquipos').modal('show');
}


/** Actualizando torneo */
function ajaxUpdate(form) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var dataString = new FormData( $('#frmEditar')[0] );

    $.ajax({
        url: flagUrl+'admin/torneos/update-process',
        dataType : 'json',
        type: 'POST',
        data: dataString,
        cache: false,
        contentType: false,
        processData: false,

        beforeSend: function()
        {
            $("#fa_guardar").hide();
            $("#fa_spinner").show();
            $('#btnActualizarTorneo').prop("disabled", true);
        }
    })

    .done(function(data)
    {
        if(data.status === "updated-torneo") {
            $("#fa_guardar").show();
            $("#fa_spinner").hide();
            $('#btnActualizarTorneo').prop("disabled", false);

            toastr.success('El torneo ha sido actualizado satisfactoriamente', 'Success');
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
