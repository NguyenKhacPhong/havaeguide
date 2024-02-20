<?php

namespace App\Repositories\Cart;

use App\Models\SessionUser;
use App\Models\ShoppingCart;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CartRepository
{
    private $cart;
    public function __construct(ShoppingCart $cart)
    {
        $this->$cart = $cart;
    }
    //API
    public function addToCart($request){
        $sessionUser = SessionUser::where('token', $request->token)->select('user_id')->first();
        $checkCart = ShoppingCart::where('user_id', $sessionUser->user_id)
        ->where('product_id', $request->product_id)->first();
        // dd($checkCart);
        if($checkCart){
            $result = $checkCart->update([
                "quantity"=> $checkCart->quantity + $request->quantity
            ]);
        }else{
            $result = ShoppingCart::create([
                'product_id'=> $request->product_id,
                'user_id'=> $sessionUser->user_id,
                'quantity'=> $request->quantity
            ]);
        }
        
        if($result){
            return response()->json([
                'code'=>200,
                'message'=>"Đã thêm sản phẩm vào giỏ hàng!"
            ],200);
        }else{
            return response()->json([
                'code'=>401,
                'message'=>"Thêm sản phẩm vào giỏ hàng thất bại!"
            ],401);
        }
    }

    public function getCart($request){
        $price = DB::raw("(CASE WHEN products.new_price = 0 THEN products.price ELSE products.new_price END) as price");
        $cartUser = ShoppingCart::join('products', 'shopping_carts.product_id', '=', 'products.id')
        ->join('session_users', 'shopping_carts.user_id', '=', 'session_users.user_id')
        ->where('session_users.token', $request->token)
        ->select('product_id', 'name', 'image',$price, 'shopping_carts.quantity', 'products.quantity as remaining_quantity')->get();
        if(count($cartUser) == 0){
            return response()->json([
                'code'=>404,
                'message'=> 'Không có sản phẩm nào trong giỏ hàng!'
            ],404);
        }
        foreach($cartUser as $cart){
            if($cart->quantity > $cart->remaining_quantity){
                $cart->quantity = $cart->remaining_quantity;
                $cart->save();
            }
        }
        return response()->json([
            'code'=>200,
            'data'=> $cartUser
        ],200);
    }
}
