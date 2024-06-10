@extends('layouts.landing')

@section('content')

<div class="row justify-content-center mt-3">
    <div class="col-md-8">

        @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                {{ $message }}
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Edit Tag
                </div>
                <div class="float-end">
                    <a href="{{ route('tag.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('tag.update', $tag->id) }}" method="post">
                    @csrf
                    @method("PUT")

                    <div class="mb-3 row">
                        <label for="name_tag" class="col-md-4 col-form-label text-md-end text-start">Nombre de etiqueta</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('name_tag') is-invalid @enderror" id="name_tag" name="name_tag" value="{{ $tag->name_tag }}">
                            @if ($errors->has('name_tag'))
                                <span class="text-danger">{{ $errors->first('name_tag') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="description" class="col-md-4 col-form-label text-md-end text-start">description</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ $tag->description }}">
                            @if ($errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="color" class="col-md-4 col-form-label text-md-end text-start">color</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="{{ $tag->color}}">
                            @if ($errors->has('color'))
                                <span class="text-danger">{{ $errors->first('color') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Update">
                    </div>
                </form>
            </div>
        </div>
    </div>    
</div>
@endsection