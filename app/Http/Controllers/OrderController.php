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
                || strlen(string: trim($item_id)) <= 0
                || strlen(trim($status)) <= 0
                || strlen(trim($quantity)) <= 0
            ) {
                return response()->json(['msg' => 'Invalid or nonexistent data'], 401);
            }
            $stock_quantity = Menu_Item::where("item_id", $item_id)->value("stock_quantity");
            //Log::info($stock_quantity[0]->stock_quantity);

            if (intval($quantity) > intval($stock_quantity)) {
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
    public function UpdateQtdOrder(Request $request, $id)
    {
        try {
            $item_id = $request->item_id;
            $quantity = $request->quantity;

            if (
                strlen(string: trim($item_id)) <= 0
                || strlen(trim($quantity)) <= 0
            ) {
                return response()->json(['msg' => 'Invalid or nonexistent quantity'], 401);
            }

            $stock_quantity = Menu_Item::where("item_id", $item_id)->value("stock_quantity");
            //Log::info($stock_quantity[0]->stock_quantity);

            if (intval($quantity) > intval($stock_quantity)) {
                return response()->json(['msg' => 'Quantity of items exceeds available stock'], 422);
            }
            $rowChanged = Orders::where("order_id", $id)->update([
                "quantity" =>  $quantity
            ]);

            if ($rowChanged === 0) {
                return response()->json(['msg' => 'No matching item found or value already the same'], 404);
            }

            return response()->json(["msg" => "Successful update order"], 200);
        } catch (\Exception $e) {
            Log::error('Erro no metodo UpdateQtdOrder:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    public function GetOrder(Request $request)
    {
        try {
            $user_id = $request->query("user_id");

            if (
                strlen(string: trim($user_id)) <= 0
            ) {
                return response()->json(['msg' => 'Invalid or nonexistent user_id'], 401);
            }
            $data = Orders::where("user_id", $user_id)->get();
            if (!$data) {
                return response()->json(['error' => 'Internal Server Error'], 500);
            }
            return response()->json(["msg" => "Successful get order", "data" => $data], 200);
        } catch (\Exception $e) {
            Log::error('Erro no metodo GetOrder:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
