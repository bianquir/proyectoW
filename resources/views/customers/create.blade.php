@extends('layouts.landing')

@section('title','Crear Clientes')

@section('content')

<div class="row justify-content-center mt-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('customer.store') }}" method="post">
                    @csrf

                    <div class="mb-3 row">
                        <label for="dni" class="col-md-4 col-form-label text-md-end text-start">Dni</label>
                        <div class="col-md-6">
                          <input type="integer" class="form-control @error('dni') is-invalid @enderror" id="dni" name="dni" value="{{ old('dni') }}">
                            @if ($errors->has('dni'))
                                <span class="text-danger">{{ $errors->first('dni') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="cuil" class="col-md-4 col-form-label text-md-end text-start">Cuil</label>
                        <div class="col-md-6">
                          <input type="integer" class="form-control @error('cuil') is-invalid @enderror" id="cuil" name="cuil" value="{{ old('cuil') }}">
                            @if ($errors->has('cuil'))
                                <span class="text-danger">{{ $errors->first('cuil') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start">Nombre</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="lastname" class="col-md-4 col-form-label text-md-end text-start">Apellido</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ old('lastname') }}">
                            @if ($errors->has('lastname'))
                                <span class="text-danger">{{ $errors->first('lastname') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="phone" class="col-md-4 col-form-label text-md-end text-start">Celular</label>
                        <div class="col-md-6">
                          <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                            @if ($errors->has('phone'))
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label text-md-end text-start">Email</label>
                        <div class="col-md-6">
                          <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="address" class="col-md-4 col-form-label text-md-end text-start">Direccion</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}">
                            @if ($errors->has('address'))
                                <span class="text-danger">{{ $errors->first('address') }}</span>
                            @endif
                        </div>
                    </div>


                    {{-- <div class="mb-3 row">
                        <label for="tag_id" class="col-md-4 col-form-label text-md-end  text-start">Elija el tag para el cliente</label>
                        <div class="col-md-6">
                            <select id="tag_id" name="tag_id" class="text-black form-control @error('tag_id') is-invalid @enderror" value="{{ old('tag_id') }}">
                                <option value="" selected disabled>Selecciona una etiqueta</option>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('tag_id'))
                                <span class="text-danger">{{ $errors->first('tag_id') }}</span>
                            @endif
                        </div>
                    </div> --}}

                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Agregar cliente">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>    
</div>

@endsection