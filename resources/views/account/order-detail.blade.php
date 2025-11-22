@extends('layout')

@section('title', 'Detalle del Pedido')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Detalle del Pedido #{{ $order->order_number }}</h1>
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <p><span class="font-semibold">Fecha:</span> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p><span class="font-semibold">Estado:</span> {{ ucfirst($order->status) }}</p>
        <p><span class="font-semibold">Total:</span> ${{ number_format($order->total, 2) }}</p>
        <p><span class="font-semibold">Método de pago:</span> {{ $order->payment_method }}</p>
        <p><span class="font-semibold">Notas:</span> {{ $order->notes ?? '-' }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Productos</h2>
        <table class="min-w-full divide-y divide-gray-200 mb-4">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr class="bg-gray-50">
                        <td class="px-4 py-2">{{ $item->product_name }}</td>
                        <td class="px-4 py-2">{{ $item->quantity }}</td>
                        <td class="px-4 py-2">${{ number_format($item->price, 2) }}</td>
                        <td class="px-4 py-2">${{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <a href="{{ route('account.index') }}" class="inline-block text-blue-600 hover:text-blue-800 mt-4">← Volver a Mi Cuenta</a>
</div>
@endsection
