<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Storage;

/**
 * App\Models\Product.
 *
 * @mixin \Eloquent
 */
class Product extends Model
{
    protected $table = 'products';

    public $timestamps = false;

    /**
     * @return string
     */
    public function path(): string
    {
        return "/products/view/{$this->code}";
    }

    /**
     * @return string
     */
    public function image(): string
    {
        $image = str_replace('/', '^', $this->code).'.png';

        if (Storage::disk('public')->exists('product_images/'.$image)) {
            return asset('product_images/'.$image);
        }

        return asset('images/no-image.png');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function prices(): BelongsTo
    {
        return $this->belongsTo(Price::class, 'code', 'product')->where('customer_code', auth()->user()->customer->code);
    }

    /**
     * @return BelongsTo
     */
    public function categories(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'code', 'product');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expectedStock(): HasMany
    {
        return $this->hasMany(ExpectedStock::class, 'product', 'code')->orderBy('due_date', 'asc');
    }

    /**
     * Return all products for the given categories.
     *
     * @param $categories
     *
     * @return mixed
     */
    public static function list($categories)
    {
        return self::whereHas('prices')->whereHas('categories', static function ($query) use ($categories) {
            $query->where('level_1', $categories['level_1'])->where('level_2', $categories['level_2'])->where('level_3', $categories['level_3']);
        })->with('prices')->paginate(10);
    }

    /**
     * Get data for the given product code.
     *
     * @param $product_code
     *
     * @return mixed
     */
    public static function show($product_code)
    {
        return self::where('code', $product_code)->whereHas('prices')->with(['prices', 'expectedStock'])->first();
    }

    /**
     * Take the search parameter and search on multiple columns.
     *
     * @param $search_term
     *
     * @return mixed
     */
    public static function search($search_term)
    {
        return self::where(static function ($query) use ($search_term) {
            $query->whereRaw('upper(products.code) LIKE \'%'.strtoupper($search_term).'%\'')->orWhereRaw('upper(name) LIKE \'%'.strtoupper($search_term).'%\'')->orWhereRaw('upper(description) LIKE \'%'.strtoupper($search_term).'%\'');
        })->whereHas('prices')->paginate(10);
    }

    /**
     * Autocomplete for quick-buy input.
     *
     * @param $search
     *
     * @return mixed
     */
    public static function autocomplete($search)
    {
        return self::select('code')->whereHas('prices')->whereRaw('UPPER(code) like \''.strtoupper($search).'%\'')->orderBy('code', 'asc')->limit(10)->get();
    }

    /**
     * @param $product
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function details($product): JsonResponse
    {
        $product_details = self::where('code', $product)->first();

        if ($product_details) {
            $image_check = self::checkImage($product_details->product);

            return response()->json([
                'product_code' => $product_details->code,
                'description' => $product_details->name,
                'image_file' => $image_check['found'] ? asset($image_check['image']) : null,
            ]);
        }

        return response()->json([
            'message' => 'Product not found',
        ], 404);
    }

    /**
     * Take a list of products and return the first image that exists.
     *
     * @param $products
     *
     * @return array
     */
    public static function checkImage($products): array
    {
        $products = explode(',', $products);

        foreach ($products as $product) {
            $product = str_replace(['%2B', '+'], ' ', encodeUrl($product)).'.png';

            $exists = Storage::disk('public')->exists('product_images/'.$product);

            if ($exists) {
                return [
                    'found' => true,
                    'image' => 'product_images/'.$product,
                ];
            }
        }

        return [
            'found' => false,
            'image' => '/images/no-image.png',
        ];
    }
}
