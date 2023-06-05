{{-- Modal - Editar --}}
<div class="modal fade" id="mdlEditarEncuentro" tabindex="-1" role="dialog" aria-labelledby="Editar" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">

            <form action="" method="POST" id="frmEditarEncuentro" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <input type="hidden" name="mdl_ue_ficha_id" id="mdl_ue_ficha_id">

                <div class="modal-header" >
                    <h6 class="modal-title">
                        <i class="fa fa-edit fa-lg mr-1" aria-hidden="true"></i>
                        Registro de encuentro - Fecha <span id="mdl_show_fecha_nro"></span>
                    </h6>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-4">

                    <div class="box-goles rounded mb-3">
                        <div class="d-flex flex-row">
                            <div class="p-2 text-center">
                                <span id="mdl_show_local" class="mr-4"></span>
                                <input type="text" name="mdl_equipo_1_goles" id="mdl_equipo_1_goles" class="input-text" placeholder="0" maxlength="2" required>
                            </div>
                            <div class="p-2 pt-3 text-center">
                                GOLES
                            </div>
                            <div class="p-2 text-center">
                                <input type="text" name="mdl_equipo_2_goles" id="mdl_equipo_2_goles" class="input-text" placeholder="0" maxlength="2" required>
                                <span id="mdl_show_visitante" class="ml-4"></span>
                            </div>
                        </div>

                        <div id="show_expulsados" class="hidden">
                            <div class="d-flex flex-row small">
                                <div class="p-2 text-center w-100">
                                    <span id="mdl_show_expulsados_local" class="ml-4"></span>
                                </div>
                                <div class="p-2 text-center">

                                </div>
                                <div class="p-2 text-center w-100">
                                    <span id="mdl_show_expulsados_visitante" class="ml-4"></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="mdl_ue_partido_hora">Hora del encuentro:</label>
                                <input type="text" name="mdl_ue_partido_hora" id="mdl_ue_partido_hora" class="form-control" value="" required />
                            </div>

                            <div class="col-sm-4">
                                <label for="mdl_ue_partido_fecha">Fecha del encuentro:</label>
                                <input type="date" name="mdl_ue_partido_fecha" id="mdl_ue_partido_fecha" class="form-control" value="" required />
                            </div>

                            <div class="col-sm-4">
                                <label for="mdl_ue_status">
                                    Status al encuentro
                                </label>
                                <select name="mdl_ue_status" id="mdl_ue_status" class="form-control" required>
                                    <option value="">-- Seleccione status --</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="finalizado">Finalizado</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="mdl_ue_campo">
                                    Asignar campo al encuentro
                                </label>
                                <select name="mdl_ue_campo" id="mdl_ue_campo" class="form-control" required>
                                    <option value="">-- Seleccione campo --</option>
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label for="mdl_ue_arbitro">
                                    Seleccionar arbitro del campo
                                </label>
                                <select name="mdl_ue_arbitro" id="mdl_ue_arbitro" class="form-control" required>
                                    <option value="">-- Seleccione arbitro --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group pl-4 pr-4 pb-2">
                    <span class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle mr-1" aria-hidden="true"></i>
                        Cuando el encuentro termine cambie el estado a finalizado.
                    </span>
                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cerrar
                        <i class="fa fa-window-close ml-2" aria-hidden="true"></i>
                    </button>

                    <button type="submit" class="btn btn-outline-danger" id="btnActualizarEncuentro">
                        Guardar
                        <i class="fa fa-plus ml-1" id="fa_ua_guardar"></i>
                        <i class="fa fa-spinner fa-spin ml-1 hide" id="fa_ua_spinner" style="display: none"></i>
                    </button>
                </div>

            </form>

            {{-- Módulo expulsiones --}}
            @include('admin.fixtures.inc_mdl_expulsiones')

        </div>
    </div>
</div>
