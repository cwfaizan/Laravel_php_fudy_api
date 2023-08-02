<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    use ApiResponser;
    /**
     * Display a list of the Recipe.
     */
    public function index(Request $req)
    {
        $sql = array();
        if ($req->has('category_id') && $req->filled('category_id')) {
            array_push($sql, ['category_id', '=', (int)$req->query('category_id')]);
        }
        if (!empty($sql)) {
            return Recipe::where($sql)->with(['category'])->simplePaginate(10);
        } else {
            return Recipe::with(['category'])->simplePaginate(10);
        }
    }

    /**
     * Store a newly created Recipe in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified Recipe.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified Recipe in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified Recipe from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
