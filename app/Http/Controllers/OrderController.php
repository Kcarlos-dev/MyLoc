<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Menu_Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
            $data = Menu_Item::where("item_id", $item_id)->first(['stock_quantity', 'price']);
            $stock_quantity = $data->stock_quantity;
            $price =    $data->price;
            //Log::info($stock_quantity[0]->stock_quantity);

            if (intval($quantity) > intval($stock_quantity)) {
                return response()->json(['msg' => 'Quantity of items exceeds available stock'], 422);
            }
            $order_price =  $quantity * $price;
            //Log::info($order_price);
            Orders::create([
                "user_id" => $user_id,
                "item_id" => $item_id,
                "status" => $status,
                "order_price" => $order_price,
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
            /* if ($request->type == "status") {
                $rowChanged = Orders::where("order_id", $id)->update([
                    "status" =>  $request->status
                ]);

                if ($rowChanged === 0) {
                    return response()->json(['msg' => 'No matching item found or value already the same'], 404);
                }
                return response()->json(["msg" => "Successful update order"], 200);
            }*/
            $item_id = $request->item_id;
            $quantity = $request->quantity;

            if (
                strlen(string: trim($item_id)) <= 0
                || strlen(trim($quantity)) <= 0
            ) {
                return response()->json(['msg' => 'Invalid or nonexistent quantity'], 401);
            }

            $data = Menu_Item::where("item_id", $item_id)->first(['stock_quantity', 'price']);
            $stock_quantity = $data->stock_quantity;
            $price =    $data->price;
            $order_price =  $quantity * $price;
            if (intval($quantity) > intval($stock_quantity)) {
                return response()->json(['msg' => 'Quantity of items exceeds available stock'], 422);
            }
            $rowChanged = Orders::where("order_id", $id)->update([
                "quantity" =>  $quantity,
                "order_price" => $order_price
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
            $data = DB::table('orders')
                ->join('menu__items', 'orders.item_id', "=", "menu__items.item_id")
                ->select(
                    'orders.user_id',
                    'orders.item_id',
                    'menu__items.name',
                    'orders.status',
                    "orders.order_price",
                    'orders.quantity'
                )
                ->where('orders.user_id', $user_id)
                ->get();

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
    public function DeleteOrder($order_id)
    {
        try {

            if (
                strlen(string: trim($order_id)) <= 0
            ) {
                return response()->json(['msg' => 'Invalid or nonexistent order_id'], 401);
            }
            $order = Orders::where("order_id", $order_id)->delete();

            if (!$order) {
                return response()->json(['msg' => 'Order not found'], 404);
            }
            return response()->json(["msg" => "Order deleted from database"], 200);
        } catch (\Exception $e) {
            Log::error('Erro no metodo DeleteOrder:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
