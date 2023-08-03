<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Header;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[Header("Authorization", example: "Auth token is required")]
    public function index()
    {
        return Category::with(['recipes'])->simplePaginate(10);
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
