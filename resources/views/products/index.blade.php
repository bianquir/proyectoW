@extends('layouts.landing')

@section('content')

<div class="container">

    <h1 class="h1">Lista de Productos</h1>
    @if (session('success'))
        <div id="success-alert" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($products->isEmpty())
        <p>No existen registros de productos.</p>
    @else

            <a href="{{ route('products.create') }}" class="btn btn-primary btn-md mb-2">Crear producto</a>

            <table class="table table-hover table-responsive">
                <thead>
                    <tr class="text-center table-light">
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr class="text-center">
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->description }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                <form action="{{ route('products.destroy', $product->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Editar</a>   

                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Quieres eliminar este producto ?');"><i class="bi bi-trash"></i> Eliminar</button>
                                </form> 
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
</div>
@endsection