<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Menu_Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function RegisterOrder(Request $request)
    {
        try {

            $user_id = $request->user_id;
            $item_id = $request->item_id;
            $status = $request->status;
            $quantity = $request->quantity;

            if (
                strlen(trim($user_id)) <= 0
                || strlen(trim($item_id)) <= 0
                || strlen(trim($status)) <= 0
                || strlen(trim($quantity)) <= 0
            ) {
                return response()->json(['msg' => 'Invalid or nonexistent data'], 401);
            }
            $stock_quantity = Menu_Item::where("item_id",$item_id)->value("stock_quantity");
            //Log::info($stock_quantity[0]->stock_quantity);

            if(intval($quantity) > intval($stock_quantity)){
                return response()->json(['msg' => 'Quantity of items exceeds available stock'], 422);
            }
            Orders::create([
                "user_id" => $user_id,
                "item_id" => $item_id,
                "status" => $status,
                "quantity" => $quantity
            ]);


            return response()->json(["msg" => "Successful registered order"], 200);
        } catch (\Exception $e) {
            Log::error('Erro no metodo RegisterOrder:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
