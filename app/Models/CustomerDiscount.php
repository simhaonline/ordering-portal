<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\CustomerDiscount.
 *
 * @mixin Eloquent
 */
class CustomerDiscount extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_code', 'code');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function show()
    {
        return self::with('customer')->whereHas('customer')->orderBy('customer_code', 'asc')->get();
    }

    /**
     * @param $customer
     * @param $percent
     * @param $id
     *
     * @return bool|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|void|null
     */
    public static function store($customer, $percent, $id = null)
    {
        if ($id) {
            return self::edit($percent, $id);
        }

        $discount = new self;

        $discount->customer_code = $customer;
        $discount->percent = $percent;

        if ($discount->save()) {
            return self::with('customer')->find($discount->id);
        }

        return false;
    }

    /**
     * @param $percent
     * @param $id
     *
     * @return bool|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     */
    public static function edit($percent, $id)
    {
        $discount = self::find($id);

        $discount->percent = $percent;

        if ($discount->save()) {
            return self::with('customer')->find($discount->id);
        }

        return false;
    }
}
