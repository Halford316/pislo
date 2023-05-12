function fileValidation(file, tipo) {
    //var fileInput = document.getElementById('doc_adjunto');

    var fileInput = file;
    var tipo
    var filePath = fileInput.value;
    var fi = file;

    if (tipo=='doc') {
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
        var msg_error = "Solo se permite archivo con extensiones .pdf, .jpg, .jpeg y .png";
        var max_size = "3048";
        var msg_error_size = "Archivo máximo de subida: 2 MB";
    }

    if (tipo=='img') {
        var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
        var msg_error = "Solo se permite archivo con extensiones .jpg";
        var max_size = "2048";
        var msg_error_size = "Archivo máximo de subida: 2 MB";
    }

    if (!allowedExtensions.exec(filePath)) {
        //alert(msg_error);
        swal({
            title: 'Error',
            text: msg_error,
            icon: 'error'
        });
        fileInput.value = '';
        return false;
    }
    else
    {
        if (fileInput.files.length > 0) {
            for (var i = 0; i <= fi.files.length - 1; i++) {

                var fsize = fi.files.item(i).size;
                var file = Math.round((fsize / 1024));
                // The size of the file.
                if (file >= max_size) {
                    //alert(msg_error_size);
                    swal({
                        title: 'Error',
                        text: msg_error_size,
                        icon: 'error'
                    });
                    fileInput.value = '';
                    return false;
                }
            }
        }

        if (tipo=='img') {
            // Image preview
            if (fileInput.files && fileInput.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(
                        'imagePreview').innerHTML =
                        '<img src="' + e.target.result
                        + '" width="114" height="114" border="1" style="display: inline;" class="rounded" />';

                    document.getElementById(
                        'mdl_imagePreview').innerHTML =
                        '<img src="' + e.target.result
                        + '" width="114" height="114" border="1" style="display: inline;" class="rounded" />';
                };

                reader.readAsDataURL(fileInput.files[0]);
            }
        }
    }
}
