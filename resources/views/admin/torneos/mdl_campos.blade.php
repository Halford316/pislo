<div class="modal" id="mdlCampos" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Seleccionar campo
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class=" float-right">
                    <a href="#" class="btn btn-danger btn-sm" onclick="nuevoCampo()" title="Nuevo campo">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>
                </div>

                <p>Seleccione los campos al torneo</p>

                <div class="table-responsive">
                    <table class="table table-hover w-100" id="tblCampos">
                        <thead>
                            <tr class="bg-gray text-white">
                                <td></td>
                                <td>Campo</td>
                                <td>Tipo</td>
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
                <button type="button" class="btn btn-danger" id="seleccionarCampos">
                    Seleccionar
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Modal - Nuevo --}}
<div class="modal fade" id="mdlNuevoCampo" tabindex="-1" role="dialog" aria-labelledby="NuevoCampo" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <form action="" method="POST" id="frmNuevoCampo" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header" >
                    <h5 class="modal-title">
                        <i class="fa fa-plus fa-lg mr-1" aria-hidden="true"></i>
                        Nuevo Campo
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
                                    <label for="nombre">Nombre del campo:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="tipo_campo">Tipo de campo:</label>
                                    <select name="tipo_campo" id="tipo_campo" class="form-control" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach ($campo_tipos as $llave=>$valor)
                                        <option value="{{ $llave }}">{{ $valor }}</option>
                                        @endforeach
                                    </select>
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

                    <button type="submit" class="btn btn-outline-danger" id="btnGuardarCampo">
                        Guardar
                        <i class="fa fa-plus ml-1" id="fa_nc_guardar"></i>
                        <i class="fa fa-spinner fa-spin ml-1 hide" id="fa_nc_spinner" style="display: none"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- Modal - Editar --}}
<div class="modal fade" id="mdlEditarCampo" tabindex="-1" role="dialog" aria-labelledby="EditarCampo" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <form action="" method="POST" id="frmEditarCampo" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <input type="hidden" name="mdl_uc_ficha_id" id="mdl_uc_ficha_id">

                <div class="modal-header" >
                    <h5 class="modal-title">
                        <i class="fa fa-plus fa-lg mr-1" aria-hidden="true"></i>
                        Editar campo
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
                                    <label for="mdl_uc_nombre">Nombre del campo:</label>
                                    <input type="text" name="mdl_uc_nombre" id="mdl_uc_nombre" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="mdl_uc_tipo_campo">Tipo de campo:</label>
                                    <select name="mdl_uc_tipo_campo" id="mdl_uc_tipo_campo" class="form-control" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach ($campo_tipos as $llave=>$valor)
                                        <option value="{{ $llave }}">{{ $valor }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="mdl_uc_descripcion">Descripción:</label>
                                    <textarea name="mdl_uc_descripcion" id="mdl_uc_descripcion" class="form-control" cols="50" rows="2"></textarea>
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

                    <button type="submit" class="btn btn-outline-danger" id="btnActualizarCampo">
                        Guardar
                        <i class="fas fa-floppy-o ml-1" id="fa_uc_guardar"></i>
                        <i class="fa fa-spinner fa-spin ml-1 hide" id="fa_uc_spinner" style="display: none"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
