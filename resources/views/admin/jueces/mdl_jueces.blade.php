{{-- Modal - Nuevo --}}
<div class="modal fade" id="mdlNuevoJuez" tabindex="-1" role="dialog" aria-labelledby="Nuevo" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content small">

            <form action="" method="POST" id="frmNuevoJuez" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="modal-header" >
                    <h6 class="modal-title">
                        <i class="fa fa-user-plus fa-lg mr-1" aria-hidden="true"></i>
                        Nuevo Juez de línea
                    </h6>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        {{-- Columna izquierda --}}
                        <div class="col-sm-9">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="ape_paterno">Ape paterno:</label>
                                        <input type="text" name="ape_paterno" id="ape_paterno" class="form-control" required />
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="ape_materno">Ape materno:</label>
                                        <input type="text" name="ape_materno" id="ape_materno" class="form-control" required />
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="nombres">Nombres:</label>
                                        <input type="text" name="nombres" id="nombres" class="form-control" required />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    {{-- <div class="col-sm-4">
                                        <label for="fecha_nac">Fecha Nac:</label>
                                        <input type="date" name="fecha_nac" id="fecha_nac" placeholder="dd/mm/aaaa" class="form-control" required />
                                    </div> --}}

                                    <div class="col-sm-6">
                                        <label for="telefono">Teléfono:</label>
                                        <input type="text" name="telefono" id="telefono" class="form-control" required />
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="sexo">Sexo:</label>
                                        <select name="sexo" id="sexo" class="form-control" required>
                                            <option value="">-- Seleccione --</option>
                                            @foreach ($sexos as $llave=>$valor)
                                            <option value="{{ $llave }}">{{ $valor }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Columna derecha avatar --}}
                        <div class="col-sm-3">
                            <div id="imagePreview" class="ml-4 mt-4"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="foto_adjunto">
                                    Foto del juez:
                                </label>
                                <input type="file" class="form-control" id="foto_adjunto" name="foto_adjunto" onchange="return fileValidation(this, 'img')" required>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cerrar
                        <i class="fa fa-window-close ml-2" aria-hidden="true"></i>
                    </button>

                    <button type="submit" class="btn btn-outline-danger" id="btnGuardarjuez">
                        Guardar
                        <i class="fa fa-plus ml-1" id="fa_na_guardar"></i>
                        <i class="fa fa-spinner fa-spin ml-1 hide" id="fa_na_spinner" style="display: none"></i>
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>



{{-- Modal - Editar --}}
<div class="modal fade" id="mdlEditarJuez" tabindex="-1" role="dialog" aria-labelledby="Editar" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content small">

            <form action="" method="POST" id="frmEditarJuez" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <input type="hidden" name="mdl_uj_juez_id" id="mdl_uj_juez_id">

                <div class="modal-header" >
                    <h6 class="modal-title">
                        <i class="fa fa-user-plus fa-lg mr-1" aria-hidden="true"></i>
                        Editar juez de línea
                    </h6>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        {{-- Columna izquierda --}}
                        <div class="col-sm-9">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="mdl_uj_ape_paterno">Ape paterno:</label>
                                        <input type="text" name="mdl_uj_ape_paterno" id="mdl_uj_ape_paterno" class="form-control" required />
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="mdl_uj_ape_materno">Ape materno:</label>
                                        <input type="text" name="mdl_uj_ape_materno" id="mdl_uj_ape_materno" class="form-control" required />
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="mdl_uj_nombres">Nombres:</label>
                                        <input type="text" name="mdl_uj_nombres" id="mdl_uj_nombres" class="form-control" required />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    {{-- <div class="col-sm-4">
                                        <label for="mdl_uj_fecha_nac">Fecha Nac:</label>
                                        <input type="date" name="mdl_uj_fecha_nac" id="mdl_uj_fecha_nac" placeholder="dd/mm/aaaa" class="form-control" required />
                                    </div> --}}

                                    <div class="col-sm-6">
                                        <label for="mdl_uj_telefono">Teléfono:</label>
                                        <input type="text" name="mdl_uj_telefono" id="mdl_uj_telefono" class="form-control" required />
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="mdl_uj_sexo">Sexo:</label>
                                        <select name="mdl_uj_sexo" id="mdl_uj_sexo" class="form-control" required>
                                            <option value="">-- Seleccione --</option>
                                            @foreach ($sexos as $llave=>$valor)
                                            <option value="{{ $llave }}">{{ $valor }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Columna derecha avatar --}}
                        <div class="col-sm-3">
                            <div id="mdl_imagePreview" class="ml-4 mt-4">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            {{-- <div class="col-sm-3">
                                <label for="mdl_uj_status">
                                    Status
                                </label>
                                <select name="mdl_uj_status" id="mdl_uj_status" class="form-control">
                                    <option value="">-- Seleccione una opción --</option>
                                    @foreach ($estados as $llave=>$valor)
                                    <option value="{{ $llave }}">{{ $valor }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="col-sm-12">
                                <label for="mdl_uj_foto_adjunto">
                                    Foto del juez:
                                </label>
                                <input type="file" class="form-control" id="mdl_uj_foto_adjunto" name="mdl_uj_foto_adjunto" onchange="return fileValidation(this, 'img')">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cerrar
                        <i class="fa fa-window-close ml-2" aria-hidden="true"></i>
                    </button>

                    <button type="submit" class="btn btn-outline-danger" id="btnActualizarjuez">
                        Guardar
                        <i class="fa fa-plus ml-1" id="fa_uj_guardar"></i>
                        <i class="fa fa-spinner fa-spin ml-1 hide" id="fa_uj_spinner" style="display: none"></i>
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
