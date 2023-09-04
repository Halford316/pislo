{{-- Modal - listando jugadores --}}
<div class="modal fade" id="mdlJugadores" tabindex="-1" role="dialog" aria-labelledby="Nuevo" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">

        <div class="modal-content">

            <div class="modal-header" >
                <h6 class="modal-title">
                    <i class="fa fa-users fa-lg mr-1" aria-hidden="true"></i>
                    Jugadores registrados
                </h6>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="">
                    <a href="#" class="btn btn-danger btn-sm" onclick="nuevoJugador()">
                        <i class="fa fa-user-plus mr-1" aria-hidden="true"></i>
                        Nuevo jugador
                    </a>
                </div>

                <table class="table w-100 small" id="tblJugadores">
                    <thead>
                        <tr class="text-center bg-dark">
                            <td class="text-white">ID</td>
                            <td class="text-white">Foto</td>
                            <td class="text-white">Nombre</td>
                            <td class="text-white">Edad</td>
                            <td class="text-white">Teléfono</td>
                            <td class="text-white">Status</td>
                            <td class="text-white">Fecha Reg</td>
                            <td class="text-white">Fecha Mod.</td>
                            <td class="text-white">Acciones</td>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>

            <div class="modal-footer p-3">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Cerrar
                    <i class="fa fa-window-close ml-2" aria-hidden="true"></i>
                </button>
            </div>

        </div>
    </div>
</div>


{{-- Modal - Nuevo --}}
<div class="modal fade" id="mdlNuevoJugador" tabindex="-1" role="dialog" aria-labelledby="Nuevo" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content small">

            <form action="" method="POST" id="frmNuevoJugador" enctype="multipart/form-data">
                {{ csrf_field() }}

                <input type="hidden" name="mdl_nj_equipo_id" id="mdl_nj_equipo_id">

                <div class="modal-header" >
                    <h6 class="modal-title">
                        <i class="fa fa-user-plus fa-lg mr-1" aria-hidden="true"></i>
                        Nuevo Jugador
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
                                        <input type="text" name="ape_materno" id="ape_materno" class="form-control" />
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="nombres">Nombres:</label>
                                        <input type="text" name="nombres" id="nombres" class="form-control" required />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="fecha_nac">Fecha Nac:</label>
                                        <input type="date" name="fecha_nac" id="fecha_nac" placeholder="dd/mm/aaaa" class="form-control" required />
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="telefono">Teléfono:</label>
                                        <input type="text" name="telefono" id="telefono" class="form-control" />
                                    </div>

                                    <div class="col-sm-4">
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
                                <label for="foto_jugador">
                                    Foto del jugador:
                                </label>
                                <input type="file" class="form-control" id="foto_jugador" name="foto_jugador" onchange="return fileValidation(this, 'img')">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cerrar
                        <i class="fa fa-window-close ml-2" aria-hidden="true"></i>
                    </button>

                    <button type="submit" class="btn btn-outline-danger" id="btnGuardarJugador">
                        Guardar
                        <i class="fa fa-plus ml-1" id="fa_nj_guardar"></i>
                        <i class="fa fa-spinner fa-spin ml-1 hide" id="fa_nj_spinner" style="display: none"></i>
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>



{{-- Modal - Editar --}}
<div class="modal fade" id="mdlEditarJugador" tabindex="-1" role="dialog" aria-labelledby="Editar" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content small">

            <form action="" method="POST" id="frmEditarJugador" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <input type="hidden" name="mdl_uj_jugador_id" id="mdl_uj_jugador_id">

                <div class="modal-header" >
                    <h6 class="modal-title">
                        <i class="fa fa-user-plus fa-lg mr-1" aria-hidden="true"></i>
                        Editar Jugador
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
                                        <input type="text" name="mdl_uj_ape_materno" id="mdl_uj_ape_materno" class="form-control" />
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="mdl_uj_nombres">Nombres:</label>
                                        <input type="text" name="mdl_uj_nombres" id="mdl_uj_nombres" class="form-control" required />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="mdl_uj_fecha_nac">Fecha Nac:</label>
                                        <input type="date" name="mdl_uj_fecha_nac" id="mdl_uj_fecha_nac" placeholder="dd/mm/aaaa" class="form-control" required />
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="mdl_uj_telefono">Teléfono:</label>
                                        <input type="text" name="mdl_uj_telefono" id="mdl_uj_telefono" class="form-control" />
                                    </div>

                                    <div class="col-sm-4">
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
                                {{-- <img src=""  style="width: 120px; margin: 0px;"> --}}
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="mdl_uj_status">
                                    Status
                                </label>
                                <select name="mdl_uj_status" id="mdl_uj_status" class="form-control">
                                    <option value="">-- Seleccione una opción --</option>
                                    @foreach ($estados as $llave=>$valor)
                                    <option value="{{ $llave }}">{{ $valor }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-9">
                                <label for="mdl_uj_foto_jugador">
                                    Foto del jugador:
                                </label>
                                <input type="file" class="form-control" id="mdl_uj_foto_jugador" name="mdl_uj_foto_jugador" onchange="return fileValidation(this, 'img')">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cerrar
                        <i class="fa fa-window-close ml-2" aria-hidden="true"></i>
                    </button>

                    <button type="submit" class="btn btn-outline-danger" id="btnActualizarJugador">
                        Guardar
                        <i class="fa fa-plus ml-1" id="fa_uj_guardar"></i>
                        <i class="fa fa-spinner fa-spin ml-1 hide" id="fa_uj_spinner" style="display: none"></i>
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
