<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomeLink;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeLinksController extends Controller
{
    /**
     * Display all current categories + adverts.
     *
     * @return Factory|View
     */
    public function index()
    {
        //$adverts = HomeLink::adverts();
        $category_top_level = Category::show(1);
        $categories = HomeLink::categories();

        return view('home-links.index', compact('category_top_level', 'categories'));
    }

    /**
     * Create a new home link.
     */
    public function store()
    {
        request()->validate([
            'name' => 'required',
            'url' => 'required',
            'file' => 'required|mimes:jpeg,jpg,png',
        ]);

        $home_link = HomeLink::store(request());

        if ($home_link) {
            return response()->json([
                'created' => true,
                'data' => $home_link,
            ]);
        }

        return response()->json([
            'created' => false,
        ], 400);
    }

    /**
     * Take the list of items and update the position based on the ID.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePositions()
    {
        $positions_updated = HomeLink::updatePositions(request()->all());

        if ($positions_updated) {
            return response()->json([
                'updated' => true,
            ]);
        }

        return response()->json([
            'updated' => false,
        ], 400);
    }

    /**
     * Deletes a home link with the given ID.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     *
     */
    public function destroy($id): JsonResponse
    {
        if (HomeLink::destroy($id)) {
            return response()->json([
                'deleted' => true,
            ]);
        }

        return response()->json([
            'deleted' => false,
        ], 400);
    }
}
