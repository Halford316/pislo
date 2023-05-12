@extends('adminlte::page')

@section('title', 'Equipos')

@section('content_header')
    <h1 class="float-left text-danger">
        Lista de equipos
    </h1>

    <div class="float-right pb-5">
        <a href="#" class="btn btn-danger" onclick="nuevoEquipo()">
            <i class="fa fa-plus mr-1" aria-hidden="true"></i>
            Nuevo equipo
        </a>
    </div>
@stop

@section('content')

<div class="table-responsive">
    <table class="table table-striped table-hover w-100 text-sm" id="tblEquipos">
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

        <tbody>

        </tbody>
    </table>

    <div id="tableInfo"></div>
    <div id="orderInfo"></div>
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
                    className: "text-center", "targets": [1,2,3,4,6]
                }
            ],
            "aaSorting": [[ 6, "desc" ]],
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
                "sLengthMenu":     "Mostrar _MENU_  &nbsp; registros",
                "sZeroRecords":    "¡No se obtuvieron resultados!",
                "sEmptyTable":     "¡No se obtuvieron resultados!",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
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
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
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
