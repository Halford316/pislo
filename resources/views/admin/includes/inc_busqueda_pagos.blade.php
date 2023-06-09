<div class="card-body">

    <div class="row">

        <div class="col-sm-12">

            <form method="get" id="frmFiltraPor" class="form-inline">

                <div class="float-left w-15 mr-2">
                    <select name="equipo" id="fil_equipo" class="form-control">
                        <option value="">-- Seleccione equipo --</option>
                        @foreach ($equipos as $equipo)
                            <option value="{{ $equipo->id }}">{{ $equipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="float-left w-15 mr-2">
                    <select name="torneo" id="fil_torneo" class="form-control">
                        <option value="">-- Seleccione torneo --</option>
                        @foreach ($torneos as $torneo)
                            <option value="{{ $torneo->id }}">{{ $torneo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="float-left w-15 mr-2">
                    <select name="status" id="fil_status" class="form-control">
                        <option value="">-- Seleccione status --</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>

                <div class="">
                    <button type="button" class="btn btn-danger btn-sm" id="btnFiltrado">
                        <i class="fa fa-filter fa-lg"></i>
                    </button>

                    <span>
                        <a href="javascript:" class="btn btn-outline-secondary btn-sm" id="btnRestablecer">
                            Restablecer todo
                        </a>
                    </span>
                </div>
            </form>

        </div>
    </div>

</div>
