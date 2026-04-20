<?php

namespace App\Services\Client;

use App\Models\Products;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected $cartKey = 'cart';

    public function getCart()
    {
        return Session::get($this->cartKey, []);
    }

    public function add($productId, $quantity = 1)
    {
        $product = Products::findOrFail($productId);
        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'quantity' => $quantity,
                'price'    => $product->price,
                'image'    => $product->image,
                'slug'     => $product->slug,
            ];
        }

        Session::put($this->cartKey, $cart);
        return $cart;
    }

    public function update($productId, $quantity)
    {
        $cart = $this->getCart();
        
        if (isset($cart[$productId])) {
            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['quantity'] = $quantity;
            }
            Session::put($this->cartKey, $cart);
        }
        
        return $cart;
    }

    public function remove($productId)
    {
        $cart = $this->getCart();
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put($this->cartKey, $cart);
        }
        return $cart;
    }

    public function clear()
    {
        Session::forget($this->cartKey);
    }

    public function count()
    {
        return count($this->getCart());
    }

    public function subtotal()
    {
        $cart = $this->getCart();
        return array_reduce($cart, function($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
    }
}
