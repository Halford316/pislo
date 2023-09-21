<div class="modal" id="mdlSponsors" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Seleccionar sponsor
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class=" float-right">
                    <a href="#" class="btn btn-danger btn-sm" onclick="nuevoSponsor()" title="Nuevo sponsor">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>
                </div>

                <p>Seleccione los sponsors para el torneo</p>

                <div class="table-responsive">
                    <table class="table w-100" id="tblSponsors">
                        <thead>
                            <tr class="bg-gray text-white">
                                <td></td>
                                <td>Sponsor</td>
                                <td>Descripción</td>
                                <td>Acciones</td>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times mr-1" aria-hidden="true"></i>
                    Cancelar
                </button>
                <button type="button" class="btn btn-danger" id="seleccionarSponsors">
                    Seleccionar
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Modal - Nuevo --}}
<div class="modal fade" id="mdlNuevoSponsor" tabindex="-1" role="dialog" aria-labelledby="NuevoSponsor" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <form action="" method="POST" id="frmNuevoSponsor" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header" >
                    <h5 class="modal-title">
                        <i class="fa fa-plus fa-lg mr-1" aria-hidden="true"></i>
                        Nuevo Sponsor
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
                                    <label for="nombre">Nombre del Sponsor:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="descripcion">Descripción:</label>
                                    <textarea name="descripcion" id="descripcion" class="form-control" cols="50" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cerrar
                        <i class="fa fa-window-close ml-2" aria-hidden="true"></i>
                    </button>

                    <button type="submit" class="btn btn-outline-danger" id="btnGuardarSponsor">
                        Guardar
                        <i class="fa fa-plus ml-1" id="fa_ns_guardar"></i>
                        <i class="fa fa-spinner fa-spin ml-1 hide" id="fa_ns_spinner" style="display: none"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- Modal - Editar --}}
<div class="modal fade" id="mdlEditarSponsor" tabindex="-1" role="dialog" aria-labelledby="EditarSponsor" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <form action="" method="POST" id="frmEditarSponsor" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <input type="hidden" name="mdl_us_ficha_id" id="mdl_us_ficha_id">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-edit fa-lg mr-1" aria-hidden="true"></i>
                        Editar sponsor
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
                                    <label for="mdl_us_nombre">Nombre del sponsor:</label>
                                    <input type="text" name="mdl_us_nombre" id="mdl_us_nombre" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="mdl_us_descripcion">Descripción:</label>
                                    <textarea name="mdl_us_descripcion" id="mdl_us_descripcion" class="form-control" cols="50" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cerrar
                        <i class="fa fa-window-close ml-2" aria-hidden="true"></i>
                    </button>

                    <button type="submit" class="btn btn-outline-danger" id="btnActualizarSponsor">
                        Guardar
                        <i class="fa fa-floppy-o ml-1" id="fa_us_guardar"></i>
                        <i class="fa fa-spinner fa-spin ml-1 hide" id="fa_us_spinner" style="display: none"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
