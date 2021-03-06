<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Price;
use App\Models\Product;
use File;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Storage;

class ProductImageController extends Controller
{
    /**
     * Display the product images page.
     *
     * @return Factory|View
     */
    public function index()
    {
        $products = Price::products();

        return view('product-images.index', compact('products'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkImage(): JsonResponse
    {
        $product = encodeUrl(request('product'));

        $image = Product::checkImage($product);

        if (! $image['found']) {
            return response()->json([
                'image' => false,
                'product' => request('product'),
                'file_name' => str_replace('%2B', ' ', $product).'.png',
            ]);
        }

        return response()->json([
            'image' => true,
        ]);
    }

    /**
     * Upload product images.
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        $image = request()->file('file');

        $name = $image->getClientOriginalName();

        Storage::put($name, File::get($image));

        return response()->json('success');
    }
}
