@extends('adminlte::page')

@section('title', 'Reportes de pagos')

@section('content_header')
<div class="p-2">
    <h1 class="text-danger">
        Reporte de pagos
    </h1>
</div>
@stop

@section('content')

<div class="table-responsive p-2">
    <div class="card shadow">
        @include('admin.includes.inc_busqueda_pagos')
    </div>

    <table class="table table-hover w-100 text-sm" id="tblFichas">
        <thead>
            <tr class="bg-gray-dark">
                <th>ID</th>
                <th>EQUIPO</th>
                <th>TORNEO</th>
                <th>COSTO</th>
                <th>ADELANTO</th>
                <th>SALDO</th>
                <th>STATUS</th>
                <th>USUARIO</th>
                <th>FECHA REG</th>
                <th>FECHA MOD</th>
            </tr>
        </thead>

        <tbody>

        </tbody>
    </table>

    <div id="tableInfo"></div>
    <div id="orderInfo"></div>
</div>


@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pislo.css') }}">
    <link rel="stylesheet" href="{{ asset('pislo-assets/icon_font/style.css') }}">
@stop

@section('js')
    <script>

    var flagUrl = '{{ URL::asset('') }}';

    $(document).ready(function() {

        /* Cargando los torneos  */
        var table = $('#tblFichas').DataTable( {
            "scrollX": true,
            "autoWidth": true,
            //searching: false,
            iDisplayLength: 10,
            bInfo: true,
            bSort: true,
            oPaginate: false,
            "columnDefs": [
                { sortable: false, targets: "_all"},
                { "className": "text-center", "targets": [1,2,3,4,5,6,7,8,9] }
            ],
            "processing": true,
            "serverSide": true,
            "aaSorting": [[ 8, "desc" ]],
            "sDom": "rtipl", //ocultar search textbox
            ajax: {
                    "url" : flagUrl+"admin/reportes/pagos/datatable",
                    "type" : "POST",
                    "data" : {'_token' : '{{ csrf_token() }}'}
                },

            "columns" : [
                { data: 'id' },
                { data: 'equipo' },
                { data: 'torneo' },
                { data: 'costo' },
                { data: 'adelanto' },
                { data: 'saldo' },
                { data: 'status' },
                { data: 'usuario' },
                { data: 'fecha_reg' },
                { data: 'fecha_mod' }
            ],

            language:{
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_  &nbsp; registros",
                "sZeroRecords":    "¡No se obtuvieron resultados!",
                "sEmptyTable":     "¡No se obtuvieron resultados!",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de <span class=\"text-danger\">_TOTAL_</span> registros",
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
                    "sNext":     "Siguiente &gt;",
                            "sPrevious": "&lt; Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }

        });


        var table = $('#tblFichas').dataTable().api();

        $('#btnBusqueda').on('click', function () {
            table.search($("#search_tx").val()).draw();
        });

        $('#btnFiltrado').on('click', function () {
            filter_tx = $('#fil_equipo').val() + '|' + $('#fil_torneo').val() + '|' + $('#fil_status').val();
            table.search(filter_tx).draw();
        });

        $('#tblFichas').on( 'order.dt', function () {
            table.order();
        });

        $('#btnRestablecer').on('click', function () {
            table.search('').draw();
            $('#frmFiltraPor')[0].reset();
        });

    });

    </script>
@stop
