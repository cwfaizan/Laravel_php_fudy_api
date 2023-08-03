<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Header;

class CategoryController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    #[Header("Authorization", example: "Auth token is required")]
    public function index()
    {
        return $this->successResponse(Category::all(), 'Categories List');
    }

    /**
     * Store a newly created resource in storage.
     */
    #[Header("Authorization", example: "Auth token is required")]
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    #[Header("Authorization", example: "Auth token is required")]
    public function destroy(string $id)
    {
        //
    }
}
