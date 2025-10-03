@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Lista de Productos</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        @if(Auth::user()->hasPermission('products', 'admin'))
                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                Crear Nuevo Producto
                            </a>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Precio Total</th>
                                    <th>Fecha de Expiración</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>{{ number_format($product->price, 2) }}</td>
                                    <td>{{ number_format($product->total_price, 2) }}</td>
                                    <td>{{ $product->expiration_date ? $product->expiration_date->format('Y-m-d') : '-' }}</td>
                                    <td>
                                        @if(Auth::user()->hasPermission('products', 'admin'))
                                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">
                                                Editar
                                            </a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
