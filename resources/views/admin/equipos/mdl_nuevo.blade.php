{{-- Modal - Nuevo --}}
<div class="modal fade" id="mdlNuevo" tabindex="-1" role="dialog" aria-labelledby="Nuevo" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">

            <form action="" method="POST" id="frmNuevo" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header" >
                    <h5 class="modal-title">
                        <i class="fa fa-plus fa-lg mr-1" aria-hidden="true"></i>
                        Nuevo equipo
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ffffff;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-body" style="font-size:13px;">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="nombre">Nombre del equipo:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="foto_adjunto">
                                        Foto del equipo:
                                    </label>
                                    <input type="file" class="form-control" id="foto_adjunto" name="foto_adjunto" onchange="return fileValidation(this, 'all')">
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cerrar
                        <i class="fa fa-times ml-2" aria-hidden="true"></i>
                    </button>

                    <button type="submit" class="btn btn-outline-danger" id="btnGuardar">
                        Guardar
                        <i class="fa fa-check-circle ml-1" id="fa_n_guardar"></i>
                        <i class="fa fa-spinner fa-spin ml-1 hide" id="fa_n_spinner" style="display: none"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- Modal - Editar --}}
<div class="modal fade" id="mdlEditarEquipo" tabindex="-1" role="dialog" aria-labelledby="Editar" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">

            <form action="" method="POST" id="frmEditar" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <input type="hidden" name="mdl_ue_equipo_id" id="mdl_ue_equipo_id">

                <div class="modal-header" >
                    <h5 class="modal-title">
                        <i class="fa fa-plus fa-lg mr-1" aria-hidden="true"></i>
                        Editar equipo
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ffffff;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-body" style="font-size:13px;">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="nombre">Nombre del equipo:</label>
                                    <input type="text" name="mdl_ue_nombre" id="mdl_ue_nombre" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="mdl_imagePreview" class="ml-4 mt-4">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="foto_adjunto">
                                        Foto del equipo:
                                    </label>
                                    <input type="file" class="form-control" id="mdl_ue_foto_adjunto" name="mdl_ue_foto_adjunto" onchange="return fileValidation(this, 'all')">
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cerrar
                        <i class="fa fa-times ml-2" aria-hidden="true"></i>
                    </button>

                    <button type="submit" class="btn btn-outline-danger" id="btnActualizarEquipo">
                        Guardar
                        <i class="fa fa-check-circle ml-1" id="fa_ue_guardar"></i>
                        <i class="fa fa-spinner fa-spin ml-1 hide" id="fa_ue_spinner" style="display: none"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
