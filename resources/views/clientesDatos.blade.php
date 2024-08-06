@extends('layouts.landing')
@section('content')
    <div class="container">
        <!-- Formulario de búsqueda -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <form action="{{ route('customer.search') }}" method="GET">
                    <div class="form-group d-inline-flex">
                        <input type="text" name="search" class="form-control mr-2" style="width: 300px;" placeholder="DNI, teléfono, nombre">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de Clientes -->
        <div class="row mb-4">
            <div class="col-12">
                <h3>Clientes</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>DNI</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td>{{ $customer->dni }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabla de Pedidos -->
        <div class="row mb-4">
            <div class="col-12">
                <h3>Pedidos</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente ID</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            @foreach($customer->orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->customer_id }}</td>
                                    <td>{{ $order->date }}</td>
                                    <td>{{ $order->total }}</td>
                                    <td>{{ $order->state }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabla de Productos -->
        <div class="row mb-4">
            <div class="col-12">
                <h3>Productos</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Pedido ID</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            @foreach($customer->orders as $order)
                                @foreach($order->products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->description }}</td>
                                        <td>{{ $product->price }}</td>
                                        <td>{{ $product->stock }}</td>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $product->pivot->quantity }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection



