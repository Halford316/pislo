@extends('adminlte::page')

@section('title', 'Torneos')

@section('content_header')

@stop

@section('content')

<div class="content p-4">
    <form action="" id="frmEditar" method="POST" autocomplete="off">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <br>

        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
        <input type="hidden" name="torneo_id" value="{{ $ficha->id }}">

        <div class="card w-100 pr-4 pl-4 text-sm">

            <div class="card-header">

                <div class="row">
                    <div class="col-sm-8">
                        <div class="d-flex flex-row text-white">
                            <div class="mr-4">
                                <i class="ico-copa fa-4x"></i>
                            </div>
                            <div class="">
                                <h4 class="float-left text-danger">
                                    Editar torneo
                                </h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="text-right">
                            <a href="{{ route('torneos.index') }}" class="btn btn-outline-secondary mr-2">
                                <i class="fa fa-angle-left mr-1" aria-hidden="true"></i>
                                Regresar
                            </a>

                            <button type="submit" class="btn btn-danger" id="btnGuardar">
                                Actualizar torneo
                                <i class="fa fa-plus ml-1" id="fa_guardar"></i>
                                <i class="fa fa-spinner fa-spin ml-1 hide" id="fa_spinner" style="display: none"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-body mt-3">

                <div class="form-group">
                    <div class="row m-l-0 m-r-0">
                        <div class="col-sm-9">
                            <label for="nombre">Nombre del torneo:</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('torneo', $ficha->nombre) }}" required />
                        </div>

                        <div class="col-sm-3">
                            <label for="fecha_inicio">Fecha de inicio:</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ old('torneo', $ficha->fecha_inicio) }}" required />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row m-l-0 m-r-0">
                        <div class="col-sm-9">
                            <label for="premio">Premio:</label>
                            <input type="text" name="premio" id="premio" class="form-control" value="{{ old('torneo', $ficha->premio) }}" required />
                        </div>

                        <div class="col-sm-3">
                            <label for="nro_equipos">Nro equipos:</label>
                            <input type="number" name="nro_equipos" id="nro_equipos" class="form-control" value="{{ old('torneo', $ficha->nro_equipos) }}" required />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row m-l-0 m-r-0">
                        <div class="col-sm-3">
                            <label for="precio">Precio:</label>
                            <input type="number" name="precio" id="precio" class="form-control" value="{{ old('torneo', $ficha->precio) }}" required />
                        </div>

                        <div class="col-sm-4">
                            <label for="direccion">Dirección:</label>
                            <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('torneo', $ficha->direccion) }}" required />
                        </div>

                        <div class="col-sm-5">
                            <label for="lugar">Lugar:</label>
                            <input type="text" name="lugar" id="lugar" class="form-control" value="{{ old('torneo', $ficha->lugar) }}" required />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-2">
                            <label for="duracion">Duración:</label>
                            <input type="number" name="duracion" id="duracion" class="form-control" value="{{ old('torneo', $ficha->duracion) }}" required />
                        </div>

                        <div class="col-sm-2">
                            <label for="minutos">&nbsp;</label>
                            <input type="text" name="minutos" id="minutos" class="form-control" value="Minutos" readonly required />
                        </div>

                        <div class="col-sm-2">
                            <label for="descanso">Descanso:</label>
                            <input type="number" name="descanso" id="descanso" class="form-control" value="{{ old('torneo', $ficha->descanso) }}" required />
                        </div>

                        <div class="col-sm-2">
                            <label for="minutos">&nbsp;</label>
                            <input type="text" name="minutos" id="minutos" class="form-control" value="Minutos" readonly required />
                        </div>

                        <div class="col-sm-4">
                            <label for="hora_inicio">Hora de inicio</label>
                            <input type="text" name="hora_inicio" id="hora_inicio" class="form-control" placeholder="Hora 00:00" value="{{ old('torneo', $ficha->hora_inicio) }}" maxlength="5" required />
                        </div>
                    </div>
                </div>

                {{-- <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">

                            <div class="card w-100 p-3">
                                <div class="card-header">
                                    <h6 class="float-left text-dark">
                                        Campos
                                    </h6>


                                    <div class="float-right">
                                        <a href="#" class="btn btn-secondary" id="agregarCampos">
                                            <i class="fa fa-plus mr-1" aria-hidden="true"></i>
                                            Seleccionar
                                        </a>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <input type="hidden" name="campo_existe" id="campo_existe" value="">
                                    <div id="selected-campos">
                                        <!-- Aquí se mostrarán los elementos seleccionados -->
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-sm-6">

                            <div class="card w-100 p-3">
                                <div class="card-header">
                                    <h6 class="float-left text-dark">
                                        Sponsors
                                    </h6>


                                    <div class="float-right">
                                        <a href="#" class="btn btn-secondary" id="agregarSponsors">
                                            <i class="fa fa-plus mr-1" aria-hidden="true"></i>
                                            Seleccionar
                                        </a>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <input type="hidden" name="sponsor_existe" id="sponsor_existe" value="">
                                    <div id="selected-sponsors">
                                        <!-- Aquí se mostrarán los elementos seleccionados -->
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div> --}}

            </div>
        </div>

    </form>
</div>


@include('admin.torneos.mdl_campos')
@include('admin.torneos.mdl_sponsors')

@stop

@section('css')

@stop

@section('js')
    <script src="{{ asset('js/torneos/torneos.js') }}" language="JavaScript"></script>
    <script src="{{ asset('js/torneos/adm_campos.js') }}" language="JavaScript"></script>
    <script src="{{ asset('js/torneos/adm_sponsors.js') }}" language="JavaScript"></script>

<script>
    var flagUrl = '{{ URL::asset('') }}';


    $(document).ready(function() {

        $('#hora_inicio').inputmask('99:99');

        /* Cargando los campos */
        $('#tblCampos').DataTable( {
            "searching" : false,
            "paging" : true,
            "bInfo" : false,
            "lengthChange": false,
            bSort: false,
            "iDisplayLength": 5,
            "ajax" : "{{ route('torneos.campos.datatable') }}",
            "columnDefs": [
                {
                    className: "text-center", "targets": [0,1,2,3]
                }
            ],
            "columns" : [
                { data: 'id' },
                { data: 'campo' },
                { data: 'tipo' },
                { data: 'acciones' }
            ],
            language: {
                    "sProcessing":     "Procesando...",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "No tiene ingresado ningún campo",
                    "sLoadingRecords": "Cargando...",
            }

        } );

        /* Cargando los sponsors */
        $('#tblSponsors').DataTable( {
            "searching" : false,
            "paging" : true,
            "lengthChange": false,
            "info": false,
            bSort: false,
            "iDisplayLength": 5,
            "ajax" : "{{ route('torneos.sponsors.datatable') }}",
            "columnDefs": [
                {
                    className: "text-center", "targets": [0,1,2,3]
                }
            ],
            "columns" : [
                { data: 'id' },
                { data: 'sponsor' },
                { data: 'descripcion' },
                { data: 'acciones' }
            ],
            language: {
                    "sProcessing":     "Procesando...",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "No tiene ingresado ningún sponsor",
                    "sLoadingRecords": "Cargando...",
            }

        } );

    });
</script>

@stop
