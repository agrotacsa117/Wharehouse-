@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Total de Productos</h1>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Totales de Productos</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- Tabla de Totales por Bodega -->
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h6 class="card-title">Por Bodega</h6>
                                                        <table class="table table-bordered datatable">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">Bodega</th>
                                                                    <th class="text-center">Cantidad</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($bodegas as $bodega)
                                                                    <tr class="text-center" style="background-color: #dc3545; color: white;">
                                                                        <td>{{ $bodega->bodega }}</td>
                                                                        <td>{{ $bodega->total_cantidad }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <!-- Tabla de Total General -->
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h6 class="card-title">Total General</h6>
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                <tr>
                                                                    <th class="text-center">Total de Productos</th>
                                                                    <td class="text-center">{{ $items->count() }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-center">Total de Cantidad</th>
                                                                    <td class="text-center">{{ $total_general }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection