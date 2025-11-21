<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Mostrar formulario de checkout
     */
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        
        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío');
        }
        
        $cartItems = CartItem::where('cart_id', $cart->id)
            ->with('product')
            ->get();
            
        if ($cartItems->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío');
        }
        
        return view('checkout.index', [
            'cart' => $cart,
            'cartItems' => $cartItems
        ]);
    }

    /**
     * Procesar el checkout
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_email' => 'nullable|email',
            'payment_method' => 'required|in:efectivo,transferencia,tarjeta',
            'notes' => 'nullable|string'
        ]);

        $cart = Cart::where('user_id', Auth::id())->first();
        
        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío');
        }
        
        $cartItems = CartItem::where('cart_id', $cart->id)
            ->with('product')
            ->get();

        if ($cartItems->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío');
        }

        // Verificar stock disponible
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return back()->with('error', "No hay suficiente stock de {$item->product->name}");
            }
        }

        DB::beginTransaction();
        
        try {
            // Calcular totales
            $subtotal = $cart->getTotal();
            $shipping = 0; // Envío gratis
            $tax = 0; // Sin impuestos
            $total = $subtotal + $shipping + $tax;

            // Crear orden
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'shipping_name' => $request->shipping_name,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_phone' => $request->shipping_phone,
                'shipping_email' => $request->shipping_email,
                'notes' => $request->notes,
            ]);

            // Crear items de la orden y actualizar stock
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->price * $item->quantity,
                ]);

                // Reducir stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Vaciar carrito
            CartItem::where('cart_id', $cart->id)->delete();
            $cart->delete();

            DB::commit();

            return redirect()->route('checkout.confirmation', $order->id)
                ->with('success', 'Pedido realizado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar confirmación del pedido
     */
    public function confirmation($orderId)
    {
        $order = Order::with('items.product')
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('checkout.confirmation', ['order' => $order]);
    }
}
