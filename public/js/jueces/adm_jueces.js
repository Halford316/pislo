/** Store  */
$('#frmNuevoJuez').on('submit', function(ev) {
    ev.preventDefault();

    ajaxStore(this);
});

/** Update  */
$('#frmEditarJuez').on('submit', function(ev){
    ev.preventDefault();

    ajaxUpdate(this);
});


function nuevoJuez()
{
    var foto_default = "/images/foto_default.png";
    document.getElementById('imagePreview').innerHTML =
        '<img src="'+foto_default+'" width="114" height="114" border="1" class="rounded" />';

    $('#mdlNuevoJuez').modal('show');
}


/** Guardando Juez */
function ajaxStore(form) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var dataString = new FormData(form);

    $.ajax({
        url: flagUrl+'admin/jueces/store-process',
        datatType : 'json',
        type: 'POST',
        data: dataString,
        cache: false,
        contentType: false,
        processData: false,

        beforeSend:function(){
            $("#fa_na_guardar").hide();
            $("#fa_na_spinner").show();
            $('#btnGuardarJuez').prop("disabled", true);
        },

        success:function(result) {
            $("#fa_na_guardar").show();
            $("#fa_na_spinner").hide();
            $('#btnGuardarJuez').prop("disabled", false);

            if(result.success) {

                Swal.fire(
                    'Registro exitoso!',
                    'Se ha registrado correctamente el juez de línea.',
                    'success'
                )

                $("#frmNuevoJuez")[0].reset();
                $('#mdlNuevoJuez').modal('hide');
                $('#tblJueces').DataTable().ajax.reload();


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
function editarJuez(id)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: flagUrl+'admin/jueces/show/'+id,
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
        $('#mdl_uj_juez_id').val(data.ficha.id);
        $('#mdl_uj_ape_paterno').val(data.ficha.ape_paterno);
        $('#mdl_uj_ape_materno').val(data.ficha.ape_materno);
        $('#mdl_uj_nombres').val(data.ficha.nombres);
        //$('#mdl_uj_fecha_nac').val(data.ficha.fecha_nac);
        $('#mdl_uj_telefono').val(data.ficha.telefono);
        $('#mdl_uj_sexo').val(data.ficha.sexo);
        $('#mdl_uj_foto_adjunto').val('');

        var foto = data.ficha.foto;
        document.getElementById('mdl_imagePreview').innerHTML =
        '<img src="/storage/jueces_fotos/'+foto+'" width="114" height="114" border="1" class="rounded" />';

    })

    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        console.log(jqXHR.statusText);
    });

    $('#mdlEditarJuez').modal('show');
}


/** Actualizando juez */
function ajaxUpdate(form) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var dataString = new FormData( $('#frmEditarJuez')[0] );

    $.ajax({
        url: flagUrl+'admin/jueces/update-process',
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
            $('#btnActualizarJuez').prop("disabled", true);
        }
    })

    .done(function(data)
    {
        if(data.status === "updated-juez") {
            $("#fa_uj_guardar").show();
            $("#fa_uj_spinner").hide();
            $('#btnActualizarJuez').prop("disabled", false);

            $('#mdlEditarJuez').modal('hide');
            $('#tblJueces').DataTable().ajax.reload(null, false);

            toastr.success('El juez de línea ha sido actualizado', 'Success');
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
