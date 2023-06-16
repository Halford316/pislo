{{-- Modal - listando pagos --}}
<div class="modal fade" id="mdlPagos" tabindex="-1" role="dialog" aria-labelledby="Nuevo" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">

        <div class="modal-content">

            <div class="modal-header" >
                <h6 class="modal-title">
                    <i class="fa fa-credit-card fa-lg mr-1" aria-hidden="true"></i>
                    Pagos registrados
                </h6>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="card shadow border-left">
                    <div class="card-body">

                        <form action="" method="POST" id="frmNuevoPago" enctype="multipart/form-data" autocomplete="off">
                            {{ csrf_field() }}

                            <input type="hidden" name="equipo_id" id="equipo_id">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="torneo_id">Torneo:</label>
                                        <select name="torneo_id" id="torneo_id" class="form-control" required>
                                            <option value="">-- Seleccione torneo --</option>
                                            @foreach ($torneos as $torneo)
                                            <option value="{{ $torneo->id }}">{{ $torneo->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="costo">Costo por registro al torneo:</label>
                                        <input type="number" name="costo" id="costo" placeholder="S/ 0.00" onkeypress="return valideKey(event);" class="form-control" readonly />
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="registracion">A pagar:</label>
                                        <input type="text" name="registracion" id="registracion" placeholder="S/ 0.00" class="form-control"  readonly />
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="adelanto">A cuenta:</label>
                                        <input type="number" name="adelanto" id="adelanto" placeholder="S/ 0.00" onkeypress="return valideKey(event);" class="form-control" required />
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="saldo">Saldo:</label>
                                        <input type="text" name="saldo" id="saldo" placeholder="S/ 0.00" class="form-control" readonly />
                                    </div>
                                </div>
                            </div>


                            <div class="d-block text-right">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    Cerrar
                                    <i class="fa fa-window-close ml-2" aria-hidden="true"></i>
                                </button>

                                <button type="submit" class="btn btn-outline-danger" id="btnGuardarPago">
                                    Guardar
                                    <i class="fa fa-plus ml-1" id="fa_np_guardar"></i>
                                    <i class="fa fa-spinner fa-spin ml-1 hide" id="fa_np_spinner" style="display: none"></i>
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

                {{-- Listando pagos por torneo --}}
                <div class="card shadow">

                    <div class="card-body">

                        <table class="table table-hover w-100 small" id="tblPagos">
                            <thead>
                                <tr class="text-center bg-dark">
                                    <td class="text-white">Equipo</td>
                                    <td class="text-white">Torneo</td>
                                    <td class="text-white">Pago</td>
                                    <td class="text-white">A cuenta</td>
                                    <td class="text-white">Saldo</td>
                                    <td class="text-white">Status</td>
                                    <td class="text-white">Usuario</td>
                                    <td class="text-white">Fecha Reg</td>
                                    <td class="text-white">Fecha Mod.</td>
                                    <td class="text-white">Acciones</td>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>
                </div>

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



{{-- Modal - Editar --}}
<div class="modal fade" id="mdlEditarPago" tabindex="-1" role="dialog" aria-labelledby="Editar" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <form action="" method="POST" id="frmEditarPago" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <input type="hidden" name="mdl_up_pago_id" id="mdl_up_pago_id">

                <div class="modal-header" >
                    <h6 class="modal-title">
                        <i class="fa fa-edit fa-lg mr-1" aria-hidden="true"></i>
                        Editar pago
                    </h6>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="mdl_up_torneo_id">Torneo:</label>
                                <select name="mdl_up_torneo_id" id="mdl_up_torneo_id" class="form-control" required readonly>
                                    <option value="">-- Seleccione torneo --</option>
                                    @foreach ($torneos as $torneo)
                                    <option value="{{ $torneo->id }}">{{ $torneo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="mdl_up_registracion">Pago:</label>
                                <input type="text" name="mdl_up_registracion" id="mdl_up_registracion" class="form-control" required readonly />
                            </div>

                            <div class="col-sm-4">
                                <label for="mdl_up_adelanto">A cuenta:</label>
                                <input type="text" name="mdl_up_adelanto" id="mdl_up_adelanto" class="form-control" required />
                            </div>

                            <div class="col-sm-4">
                                <label for="mdl_up_saldo">Saldo:</label>
                                <input type="text" name="mdl_up_saldo" id="mdl_up_saldo" class="form-control" readonly />
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cerrar
                        <i class="fa fa-window-close ml-2" aria-hidden="true"></i>
                    </button>

                    <button type="submit" class="btn btn-outline-danger" id="btnActualizarPago">
                        Guardar
                        <i class="fa fa-plus ml-1" id="fa_up_guardar"></i>
                        <i class="fa fa-spinner fa-spin ml-1 hide" id="fa_up_spinner" style="display: none"></i>
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
