{{-- Vista para mostrar los detalles de un producto --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            {{-- Nombre y descripción del producto --}}
            <h1>{{ $product->name }}</h1>
            <p class="lead">{{ $product->description }}</p>
            <hr>
            {{-- Detalles del producto --}}
            <h3>Detalles del producto</h3>
            <ul>
                <li>Precio: ${{ $product->price }}</li>
                <li>Categoría: {{ $product->category->name }}</li>
                <li>Stock: {{ $product->stock }}</li>
            </ul>
            <hr>
            {{-- Imágenes del producto --}}
            <h3>Imágenes del producto</h3>
            @if($product->images->count())
                <div class="row">
                    @foreach($product->images as $image)
                        <div class="col-md-4">
                            <a href="{{ asset('storage/' . $image->url) }}" target="_blank">
                                <img src="{{ asset('storage/' . $image->url) }}" class="img-fluid" alt="{{ $product->name }}">
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p>No hay imágenes disponibles para este producto.</p>
            @endif
            <hr>
            {{-- Comentarios del producto --}}
            <h3>Comentarios</h3>
            @if($product->comments->count())
                <div class="list-group">
                    @foreach($product->comments as $comment)
                        <div class="list-group-item">
                            <h5 class="mb-1">{{ $comment->user->name }}</h5>
                            <p class="mb-1">{{ $comment->content }}</p>
                            <small>{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                    @endforeach
                </div>
            @else
                <p>No hay comentarios para este producto aún.</p>
            @endif
        </div>
    </div>
</div>
@endsection