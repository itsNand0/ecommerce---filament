<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Obtener o crear un carrito para el usuario actual
     */
    private function getCart()
    {
        if (Auth::check()) {
            // Usuario autenticado
            return Cart::firstOrCreate([
                'user_id' => Auth::id()
            ]);
        } else {
            // Usuario invitado - usar session_id
            $sessionId = session()->getId();
            return Cart::firstOrCreate([
                'session_id' => $sessionId
            ]);
        }
    }

    /**
     * Agregar producto al carrito
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Verificar stock disponible
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'No hay suficiente stock disponible');
        }

        $cart = $this->getCart();

        // Verificar si el producto ya está en el carrito
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Actualizar cantidad
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            if ($newQuantity > $product->stock) {
                return back()->with('error', 'No hay suficiente stock disponible');
            }
            
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            // Crear nuevo item en el carrito
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->sale_price ?? $product->price
            ]);
        }

        return back()->with('success', 'Producto agregado al carrito');
    }

    /**
     * Ver el carrito
     */
    public function index()
    {
        $cart = $this->getCart();
        $cartItems = CartItem::where('cart_id', $cart->id)
            ->with('product')
            ->get();

        return view('cart', [
            'cart' => $cart,
            'cartItems' => $cartItems
        ]);
    }

    /**
     * Actualizar cantidad de un item
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::findOrFail($id);
        
        // Verificar que el item pertenece al carrito del usuario
        $cart = $this->getCart();
        if ($cartItem->cart_id !== $cart->id) {
            abort(403);
        }

        // Verificar stock
        if ($cartItem->product->stock < $request->quantity) {
            return back()->with('error', 'No hay suficiente stock disponible');
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return back()->with('success', 'Cantidad actualizada');
    }

    /**
     * Eliminar item del carrito
     */
    public function remove($id)
    {
        $cartItem = CartItem::findOrFail($id);
        
        // Verificar que el item pertenece al carrito del usuario
        $cart = $this->getCart();
        if ($cartItem->cart_id !== $cart->id) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Producto eliminado del carrito');
    }

    /**
     * Vaciar el carrito
     */
    public function clear()
    {
        $cart = $this->getCart();
        CartItem::where('cart_id', $cart->id)->delete();

        return back()->with('success', 'Carrito vaciado');
    }
}
