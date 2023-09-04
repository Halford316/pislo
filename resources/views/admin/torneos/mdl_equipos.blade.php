{{-- Modal - listando equipos --}}
<div class="modal fade" id="mdlEquipos" tabindex="-1" role="dialog" aria-labelledby="Nuevo" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">

        <div class="modal-content">

            <div class="modal-header" >
                <h6 class="modal-title">
                    <i class="fa fa-users fa-lg mr-1" aria-hidden="true"></i>
                    Equipos registrados
                </h6>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <table class="table w-100 small" id="tblEquipos">
                    <thead>
                        <tr class="text-center bg-dark">
                            <td class="text-white">ID</td>
                            <td class="text-white">Nombre del equipo</td>
                            <td class="text-white">Nro. jugadores</td>
                            <td class="text-white">Status</td>
                            <td class="text-white">Fecha Reg</td>
                            <td class="text-white">Fecha Mod.</td>
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
