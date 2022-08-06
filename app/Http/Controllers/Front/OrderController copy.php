<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::with([
            'products' => function ($query) {
                $query->with([
                    'reviews',
                    'thumbnail',
                    'brand',
                    'subcategories' => function ($query) {
                        $query->with([
                            'category' => function ($query) {
                                $query->with('supercategory');
                            }
                        ]);
                    },
                ]);
            },
            'status'
        ])
            ->where('user_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->paginate(5);

        return view('front.orders.index', compact('orders'));
    }

    public function edit($order_id)
    {
        $order = Order::with([
            'products' => function ($query) {
                $query->with([
                    'reviews',
                    'thumbnail',
                    'brand',
                    'subcategories' => function ($query) {
                        $query->with([
                            'category' => function ($query) {
                                $query->with('supercategory');
                            }
                        ]);
                    },
                ]);
            },
        ])
            ->findOrFail($order_id);

        return view('front.orders.edit', compact('order'));
    }

    public function shipping()
    {
        $products_id = [];

        // get products id from cart
        $cart_products_id = Cart::instance('cart')->content()->pluck('id')->toArray();

        // get products id from wishlist
        $wishlist_products_id = Cart::instance('wishlist')->content()->pluck('id')->toArray();

        // get products id from cart and wishlist
        $products_id = array_unique(array_merge($cart_products_id, $wishlist_products_id));

        $products = [];

        // get all products data from database with best price
        $products = getBestOfferForProducts($products_id);

        // put products data in cart_products variable
        $cart_products = $products->whereIn('id', $cart_products_id);

        // put products data in wishlist_products variable
        $wishlist_products = $products->whereIn('id', $wishlist_products_id);

        return view('front.orders.shipping', compact('cart_products', 'wishlist_products'));
    }

    public function billing()
    {
        return view('front.orders.billing');
    }

    public function billingCheck(Request $request)
    {
        $data = $request->all();

        ksort($data);

        $hmac = $data['hmac'];

        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success'
        ];

        $concat_data = '';

        foreach ($data as $key => $element) {
            if (in_array($key, $array)) {
                $concat_data .= $element;
            }
        }

        $secret = env('PAYMOB_HMAC');

        $generated_hmac = hash_hmac('SHA512', $concat_data, $secret);

        if ($generated_hmac == $hmac && $data['success'] == 'true') {
            // update the database with the order data
            $order = Order::with([
                'address' => fn ($q) => $q->with('governorate', 'city'),
                'user',
                'products' => fn ($q) => $q->select('products.id', 'products.quantity', 'product_id'),
            ])->updateOrCreate([
                'user_id'       =>  auth()->user()->id,
                'status_id'     =>  1
            ], [
                'payment_status' => 1,
                'payment_details' => [
                    'amount_cents' => $data['amount_cents'],
                    'order_id' => $data['order'],
                    'transaction_id' => $data['id'],
                    'source_data_sub_type' => $data['source_data_sub_type'],
                ]
            ]);

            // create order
            createBostaOrder($order);

            return redirect()->route('front.order.done');
        } else {
            return redirect()->route('front.order.billing')->with('error', __('front/homePage.Payment Failed, Please Try Again'));
        }
    }

    public function done()
    {
        return view('front.orders.done');
    }

    public function cancel($order_id)
    {
        DB::beginTransaction();

        try {
            $order = Order::findOrFail($order_id);

            if ($order->payment_method == 1) {
                // update the database
                returnTotalOrder($order);
                // Cancel Bosta Order
                cancelBostaOrder($order);
            } elseif (($order->payment_method == 2 || $order->payment_method == 3) && $order->payment_status == 1) {
                $refund = $order->subtotal_final + $order->delivery_fees;

                if ($order->created_at->diffInDays() < 1) {
                    if (voidRequestPaymob(json_decode($order->payment_details)->transaction_id)) {
                        // update the database
                        returnTotalOrder($order);
                        // Cancel Bosta Order
                        cancelBostaOrder($order);
                    }
                } else {
                    if (refundRequestPaymob(json_decode($order->payment_details)->transaction_id, $refund)) {
                        // update the database
                        returnTotalOrder($order);
                        // Cancel Bosta Order
                        cancelBostaOrder($order);
                    }
                }
            } elseif ($order->payment_method == 4) {
                if ($order->bosta_id != null) {
                    // update the database
                    returnTotalOrder($order);
                    // Cancel Bosta Order
                    cancelBostaOrder($order);
                } else {
                    // update the database
                    returnTotalOrder($order);
                }
            }

            DB::commit();

            return redirect()->route('front.orders.index')->with('success', __('front/homePage.Order Canceled Successfully'));
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
    }
}
