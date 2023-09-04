@extends('adminlte::page')

@section('title', 'Arbitros')

@section('content_header')
<div class="card p-4">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-8">
                <div class="d-flex flex-row text-white">
                    <div class="mr-4">
                        <i class="ico-arbitro fa-4x"></i>
                    </div>
                    <div class="">
                        <h3 class="float-left text-danger">
                            Lista de Arbitros
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 pt-3">
                <div class="text-right">
                    <a href="javascript:" class="btn btn-danger" onclick="nuevoArbitro()">
                        <i class="fa fa-plus mr-1" aria-hidden="true"></i>
                        Nuevo arbitro
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('content')

<div class="table-responsive p-4">
    <table class="table w-100 text-sm" id="tblArbitros">
        <thead>
            <tr class="text-center bg-dark">
                <td class="text-white">ID</td>
                <td class="text-white">Foto</td>
                <td class="text-white">Nombre</td>
                <td class="text-white">Edad</td>
                <td class="text-white">Teléfono</td>
                <td class="text-white">Fecha Reg</td>
                <td class="text-white">Fecha Mod.</td>
                <td class="text-white">Acciones</td>
            </tr>
        </thead>

        <tbody>

        </tbody>
    </table>

    <div id="tableInfo"></div>
    <div id="orderInfo"></div>
</div>

@include('admin.arbitros.mdl_arbitros')

@stop

@section('css')

@stop

@section('js')
    <script src="{{ asset('js/valida.js') }}" language="JavaScript"></script>
    <script src="{{ asset('js/arbitros/adm_arbitros.js') }}" language="JavaScript"></script>
    <script>

    var flagUrl = '{{ URL::asset('') }}';

    $(document).ready(function() {

        /* Cargando los Arbitros  */
        var table = $('#tblArbitros').DataTable( {
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
                    "url" : flagUrl+"admin/arbitros/datatable",
                    "type" : "GET",
                    "data" : {'_token' : '{{ csrf_token() }}'}
                },
            "columnDefs": [
                {
                    className: "text-center", "targets": [0,1,2,3,4,5]
                }
            ],
            "aaSorting": [[ 6, "desc" ]],
            "aoColumnDefs": [
                { 'bSortable': false, 'aTargets': [0,1,2,3,4,5,6,7] }
            ],

            "columns" : [
                { data: 'id' },
                { data: 'foto' },
                { data: 'nombre' },
                { data: 'edad' },
                { data: 'telefono' },
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
