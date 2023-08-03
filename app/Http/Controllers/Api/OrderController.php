<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Maize;
use App\Models\Order;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\UrlParam;

class OrderController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    #[Header("Authorization", example: "Auth token is required")]
    #[QueryParam("table_id", "int", description: "table id of the order is optional")]
    #[QueryParam("is_completed", "int", description: "the order is completed 0 or 1")]
    public function index(Request $req)
    {
        $sql = array();
        if ($req->has('table_id') && $req->filled('table_id')) {
            array_push($sql, ['maize_id', '=', (int)$req->query('table_id')]);
        }
        if ($req->has('is_completed') && $req->filled('is_completed')) {
            array_push($sql, ['is_completed', '=', (int)$req->query('is_completed')]);
        }
        array_push($sql, ['user_id', '=', Auth::id()]);
        if (!empty($sql)) {
            return $this->successResponse(Order::where($sql)->get(), 'Order List');
        } else {
            return $this->successResponse(Order::with(['maize'])->get(), 'Order List');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    #[Header("Authorization", example: "Auth token is required")]
    #[BodyParam("table_id", "int", description: "table_id is required")]
    #[BodyParam("recipe_id", "int", description: "recipe_id is required")]
    #[BodyParam("quantity", "int", description: "quantity is required")]
    #[BodyParam("unit_price", "double", description: "unit_price of the Recipe is required")]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table_id' => 'required',
            'recipe_id' => 'required',
            'quantity' => 'required',
            'unit_price' => 'required'
        ],);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        $recipe = Order::Create([
            'user_id' => Auth::id(),
            'maize_id' => $request->table_id,
            'recipe_id' => $request->recipe_id,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'user_id' => $request->user_id,
            'is_completed' => 0,
        ]);
        Maize::where('id', $request->table_id)->update(['booked' => 1]);
        return $this->successResponse($recipe, 'Order Created', 201);
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
    #[Header("Authorization", example: "Auth token is required")]
    #[UrlParam("id", "int", example: 1, description: "Recipe id is required")]
    #[BodyParam("table_id", "int", description: "table_id is required")]
    #[BodyParam("recipe_id", "int", description: "recipe_id is required")]
    #[BodyParam("quantity", "int", description: "quantity is required")]
    #[BodyParam("unit_price", "double", description: "unit_price of the Recipe is required")]
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'table_id' => 'required',
            'recipe_id' => 'required',
            'quantity' => 'required',
            'unit_price' => 'required'
        ],);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        $recipe = Order::where([['user_id', Auth::id()], ['recipe_id', $request->recipe_id], ['is_completed', 0]])->update([
            'maize_id' => $request->table_id,
            'recipe_id' => $request->recipe_id,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'is_completed' => 0,
        ]);
        return $this->successResponse($recipe, 'Order Updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    #[Header("Authorization", example: "Auth token is required")]
    #[UrlParam("id", "int", example: 1, description: "Recipe id is required")]
    public function destroy(string $id)
    {
        $rec = Order::where([['user_id', Auth::id()], ['recipe_id', $id], ['is_completed', 0]]);
        if ($rec->exists()) {
            $rec->delete();
            return $this->successResponse(null, 'Order deleted');
        }
        return $this->errorResponse(['id' => 'Invalid Order details'], 'Order not found');
    }

    #[Header("Authorization", example: "Auth token is required")]
    #[UrlParam("id", "int", example: 1, description: "table id is required")]
    public function destroyCompleteOrder(string $id)
    {
        $rec = Order::where([['user_id', Auth::id()], ['maize_id', $id], ['is_completed', 0]]);
        if ($rec->exists()) {
            $rec->delete();
            Maize::where('id', $id)->update(['booked' => 0]);
            return $this->successResponse(null, 'Order deleted');
        }
        return $this->errorResponse(['id' => 'Invalid Order details'], 'Order not found');
    }
}
