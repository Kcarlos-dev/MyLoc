<?php

namespace App\Http\Controllers;

use App\Models\Menu_Item;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ItemsController extends Controller
{
    public function RegisterItems(Request $request)
    {
        try {
            [
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'category' => $category,
                "stock_quantity" => $stock_quantity,
                "is_available" => $is_available
            ] = $request->only([
                'name',
                'description',
                'price',
                'category',
                'stock_quantity',
                'is_available'
            ]);

            if (
                strlen(trim($name)) <= 0
                || strlen(trim($description)) <= 0
                || strlen(trim($price)) <= 0
                || strlen(trim($category)) <= 0
                || strlen(trim($is_available)) <= 0
                || strlen(trim($stock_quantity)) <= 0
            ) {
                return response()->json(['msg' => 'Need of data'], 401);
            }

            if(Menu_Item::where('name',$name)->first()){
                return response()->json(["Erro"=>"Item $name already in the database"],400);
            }


            Menu_Item::create([
                "name" => $name,
                "description" => $description,
                "price" => $price,
                "category" => $category,
                "stock_quantity" => $stock_quantity,
                "is_available" => filter_var($is_available, FILTER_VALIDATE_BOOLEAN)
            ]);
            return response()->json(["msg" => "Items registered successfully"], 201);
        } catch (\Exception $e) {
            Log::error('Erro no metodo RegisterItems:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    public function GetItems(Request $request)
    {
        try {
            $name = $request->query("name");
            if (strlen(trim($name)) <= 0) {
                $data = Menu_Item::get();
            } else {
                $data = Menu_Item::where('name', $name)->get();
            }
            if (!$data) {
                return response()->json(['error' => 'Internal Server Error'], 500);
            }
            return response()->json(["msg" => "Items found successfully", "data" => $data], 200);
        } catch (\Exception $e) {
            Log::error('Erro no metodo GetItems:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    public function UpdateItems(Request $request)
    {
        try {
            $name = $request->name;
            $stock_quantity = $request->stock_quantity;
            if (
                strlen(trim($name)) <= 0
                || strlen(trim($stock_quantity)) <= 0
            ) {
                return response()->json(['msg' => 'Need of data'], 401);
            }
            $isAvailable = $stock_quantity > 0;

            $rowChanged = Menu_Item::where("name", $name)->update([
                "stock_quantity" => $stock_quantity,
                "is_available" => $isAvailable
            ]);

            if ($rowChanged === 0) {
                return response()->json(['msg' => 'No matching item found or value already the same'], 404);
            }

            return response()->json(["msg" => "Successfully changed product"], 200);
        } catch (\Exception $e) {
            Log::error('Erro no metodo GetItems:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    public function DeleteItems($name)
    {
        try {
            if (
                strlen(trim($name)) <= 0
            ) {
                return response()->json(['msg' => 'Name not found'], 400);
            }
            $item = Menu_Item::where('name', $name)->delete();

            if (!$item) {
                return response()->json(['msg' => 'Item not found'], 404);
            }
            return response()->json(['msg' => 'Item deleted from database'], 200);
        } catch (\Exception $e) {
            Log::error('Erro no metodo DeleteItems:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
