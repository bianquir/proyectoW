@extends('layouts.landing')
@section('title', 'Tags')
@section('content')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tags') }}
        </h2>
    </x-slot>

@if ($message = Session::get('success'))
<div class="alert alert-success" role="alert">
    {{ $message }}
</div>
@endif

    <div class="container">
        <div class="col-auto">
            <a href="{{ route('tag.create') }}" class="btn btn-primary btn-sm my-2">
                <i class="bi bi-plus-circle"></i> Crear etiqueta
            </a>
        </div>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Color</th>
                    <th scope="col">Descripcion</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tags as $tag)
                <tr>
                    <th scope="row">{{ $tag->id }}</th>
                    <td>{{ $tag->name_tag }}</td>
                    <td>{{ $tag->description }}</td>
                    <td>{{ $tag->color }}</td>
                    <td>
                        <form action="{{route('tag.destroy' , $tag->id)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <a href="{{ route('tag.edit', $tag->id) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete this student?');">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
     {{$tags->links()}}
    </div>

</x-app-layout>
@endsection
