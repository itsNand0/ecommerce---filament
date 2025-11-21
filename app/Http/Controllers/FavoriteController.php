<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Mostrar lista de favoritos
     */
    public function index()
    {
        $favorites = Auth::user()
            ->favorites()
            ->with('product.category')
            ->get();

        return view('favorites.index', ['favorites' => $favorites]);
    }

    /**
     * Agregar producto a favoritos
     */
    public function toggle(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            // Si ya existe, eliminar de favoritos
            $favorite->delete();
            return back()->with('success', 'Producto eliminado de favoritos');
        } else {
            // Si no existe, agregar a favoritos
            Favorite::create([
                'user_id' => Auth::id(),
                'product_id' => $productId
            ]);
            return back()->with('success', 'Producto agregado a favoritos');
        }
    }

    /**
     * Eliminar de favoritos
     */
    public function remove($id)
    {
        $favorite = Favorite::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $favorite->delete();

        return back()->with('success', 'Producto eliminado de favoritos');
    }
}
