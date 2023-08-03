<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\UrlParam;

class RecipeController extends Controller
{
    use ApiResponser;
    /**
     * Display a list of the Recipe.
     */
    #[Header("Authorization", example: "Auth token is required")]
    #[QueryParam("category_id", "int", description: "category id of the Recipe is optional")]
    public function index(Request $req)
    {
        $sql = array();
        if ($req->has('category_id') && $req->filled('category_id')) {
            array_push($sql, ['category_id', '=', (int)$req->query('category_id')]);
        }
        if (!empty($sql)) {
            return $this->successResponse(Recipe::where($sql)->get(), 'Recipe List');
        } else {
            return $this->successResponse(Recipe::with(['category'])->get(), 'Recipe List');
        }
    }

    /**
     * Store a newly created Recipe in storage.
     */
    #[Header("Authorization", example: "Auth token is required")]
    #[BodyParam("title", "string", description: "Recipe title is required")]
    #[BodyParam("price", "double", description: "Recipe price is required")]
    #[BodyParam("quantity", "int", description: "Recipe quantity is required")]
    #[BodyParam("category_id", "int", description: "category id of the Recipe is required")]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'price' => 'required',
            'quantity' => 'required',
            'category_id' => ['required', Rule::in([1, 2, 3, 4, 5, 6, 7, 8]),],
        ],);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        $recipe = Recipe::Create([
            'title' => $request->title,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
            'image_url' => 'https://source.unsplash.com/random',
        ]);
        return $this->successResponse($recipe, 'Recipe Created', 201);
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
    #[Header("Authorization", example: "Auth token is required")]
    #[UrlParam("id", "int", example: 1, description: "Recipe id is required")]
    #[BodyParam("title", "string", description: "Recipe title is required")]
    #[BodyParam("price", "double", description: "Recipe price is required")]
    #[BodyParam("quantity", "int", description: "Recipe quantity is required")]
    #[BodyParam("category_id", "int", description: "category id of the Recipe is required")]
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'price' => 'required',
            'quantity' => 'required',
            'category_id' => ['required', Rule::in([1, 2, 3, 4, 5, 6, 7, 8]),],
        ],);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        Recipe::where('id', $id)->update([
            'title' => $request->title,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
            'image_url' => 'https://source.unsplash.com/random',
        ]);
        return $this->successResponse(null, 'Recipe Updated', 200);
    }

    /**
     * Remove the specified Recipe from storage.
     */
    #[Header("Authorization", example: "Auth token is required")]
    #[UrlParam("id", "int", example: 1, description: "Recipe id is required")]
    public function destroy(string $id)
    {
        $rec = Recipe::where('id', $id);
        if ($rec->exists()) {
            $rec->delete();
            return $this->successResponse(null, 'Recipe deleted');
        }
        return $this->errorResponse(['id' => 'Invalid Recipe details'], 'Recipe not found');
    }
}
