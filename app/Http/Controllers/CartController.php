<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartDetail;

class CartController extends Controller
{
    public function store(Request $request)
    {
        // 1. バリデーション（入力チェック）
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_size_id' => 'nullable|exists:product_sizes,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = auth()->id();

        // 2. ユーザーの「現在のカート」を取得、なければ作成
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        // 3. すでに同じ商品（＋同じサイズ）がカートに入っているか確認
        // Cart_detailsテーブル: cart_id, product_id, product_size_id
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

        // 4. カート一覧へリダイレクト
        return redirect()->route('cart.index')->with('message', 'カートに商品を追加しました！');
    }

    public function index()
    {
        // ログインユーザーのカートを取得（リレーションをロード）
        $cart = Cart::where('user_id', auth()->id())
            ->with(['cartDetails.product.productImages', 'cartDetails.productSize'])
            ->first();

        // Cartモデルの合計金額を計算するgetTotalAmountメソッドを使用
        $totalAmount = $cart ? $cart->getTotalAmount() : 0;

        return view('cart.index', compact('cart', 'totalAmount'));
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
