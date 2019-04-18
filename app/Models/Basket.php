<?php

namespace App\Models;

use Eloquent;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Response;
use Auth;
use Storage;

/**
 * App\Models\Basket
 *
 * @mixin Eloquent
 */
class Basket extends Model
{
    protected $table = 'basket';
    public $timestamps = false;

    /**
     * Return product relationship on product code from basket.
     *
     * @return BelongsTo
     */
    public function productDetails()
    {
        return $this->belongsTo(Products::class, 'product', 'product');
    }

    /**
     * Return relationship for prices.
     *
     * @return BelongsTo
     */
    public function prices()
    {
        return $this->belongsTo(Prices::class, 'product', 'product')->where('prices.customer_code', Auth::user()->customer->customer_code);
    }

    /**
     * Return all basket lines based on customer code
     *
     * @param null $delivery
     * @param null $country
     * @return Basket[]|Collection
     */
    public static function show($delivery = null, $country = null)
    {
        $lines = (new Basket)->selectRaw('basket.product as product, basket.customer_code as customer_code, 
                                                    basket.quantity as quantity, price, break1, price1, break2, price2, 
                                                    break3, price3, name, uom, not_sold, stock_levels.quantity as stock_level')
            ->where('basket.customer_code', Auth::user()->customer->customer_code)
            ->join('prices', 'basket.product', '=', 'prices.product')
            ->where('prices.customer_code', Auth::user()->customer->customer_code)
            ->join('products', 'basket.product', '=', 'products.product')
            ->join('stock_levels', 'basket.product', '=', 'stock_levels.product')
            ->get();

        $goods_total = 0;
        $product_lines = [];

        foreach ($lines as $line) {
            switch (true) {
                case $line->quantity >= $line->break3 && $line->price3 != 0:
                    $net_price = $line->price3;
                    break;
                case $line->quantity >= $line->break2 && $line->price2 != 0:
                    $net_price = $line->price2;
                    break;
                case $line->quantity >= $line->break1 && $line->price1 != 0:
                    $net_price = $line->price1;
                    break;
                default:
                    $net_price = $line->price;
            }

            $goods_total = $goods_total + (discount($net_price) * $line->quantity);

            if (Storage::disk('public')->exists('product_images/' . $line->product . '.png')) {
                $image = asset('product_images/' . $line->product . '.png');
            } else {
                $image = asset('images/no-image.png');
            }

            $product_lines[] = [
                'product' => $line->product,
                'name' => $line->name,
                'uom' => $line->uom,
                'stock' => $line->stock_level,
                'image' => $image,
                'quantity' => $line->quantity,
                'price' => currency(discount($net_price) * $line->quantity, 2),
                'unit_price' => currency(discount($net_price), 4),
            ];
        }

//        $small_order_charge = static::smallOrderCharge($goods_total);
        $small_order_charge = SmallOrderCharge::value($goods_total, $country);

        if ($delivery) {
            $delivery_charge = 0;
        } else {
            $delivery_charge = 10;
        }

        return [
            'summary' => [
                'goods_total' => currency($goods_total, 2),
                'shipping' => currency($delivery_charge, 2),
                'sub_total' => currency($goods_total, 2),
                'small_order_charge' => currency($small_order_charge, 2),
                'vat' => currency(vatAmount($goods_total + $small_order_charge + $delivery_charge), 2),
                'total' => currency(vatIncluded($goods_total + $small_order_charge + $delivery_charge), 2)
            ],
            'line_count' => count($product_lines),
            'lines' => $product_lines,
        ];
    }

    /**
     * Add array of order lines into customers basket.
     *
     * @param $order_lines
     * @return mixed
     */
    public static function store($order_lines)
    {
        $user_id = Auth::user()->id;
        $customer_code = Auth::user()->customer->customer_code;

        foreach ($order_lines as $line) {
            // Check that the customer can buy the product.
            $customer_can_buy = Products::show($line['product']);

            if ($customer_can_buy) {
                $product = static::exists($line['product']);
                $line['user_id'] = $user_id;
                $line['customer_code'] = $customer_code;

                if ($product) {
                    $basket_quantity = $product->quantity;

                    (new basket)->where('product', $line['product'])->update(['quantity' => $basket_quantity + $line['quantity']]);
                } else {
                    (new Basket)->insert($line);
                }
            }
        }

        return ['basket_updated' => isset($product) ? true : false];
    }

    /**
     * Checks if a product already exists in the customers basket.
     *
     * @param $product_code
     * @return Basket|Model|object|null
     */
    public static function exists($product_code)
    {
        return (new Basket)->where('customer_code', Auth::user()->customer->customer_code)->where('product', $product_code)->first();
    }

    /**
     * Remove all items from the basket for the logged in customer.
     *
     * @return bool|int|null
     * @throws Exception
     */
    public static function clear()
    {
        return (new Basket)->where('customer_code', Auth::user()->customer->customer_code)->delete();
    }

    /**
     * Delete an individual product line from the basket.
     *
     * @param $product
     * @return ResponseFactory|Response
     */
    public static function destroyLine($product)
    {
        return (new Basket)->where('customer_code', Auth::user()->customer->customer_code)
            ->where('user_id', Auth::user()->id)
            ->where('product', $product)
            ->delete();
    }

    /**
     * Update the quantity for a given product line in the basket.
     *
     * @param $product
     * @param $quantity
     * @return int
     */
    public static function updateLine($product, $quantity)
    {
        return (new Basket)->where('customer_code', Auth::user()->customer->customer_code)
            ->where('user_id', Auth::user()->id)
            ->where('product', $product)
            ->update(['quantity' => $quantity]);
    }

    /**
     * Check if the value of the order reaches the limit of the small
     * order charge, if not return the charge else return 0.
     *
     * @param $value
     * @return int
     */
    public static function smallOrderCharge($value)
    {
        $small_order_limit = 200;
        $small_order_charge = 10;

        return $value > $small_order_limit ? 0 : $small_order_charge;
    }
}
