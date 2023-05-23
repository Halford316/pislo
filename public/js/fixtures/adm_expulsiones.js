$(document).ready(function(){

    $('#frmExpulsiones').on('submit', function(ev){
        ev.preventDefault();

        ajaxStoreExpulsiones(this);
    });

});



/** Guardando */
function ajaxStoreExpulsiones(form) {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var dataString = new FormData( $('#frmExpulsiones')[0] );

    $.ajax({
        url: '/admin/fixtures/administrar-fixture/expulsiones/store-process',
        datatType : 'json',
        type: 'POST',
        data: dataString,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function()
        {
            $('#btnGuardarExpulsion').prop("disabled", true);
        }
    })
    .done(function(result)
    {
        $('#btnGuardarExpulsion').prop("disabled", false);

        if(result.success) {

            toastr.success('Se agregó correctamente al jugador al listado de expulsados', 'Success');
            toastr.options.timeOut = 3000;
            toastr.options.progressBar = true;

            $('#tblExpulsiones').DataTable().ajax.reload();
            $("#frmExpulsiones")[0].reset();
        }

    })
    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        var errors = $.parseJSON(jqXHR.responseText);
        console.log(errors);

        var errorsHtml = '';
        $.each(errors['errors'], function (index, value) {
            errorsHtml += '- ' + value;
        });

        if(jqXHR.status === 401) {
            errorsHtml = 'Error en la autenticación.';
        }

        if(jqXHR.status === 500) {
            errorsHtml = 'Hubo un error en el sistema.';
        }

        swal("Error en el registro", errorsHtml, "warning");
    });

}


/** Eliminado registro */
function eliminarExpulsion(id) {

    swal({
        title: '¿Desea eliminar la observación?',
        //text: "Ud. enviará una constancia adjunto en pdf al correo electrónico del alumno.",
        icon: "warning",
        buttons: [
          'No, cancelar',
          'Sí, eliminar!'
        ],
        dangerMode: true,
    })
    .then(function(isConfirm) {
        if (isConfirm) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: flagUrl+'admweb/bachiller/Expulsiones/delete/'+id,
                dataType : 'json',
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,

                beforeSend: function()
                {
                    $('#btnEliminar').prop("disabled", true);
                    //console.log('Enviando...');
                    $("#loader").removeClass('hidden');
                }
            })

            .done(function(data)
            {
                $("#loadStatus").hide();
                $('#btnEliminar').prop("disabled", false);
                $("#loader").addClass('hidden');

                swal({
                    title: 'Observación eliminada!',
                    text: 'La observación ha sido eliminada correctamente del sistema.',
                    icon: 'success'
                }).then(function() {
                    //console.log('Ejecutando...');
                });

                $('#tblExpulsiones').DataTable().ajax.reload();

            })

            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                //console.log(jqXHR.statusText);
                console.log(xhr.responseText);
            });

        }

    });
}



