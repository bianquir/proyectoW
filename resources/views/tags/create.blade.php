@extends('layouts.landing')
@section('title', 'Nueva etiqueta')
@section('content')
<div class="row justify-content-center mt-3">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Agregar nueva etiqueta
                </div>
                <div class="float-end">
                    <a href="{{ route('tag.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('tag.store') }}" method="post">
                    @csrf
                    <div class="mb-3 row">
                        <label for="name_tag" class="col-md-4 col-form-label text-md-end text-start">Nombre de etiqueta</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('name_tag') is-invalid @enderror" id="name_tag" name="name_tag" value="{{ old('name_tag') }}">
                            @if ($errors->has('name_tag'))
                                <span class="text-danger">{{ $errors->first('name_tag') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="description" class="col-md-4 col-form-label text-md-end text-start">descripcion</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description') }}">
                            @if ($errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="color" class="col-md-4 col-form-label text-md-end text-start">color</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color') }}">
                            @if ($errors->has('color'))
                                <span class="text-danger">{{ $errors->first('color') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Add Tag">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>    
</div>
    
@endsection