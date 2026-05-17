<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CartRequest;
use App\Models\Cart;
use App\Models\CartDetail;

class CartController extends Controller
{
    //個別商品用、ルート名は'cart.add'
    public function store(CartRequest $request)
    {
        $userId = auth()->id();
        // ユーザーの「現在のカート」を取得、なければ作成
        $cart = Cart::firstOrCreate(['user_id' => $userId]);
        // 同じ商品（＋同じサイズ）がカートに入っていないか確認(Cart_detailsテーブル)
        $cartDetail = CartDetail::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->where('product_size_id', $request->product_size_id)
            ->first();

        if ($cartDetail) {
            // すでに入っている場合は数量を加算
            $cartDetail->quantity += $request->quantity;
            $cartDetail->save();
        } else {
            // 入っていない場合は新しく明細を作成
            CartDetail::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'product_size_id' => $request->product_size_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', '商品をカートに追加しました！');
    }

    //スターターキット用、ルート名は'cart.add_kit'
    public function storeKit(CartRequest $request)
    {
        $userId = auth()->id();
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        // 各商品をループして保存
        foreach ($request->products as $kitItem) {
            $cartDetail = CartDetail::where('cart_id', $cart->id)
                ->where('product_id', $kitItem['product_id'])
                ->where('product_size_id', $kitItem['product_size_id'] ?? null)
                ->first();

            if ($cartDetail) {
                $cartDetail->quantity += $kitItem['quantity'];
                $cartDetail->save();
            } else {
                CartDetail::create([
                    'cart_id' => $cart->id,
                    'product_id' => $kitItem['product_id'],
                    'product_size_id' => $kitItem['product_size_id'] ?? null,
                    'quantity' => $kitItem['quantity'],
                ]);
            }
        }

        return redirect()->route('cart.index')->with('success', 'キットの商品をカートに追加しました！');
    }

    public function index()
    {
        // ログインユーザーのカートを取得（リレーションをロード）
        $cart = Cart::where('user_id', auth()->id())
            ->with(['cartDetails.product.productImages', 'cartDetails.productSize'])
            ->first();

        // Cartモデルの合計金額を計算するgetTotalAmountメソッドを使用
        $totalAmount = $cart ? $cart->getTotalAmount() : 0;

        return view('/user/cart.index', compact('cart', 'totalAmount'));
    }

    public function destroy(CartDetail $cartDetail)
    {
        // 他人のカートを消せないように念のためチェック
        if ($cartDetail->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cartDetail->delete();
        return back()->with('message', '商品を削除しました');
    }
}
