@extends('layouts.landing')
@section('title', 'Tags')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1 class="display-6 mb-0">Etiquetas</h1>
        </div>
        <div class="card-body">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Botón Crear etiqueta y Formulario de filtro -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('tag.create') }}" class="btn btn-primary btn-sm me-3">
                    <i class="bi bi-plus-circle"></i> Crear etiqueta
                </a>
                <!-- Formulario de filtro -->
                <form method="GET" action="{{ route('tag.index') }}" class="d-flex align-items-center">
                    <select name="filter_tag" class="form-select form-select-sm rounded-pill border-0 me-2" aria-label="Filtrar etiquetas">
                        <option value="">Todas</option>
                        @foreach ($tagsForFilter as $tagOption)
                            <option value="{{ $tagOption->id }}" {{ request()->input('filter_tag') == $tagOption->id ? 'selected' : '' }}>
                                {{ $tagOption->name_tag }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill">
                        Filtrar
                    </button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Color</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tags as $tag)
                        <tr>
                            <th scope="row">{{ $tag->id }}</th>
                            <td>{{ $tag->name_tag }}</td>
                            <td>{{ $tag->description }}</td>
                            <td>
                                @if($tag->color)
                                    @php
                                        $color = $tag->color[0] == '#' ? $tag->color : '#' . $tag->color;
                                    @endphp
                                     <span class="color-square" style="background-color: {{ $color }};"></span>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('tag.edit', $tag->id) }}" class="btn btn-success btn-sm"><i class="bi bi-pencil-square"></i> Editar</a>
                                <form action="{{ route('tag.destroy', $tag->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro que desea eliminar la etiqueta?');">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $tags->appends(['filter_tag' => request()->input('filter_tag')])->links() }}
            </div>
        </div>
    </div>
</div>
@endsection









