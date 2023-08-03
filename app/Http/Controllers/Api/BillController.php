<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Maize;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Header;

class BillController extends Controller
{
    #[Header("Authorization", example: "Auth token is required")]
    #[BodyParam("table_id", "int", description: "table_id is required")]
    public function printBill(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table_id' => 'required',
        ],);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        // PDF Code here
        $recipe = Order::where([['user_id', Auth::id()], ['maize_id', $request->table_id], ['is_completed', 0]])->update([
            'is_completed' => 1,
        ]);
        Maize::where('id', $request->table_id)->update(['booked' => 0]);
        return $this->successResponse($recipe, 'Bill Printed', 200);
    }
}
