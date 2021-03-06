<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ExpectedStock.
 *
 * @mixin \Eloquent
 * @property string $product
 * @property int $quantity
 * @property \Illuminate\Support\Carbon $due_date
 */
class ExpectedStock extends Model
{
    protected $table = 'expected_stock';

    protected $dates = ['due_date'];

    public $timestamps = false;
}
