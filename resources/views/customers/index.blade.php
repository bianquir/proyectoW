@extends('layouts.landing')
@section('title','Clientes - CRUD')
@section('content')

<div>
    <a href="{{ ('customer/create') }}" class="btn btn-primary btn-sm">Crear cliente</a>
</div>

<div class="row justify-content-center mt-3">
    <div class="col-md-12">

        @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                {{ $message }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                
                <table class="table table-striped table-bordered">
                    <thead class="text-center">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Id</th>
                        <th scope="col">Id_mensaje</th>
                        <th scope="col">Id Tag</th>
                        <th scope="col">Id orden</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">apellido</th> 
                        <th scope="col">Celular</th>
                        <th scope="col">Email</th>
                      </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse ($customers as $customer)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->id_message }}</td>
                            <td>{{ $customer->tag_id }}</td>
                            <td>{{ $customer->id_order }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->lastname }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>
                                <form action="{{ route('customer.destroy', $customer->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Editar</a>   

                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Quieres eliminar a este cliente?');"><i class="bi bi-trash"></i> Eliminar</button>
                                </form> 
                            </td>
                        </tr>
                        @empty
                            <td colspan="7">
                                <span class="text-danger">
                                    <strong>No customer Found!</strong>
                                </span>
                            </td>
                        @endforelse
                    </tbody>
                  </table>

                 {{ $customers->links() }}

            </div>
        </div>
    </div>    
</div>

@endsection