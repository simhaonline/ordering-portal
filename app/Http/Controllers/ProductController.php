<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ExpectedStock;
use App\Models\HomeLinks;
use App\Models\Product;
use App\models\ProductSpec;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Input;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Product index, if categories are given, gets the sub categories.
     * If no sub categories for that level, display the products.
     *
     * @param string $category_one [Optional]
     * @param string $category_two [Optional]
     * @param string $category_three [Optional]
     * @param string $category_four [Optional]
     * @param string $category_five [Optional]
     *
     * @return Factory|View
     */
    public function index($category_one = null, $category_two = null, $category_three = null, $category_four = null, $category_five = null)
    {
        $categories = [
            'level_1' => decodeUrl($category_one),
            'level_2' => decodeUrl($category_two),
            'level_3' => decodeUrl($category_three),
            'level_4' => decodeUrl($category_four),
            'level_5' => decodeUrl($category_five),
        ];

        if ($categories['level_1'] !== null) {
            $products = Product::list($categories);

            return view('products.products', compact('categories', 'products'));
        }

        $links['categories'] = HomeLinks::categories();

        return view('products.index', compact('links', 'categories'));
    }

    /**
     * Product details page.
     *
     * @param $product_code
     * @return Factory|View
     */
    public function show($product_code)
    {
        $previous_categories = str_replace(url('/'), '', url()->previous());
        $category_array = array_values(array_filter(explode('/', $previous_categories)));

        $product = Product::show(decodeUrl($product_code));

        $categories = [
            'level_1' => isset($category_array[1]) ? decodeUrl($category_array[1]) : '',
            'level_2' => isset($category_array[2]) ? decodeUrl($category_array[2]) : '',
            'level_3' => isset($category_array[3]) ? decodeUrl($category_array[3]) : '',
            'level_4' => isset($category_array[4]) ? decodeUrl($category_array[4]) : '',
            'level_5' => isset($category_array[5]) ? decodeUrl($category_array[5]) : '',
        ];

        if (isset($category_array[1]) && strpos($category_array[1], 'search') === 0) {
            $categories = [
                'level_1' => 'search',
                'query' => substr(decodeUrl($category_array[1]), 13)
            ];
        }

        return view('products.show', compact('categories', 'product'));
    }

    /**
     * Product search based on code, name, description.
     *
     * @return Factory|View
     */
    public function search()
    {
        $search_term = urldecode(request('query'));

        $products = Product::search($search_term);

        $categories = [
            'level_1' => 'search',
            'query' => $search_term
        ];

        return view('products.products', compact('products', 'categories'));
    }
}
