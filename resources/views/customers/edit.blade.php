@extends('layouts.landing')
@section('title','Editar Cliente')
@section('content')

<div class="row justify-content-center mt-3">
    <div class="col-md-8">

        @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                {{ $message }}
            </div>
        @endif 

        <div class="card">
           
            <div class="card-body">
                <form action="{{ route('customer.update', $customer->id) }}" method="post">
                    @csrf
                    @method("PUT")

                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start">Nombre</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $customer->name }}">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="lastname" class="col-md-4 col-form-label text-md-end text-start">Apellido</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ $customer->lastname }}">
                            @if ($errors->has('lastname'))
                                <span class="text-danger">{{ $errors->first('lastname') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="phone" class="col-md-4 col-form-label text-md-end text-start">Celular</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ $customer->phone }}">
                            @if ($errors->has('phone'))
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label text-md-end text-start">Email</label>
                        <div class="col-md-6">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $customer->email }}">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="tag_id" class="col-md-4 col-form-label text-md-end text-start">tag_id</label>
                        <div class="col-md-6">
                            <select id="tag_id" name="tag_id" class="form-control @error('tag_id') is-invalid @enderror text-black bg-white" value="{{ old('tag_id') }}">
                                <option value="" selected disabled>Selecciona una etiqueta</option>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>                            
                            @if ($errors->has('tag_id'))
                                <span class="text-danger">{{ $errors->first('tag_id') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Actualizar">
                    </div>
                    
                </form>
            </div>
          
        </div>
    </div>    
</div>
@endsection