<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Creado</title>
</head>
<body>
    <h1>¡Gracias por tu pedido, {{ $order->customer->name }}!</h1>

    <p>Tu pedido ha sido creado con éxito.</p>

    <h3>Detalles del pedido:</h3>
    <ul>
        <li><strong>Fecha del pedido:</strong> {{ \Carbon\Carbon::parse($order->shipping_day)->format('d/m/Y') }}</li>
        <li><strong>Estado:</strong> {{ $order->state }}</li>
    </ul>

    <h3>Productos:</h3>
    <ul>
        @foreach($order->orderDetails as $detail)
            <li>{{ $detail->product->name }} - Cantidad: {{ $detail->quantity }}</li>
        @endforeach
    </ul>

    <p>¡Gracias por confiar en nosotros!</p>
</body>
</html>
