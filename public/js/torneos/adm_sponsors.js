/** Store  */
$('#frmNuevoSponsor').on('submit', function(ev) {
    ev.preventDefault();

    storeSponsor(this);
});

/** Update  */
$('#frmEditarSponsor').on('submit', function(ev){
    ev.preventDefault();

    updateSponsor(this);
});

function nuevoSponsor()
{
    $('#mdlNuevoSponsor').modal('show');
}

/** Guardando */
function storeSponsor(form) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var dataString = new FormData(form);

    $.ajax({
        url: flagUrl+'admin/torneos/sponsors/store-process',
        datatType : 'json',
        type: 'POST',
        data: dataString,
        cache: false,
        contentType: false,
        processData: false,

        beforeSend:function(){
            $("#fa_ns_guardar").hide();
            $("#fa_ns_spinner").show();
            $('#btnGuardarSponsor').prop("disabled", true);
        },

        success:function(result) {
            $("#fa_ns_guardar").show();
            $("#fa_ns_spinner").hide();
            $('#btnGuardarSponsor').prop("disabled", false);

            if(result.success) {

                Swal.fire(
                    'Sponsor registrado!',
                    'Se ha registrado correctamente el sponsor.',
                    'success'
                )

                $("#frmNuevoSponsor")[0].reset();
                $('#mdlNuevoSponsor').modal('hide');
                $('#tblSponsors').DataTable().ajax.reload();

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
function editarSponsor(id)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: flagUrl+'admin/torneos/sponsors/show/'+id,
        dataType : 'json',
        type: 'GET',
        cache: false,
        processData: true,

        beforeSend: function()
        {
            //console.log('Mostrando Sponsor con ID: '+id);
        }
    })

    .done(function(data)
    {
        $('#mdl_us_ficha_id').val(data.ficha.id);
        $('#mdl_us_nombre').val(data.ficha.nombre);
        $('#mdl_us_descripcion').val(data.ficha.descripcion);

    })

    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        console.log(jqXHR.statusText);
    });

    $('#mdlEditarSponsor').modal('show');
}


/** Actualizando */
function updateSponsor(form) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var dataString = new FormData( $('#frmEditarSponsor')[0] );

    $.ajax({
        url: flagUrl+'admin/torneos/sponsors/update-process',
        dataType : 'json',
        type: 'POST',
        data: dataString,
        cache: false,
        contentType: false,
        processData: false,

        beforeSend: function()
        {
            $("#fa_us_guardar").hide();
            $("#fa_us_spinner").show();
            $('#btnActualizarSponsor').prop("disabled", true);
        }
    })

    .done(function(data)
    {
        if(data.status === "updated-sponsor") {
            $("#fa_us_guardar").show();
            $("#fa_us_spinner").hide();
            $('#btnActualizarSponsor').prop("disabled", false);

            $('#mdlEditarSponsor').modal('hide');
            $('#tblSponsors').DataTable().ajax.reload(null, false);

            toastr.success('El sponsor ha sido actualizado', 'Success');
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


/** Eliminado registro */
function eliminarSponsor(id) {

    swal.fire({
        title: '¿Desea eliminar el sponsor?',
        text: "Una vez eliminado no se podrá recuperar la información.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar!'
    })
    .then((result) => {
        if (result.isConfirmed) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: flagUrl+'admin/torneos/sponsors/delete/'+id,
                dataType : 'json',
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,

                beforeSend: function()
                {
                    $('#btnSend').prop("disabled", true);
                    $("#loader").removeClass('hidden');
                }
            })

            .done(function(data)
            {
                $("#loadStatus").hide();
                $('#btnSend').prop("disabled", false);
                $("#loader").addClass('hidden');

                if(data.status === "deleted") {
                    $('#tblSponsors').DataTable().ajax.reload();
                }

                swal.fire({
                    title: 'Registro eliminado!',
                    text: 'El registro ha sido eliminado correctamente.',
                    icon: 'success'
                }).then(function() {

                });
            })

            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                console.log(xhr.responseText);
            });
        }
    });
}

