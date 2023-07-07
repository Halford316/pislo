@extends('adminlte::page')

@section('title', 'Equipos')

@section('content_header')
<div class="card p-4">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-8">
                <div class="d-flex flex-row text-white">
                    <div class="mr-4">
                        <i class="ico-equipos fa-4x"></i>
                    </div>
                    <div class="">
                        <h3 class="float-left text-danger">
                            Lista de equipos
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 pt-3">
                <div class="text-right">
                    <a href="javascript:" class="btn btn-danger" onclick="nuevoEquipo()">
                        <i class="fa fa-plus mr-1" aria-hidden="true"></i>
                        Nuevo equipo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('content')

<div class="table-responsive p-4">
    <table class="table table-hover w-100 text-sm" id="tblEquipos">
        <thead>
            <tr class="bg-gray-dark">
                <th>ID</th>
                <th>NOMBRE</th>
                <th>FOTO</th>
                <th>Nro JUGADORES</th>
                <th>JUGADORES REGISTRADOS</th>
                <th>USUARIO</th>
                <th>FECHA REG</th>
                <th>FECHA MOD</th>
                <th>ACCIONES</th>
            </tr>
        </thead>

        <tbody></tbody>
    </table>

</div>

@include('admin.equipos.mdl_nuevo')
@include('admin.equipos.mdl_pagos')
@include('admin.equipos.mdl_jugadores')

@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script src="{{ asset('js/valida.js') }}" language="JavaScript"></script>
    <script src="{{ asset('js/equipos/adm_equipos.js') }}" language="JavaScript"></script>
    <script src="{{ asset('js/jugadores/adm_jugadores.js') }}" language="JavaScript"></script>
    <script>

    var flagUrl = '{{ URL::asset('') }}';

    $(document).ready(function() {

        /* Cargando los equipos  */
        var table = $('#tblEquipos').DataTable( {
            "responsive": true,
            bInfo: true,
            bSort: true,
            autoWidth: false,
            "processing": true,
            "searching" : true,
            "paging" : true,
            "iDisplayLength": 10,
            "info": false,
            ajax: {
                    "url" : flagUrl+"admin/equipos/datatable",
                    "type" : "POST",
                    "data" : {'_token' : '{{ csrf_token() }}'}
                },
            "columnDefs": [
                {
                    className: "text-center", "targets": [0,1,2,3,4,6,7,8]
                }
            ],
            "aaSorting": [[ 5, "desc" ]],
            "aoColumnDefs": [
                { 'bSortable': false, 'aTargets': [0,1,2,3,4,5,6,7] }
            ],

            "columns" : [
                { data: 'id' },
                { data: 'nombre' },
                { data: 'foto' },
                { data: 'nro_jugadores' },
                { data: 'jugadores_reg' },
                { data: 'usuario' },
                { data: 'fecha_reg' },
                { data: 'fecha_mod' },
                { data: 'acciones' }
            ],

            language:{
                "sProcessing":     "Procesando...",
                "sLengthMenu":     " _MENU_  &nbsp; entradas por página",
                "sZeroRecords":    "¡No se obtuvieron resultados!",
                "sEmptyTable":     "¡No se obtuvieron resultados!",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de <span class='text-danger'>_TOTAL_</span> registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "&gt;",
                    "sPrevious": "&lt;"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }

        } );

    } );

    </script>
@stop
