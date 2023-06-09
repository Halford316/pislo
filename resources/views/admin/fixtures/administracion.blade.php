@extends('adminlte::page')

@section('title', 'Fixtures')

@section('content_header')

<div class="ml-4 mr-4">

    <h1 class="text-danger">
        Fixtures
    </h1>

    <hr>
</div>

@stop

@section('content')

<div class="ml-4 mr-4">

    @for ($i=1; $i<=$rondas; $i++)
        <div class="box-fecha">
            Fecha: {{ $i }}

            <span class="float-right">
                <i class="fa fa-calendar" aria-hidden="true"></i>
            </span>
        </div>

        <div class="box-fixture">

            <div class="table-responsive">
                <table class="w-100" id="tblFechas_{{ $i }}">
                    <thead>
                        <tr class="text-center">
                            <th class="th-fixture">Cancha</th>
                            <th class="th-fixture">Hora</th>
                            <th class="th-fixture">Status</th>
                            <th class="th-fixture text-center">Equipos</th>
                            <th class="th-fixture">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                        $fechas = getFechas($torneo, $i)
                        @endphp
                        @foreach ($fechas as $fecha)
                        <tr>
                            <td class="td-fixture text-center">
                                <span id="campo_{{ $fecha->id }}">
                                    {{ ($fecha->campo_id) ? $fecha->campos->nombre : 'Sin asignar' }}
                                </span>
                            </td>

                            <td class="td-fixture text-center">
                                <span id="hora_{{ $fecha->id }}">
                                    {{ ($fecha->partido_hora) ? $fecha->partido_hora : '-' }}
                                </span>
                            </td>

                            <td class="td-fixture text-center">
                                <span id="status_{{ $fecha->id }}" class="text-{{ ($fecha->status=='pendiente') ? 'danger' : 'success' }}">
                                    {{ $fecha->status }}
                                </span>
                            </td>

                            <td class="td-fixture text-center">
                                <div class="row">
                                    <div class="col-4 text-right">
                                        {{ $fecha->locales->nombre }}

                                        <span id="goles_local_{{ $fecha->id }}" class="text-warning ml-3">
                                            {{ $fecha->equipo_1_goles }}
                                        </span>
                                    </div>

                                    <div class="col-4 text-center">VS</div>

                                    <div class="col-4">

                                        <span id="goles_visitante_{{ $fecha->id }}" class="text-warning mr-3">
                                            {{ $fecha->equipo_2_goles }}
                                        </span>

                                        {{ $fecha->visitantes->nombre }}
                                    </div>
                                </div>
                            </td>

                            <td class="td-fixture text-center">
                                <div class="btn-group">
                                    <a href="javascript:registrarFixture('{{ $fecha->id }}')" class="btn btn-danger w-100">
                                        <i class="fa fa-edit mr-2"></i>
                                        Registrar
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endfor
</div>

@include('admin.fixtures.mdl_encuentro')

@stop

@section('css')
<link rel="stylesheet" href="/css/pislo.css">
@stop

@section('js')
    <script src="{{ asset('js/fixtures/adm_fixtures.js') }}" language="JavaScript"></script>
    <script src="{{ asset('js/fixtures/adm_expulsiones.js') }}" language="JavaScript"></script>

    <script>
        var flagUrl = '{{ URL::asset('') }}';

        $(document).ready(function() {

            var i = '{{ $i }}';
            //console.log('este es mi i='+i);

            /* Cargando las fechas  */
            $('#tblFechas_'+i).DataTable( {
                "searching" : false,
                "paging" : false,
                "info": false,
                bSort: false,
                "ajax" : "{{ route('fixtures.datatable.fechas', [$torneo, $i]) }}",

                "columns" : [
                    { data: 'cancha'},
                    { data: 'hora'},
                    { data: 'equipos'},
                    { data: 'acciones'}
                ],

                language: {
                        "sProcessing":     "Procesando...",
                        "sEmptyTable":     "No tiene ingresado ninguna fecha",
                        "sLoadingRecords": "Cargando...",
                    }

            } );

        });
    </script>
@stop
