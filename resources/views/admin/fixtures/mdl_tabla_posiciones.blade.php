{{-- Modal - Editar --}}
<div class="modal fade" id="mdlTablaPosiciones" tabindex="-1" role="dialog" aria-labelledby="Editar" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header" >
                <h5 class="modal-title">
                    <i class="fa fa-table mr-1" aria-hidden="true"></i>
                    Tabla de posiciones
                </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-2">

                <!-- Modal que muestra la tabla de posiciones -->
                <div id="tabla-modal">
                    <table class="table table-striped w-100">
                        <thead>
                            <tr>
                                <th>Equipo</th>
                                <th class="text-center">Jugados</th>
                                <th class="text-center">Ganados</th>
                                <th class="text-center">Empatados</th>
                                <th class="text-center">Perdidos</th>
                                <th class="text-center">Favor</th>
                                <th class="text-center">En contra</th>
                                <th class="text-center">Dif.</th>
                                <th class="text-center">Puntos</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-cuerpo"></tbody>
                    </table>
                </div>

            </div>


        </div>
    </div>
</div>
