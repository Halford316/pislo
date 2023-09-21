/** Store  */
$('#frmNuevoCampo').on('submit', function(ev) {
    ev.preventDefault();

    storeCampo(this);
});

/** Update  */
$('#frmEditarCampo').on('submit', function(ev){
    ev.preventDefault();

    updateCampo(this);
});

function nuevoCampo()
{
    $('#mdlNuevoCampo').modal('show');
}

/** Guardando */
function storeCampo(form) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var dataString = new FormData(form);

    $.ajax({
        url: flagUrl+'admin/torneos/campos/store-process',
        datatType : 'json',
        type: 'POST',
        data: dataString,
        cache: false,
        contentType: false,
        processData: false,

        beforeSend:function(){
            $("#fa_nc_guardar").hide();
            $("#fa_nc_spinner").show();
            $('#btnGuardarCampo').prop("disabled", true);
        },

        success:function(result) {
            $("#fa_nc_guardar").show();
            $("#fa_nc_spinner").hide();
            $('#btnGuardarCampo').prop("disabled", false);

            if(result.success) {

                Swal.fire(
                    'Campo registrado!',
                    'Se ha registrado correctamente el campo.',
                    'success'
                )

                $("#frmNuevoCampo")[0].reset();
                $('#mdlNuevoCampo').modal('hide');
                $('#tblCampos').DataTable().ajax.reload();

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
function editarCampo(id)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: flagUrl+'admin/torneos/campos/show/'+id,
        dataType : 'json',
        type: 'GET',
        cache: false,
        processData: true,

        beforeSend: function()
        {
            //console.log('Mostrando campo con ID: '+id);
        }
    })

    .done(function(data)
    {
        $('#mdl_uc_ficha_id').val(data.ficha.id);
        $('#mdl_uc_nombre').val(data.ficha.nombre);
        $('#mdl_uc_tipo_campo').val(data.ficha.tipo_campo);
        $('#mdl_uc_descripcion').val(data.ficha.descripcion);

    })

    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        console.log(jqXHR.statusText);
    });

    $('#mdlEditarCampo').modal('show');
}


/** Actualizando */
function updateCampo(form) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var dataString = new FormData( $('#frmEditarCampo')[0] );

    $.ajax({
        url: flagUrl+'admin/torneos/campos/update-process',
        dataType : 'json',
        type: 'POST',
        data: dataString,
        cache: false,
        contentType: false,
        processData: false,

        beforeSend: function()
        {
            $("#fa_uc_guardar").hide();
            $("#fa_uc_spinner").show();
            $('#btnActualizarCampo').prop("disabled", true);
        }
    })

    .done(function(data)
    {
        if(data.status === "updated-campo") {
            $("#fa_uc_guardar").show();
            $("#fa_uc_spinner").hide();
            $('#btnActualizarCampo').prop("disabled", false);

            $('#mdlEditarCampo').modal('hide');
            $('#tblCampos').DataTable().ajax.reload(null, false);

            toastr.success('El campo ha sido actualizado', 'Success');
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
function eliminarCampo(id) {

    swal.fire({
        title: '¿Desea eliminar el campo?',
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
                url: flagUrl+'admin/torneos/campos/delete/'+id,
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
                    $('#tblCampos').DataTable().ajax.reload();
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

