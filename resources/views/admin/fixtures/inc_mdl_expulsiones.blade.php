<div class="card p-3">
    <div class="card-header bg-secondary">
        <h6>Expulsiones</h6>
    </div>

    <div class="card-body">

        <form action="#" id="frmExpulsiones" method="POST">
            @csrf
            <input type="hidden" name="mdl_ue_torneo_id" id="mdl_ue_torneo_id">
            <input type="hidden" name="mdl_ue_campo_id" id="mdl_ue_campo_id">
            <input type="hidden" name="mdl_ue_fixture_id" id="mdl_ue_fixture_id">

            <div class="row">
                <div class="col-sm-4">
                    <label for="mdl_ue_equipo">
                        Seleccionar equipo
                    </label>
                    <select name="mdl_ue_equipo" id="mdl_ue_equipo" class="form-control" required>
                        <option value="">-- Seleccione --</option>
                    </select>
                </div>

                <div class="col-sm-6">
                    <label for="mdl_ue_jugador">
                        Seleccionar jugador
                    </label>
                    <select name="mdl_ue_jugador" id="mdl_ue_jugador" class="form-control" required>
                        <option value="">-- Seleccione --</option>
                    </select>
                </div>

                <div class="col-sm-2 pt-3">
                    <button type="submit" class="btn btn-secondary" id="btnGuardarExpulsion">
                        <i class="fa fa-plus mr-2"></i>
                        Agregar
                    </button>
                </div>
            </div>
        </form>


        <div class="form-group pt-3">
            <table class="table table-hover w-100" id="tblExpulsiones">
                <thead>
                    <tr>
                        <th>Equipo</th>
                        <th>Jugador</th>
                        <th>Fecha de expulsión</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
