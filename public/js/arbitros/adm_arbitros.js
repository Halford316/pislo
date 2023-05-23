/** Store  */
$('#frmNuevoArbitro').on('submit', function(ev) {
    ev.preventDefault();

    ajaxStore(this);
});

/** Update  */
$('#frmEditarArbitro').on('submit', function(ev){
    ev.preventDefault();

    ajaxUpdate(this);
});


function nuevoArbitro()
{
    //$("#frmNuevoArbitro")[0].reset();

    var foto_default = "/images/foto_default.png";
    document.getElementById('imagePreview').innerHTML =
        '<img src="'+foto_default+'" width="114" height="114" border="1" class="rounded" />';

    $('#mdlNuevoArbitro').modal('show');
}


/** Guardando Arbitro */
function ajaxStore(form) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var dataString = new FormData(form);

    $.ajax({
        url: flagUrl+'admin/arbitros/store-process',
        datatType : 'json',
        type: 'POST',
        data: dataString,
        cache: false,
        contentType: false,
        processData: false,

        beforeSend:function(){
            $("#fa_na_guardar").hide();
            $("#fa_na_spinner").show();
            $('#btnGuardarArbitro').prop("disabled", true);
        },

        success:function(result) {
            $("#fa_na_guardar").show();
            $("#fa_na_spinner").hide();
            $('#btnGuardarArbitro').prop("disabled", false);

            if(result.success) {

                Swal.fire(
                    'Registro exitoso!',
                    'Se ha registrado correctamente el Arbitro.',
                    'success'
                )

                $("#frmNuevoArbitro")[0].reset();
                $('#mdlNuevoArbitro').modal('hide');
                $('#tblArbitros').DataTable().ajax.reload();


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
function editarArbitro(id)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: flagUrl+'admin/arbitros/show/'+id,
        dataType : 'json',
        type: 'GET',
        cache: false,
        processData: true,

        beforeSend: function()
        {
            //console.log('Mostrando Arbitro con ID: '+id);
        }
    })

    .done(function(data)
    {
        $('#mdl_ua_arbitro_id').val(data.ficha.id);
        $('#mdl_ua_ape_paterno').val(data.ficha.ape_paterno);
        $('#mdl_ua_ape_materno').val(data.ficha.ape_materno);
        $('#mdl_ua_nombres').val(data.ficha.nombres);
        $('#mdl_ua_fecha_nac').val(data.ficha.fecha_nac);
        $('#mdl_ua_telefono').val(data.ficha.telefono);
        $('#mdl_ua_sexo').val(data.ficha.sexo);
        $('#mdl_ua_foto_adjunto').val('');

        var foto = data.ficha.foto;
        document.getElementById('mdl_imagePreview').innerHTML =
        '<img src="/storage/arbitro_fotos/'+foto+'" width="114" height="114" border="1" class="rounded" />';

    })

    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        console.log(jqXHR.statusText);
    });

    $('#mdlEditarArbitro').modal('show');
}


/** Actualizando Arbitro */
function ajaxUpdate(form) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var dataString = new FormData( $('#frmEditarArbitro')[0] );

    $.ajax({
        url: flagUrl+'admin/arbitros/update-process',
        dataType : 'json',
        type: 'POST',
        data: dataString,
        cache: false,
        contentType: false,
        processData: false,

        beforeSend: function()
        {
            $("#fa_ua_guardar").hide();
            $("#fa_ua_spinner").show();
            $('#btnActualizarArbitro').prop("disabled", true);
        }
    })

    .done(function(data)
    {
        if(data.status === "updated-arbitro") {
            $("#fa_ua_guardar").show();
            $("#fa_ua_spinner").hide();
            $('#btnActualizarArbitro').prop("disabled", false);

            $('#mdlEditarArbitro').modal('hide');
            $('#tblArbitros').DataTable().ajax.reload(null, false);

            toastr.success('El arbitro ha sido actualizado', 'Success');
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
