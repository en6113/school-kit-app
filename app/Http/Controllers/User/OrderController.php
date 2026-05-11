<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\OrderRequest;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('products')->get();

        return view('orders.index', compact('orders'));
    }

    public function show(string $id)
    {
        $order = Order::findOrFail($id)->load([
            'details.product',
            'details.productSize',
            ]);

        return view('orders.show', compact('order'));
    }

    public function create()
    {
        // 注文確定前なので、DBのOrderではなく「カートの中身」を取得する
        $cart = Cart::where('user_id', auth()->id())->with('cartDetails.product')->first();

        $totalAmount = $cart ? $cart->getTotalAmount() : 0;
        $cartDetails = $cart ? $cart->cartDetails : collect();

        // 注文確認画面に、これから注文する内容（カートの中身）を渡す
        return view('orders.create', compact('cartDetails', 'totalAmount'));
    }

    public function store(OrderRequest $request)
    {
        // 二重送信防止（キャッシュ等を利用）
        $idempotencyKey = $request->input('idempotency_key');
        if (Cache::has("order_processing_{$idempotencyKey}")) {
            return response()->json(['message' => '現在処理中です'], 429);
        }
        Cache::put("order_processing_{$idempotencyKey}", true, 60); // 60秒間ロック

        try {
            // トランザクション処理
            $order = DB::transaction(function () use ($request) {
                // 1. カートを取得
                $cart = Cart::where('user_id', auth()->id())->with('cartDetails.product')->first();
                if (!$cart || $cart->cartDetails->isEmpty()) {
                    throw new \Exception('カートが空です。');
                }

                $cartDetails = $cart->cartDetails;

                foreach ($cartDetails as $detail) {
                    // 2. 在庫チェック ＆ ロック
                    // lockForUpdate() で他からの更新を一時ブロック
                    $product = Product::where('id', $detail->product_id)->lockForUpdate()->first();

                    if ($product->stock < $detail->quantity) {
                        throw new \Exception("商品「{$product->name}」の在庫が足りません。");
                    }

                    // 3. 在庫を減らす
                    $product->decrement('stock', $detail->quantity);
                }

                // 4. 注文レコードの作成
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'total_amount' => $cart->getTotalAmount(),
                    'delivery_method' => $request->delivery_method,
                    'payment_method' => $request->payment_method,
                    'status' => 1,//1:準備中、2:発送済み、0:キャンセル
                ]);

                // 5. 注文明細の保存
                $orderDetailsData = $cartDetails->map(function ($detail) {
                    return [
                        'product_id' => $detail->product_id,
                        'product_size_id' => $detail->product_size_id,
                        'quantity' => $detail->quantity,
                        'price_at_purchase' => $detail->product->price,
                    ];
                })->toArray();

                $order->details()->createMany($orderDetailsData);

                return $order;
            });

            // 6. 成功したらカートを空にする
            Cart::where('user_id', auth()->id())->delete();

            // 7. 完了後、注文一覧ページへリダイレクト
            return redirect()->route('orders.index')->with('success', '注文を確定しました！');

        } catch (\Exception $e) {
            // エラー時は自動でロールバックされる
            return response()->json(['error' => $e->getMessage()], 400);
        } finally {
            // 処理が終わったら二重送信防止ロックを解除（または時間切れを待つ）
            Cache::forget("order_processing_{$idempotencyKey}");
        }
    }

    public function cancel(string $id)
    {
        // 1. 対象の注文を取得
        $order = Order::findOrFail($id);

        // 2. 権限チェック
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('orders.index')->with('error', 'この注文を削除する権限がありません。');
        }

        // 3. ステータスチェック
        if ($order->status !== 1) {
            return redirect()->back()->with('error', '対象のご注文は発送済みのためキャンセルできません。');
        }

        try {
            // 4. トランザクション開始
            DB::transaction(function () use ($order) {
                // 5. 在庫を元に戻す
                foreach ($order->details as $detail) {
                    // $detail->product は OrderDetails モデルから Product モデルへのリレーション
                    $detail->product()->increment('stock', $detail->quantity);
                }

                // 6. 注文の削除(ステータスをキャンセルに変更)
                $order->update(['status' => 0]);
            });

            return redirect()->route('orders.index')->with('success', '注文をキャンセルしました。');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'キャンセル処理に失敗しました。');
        }
    }
}
