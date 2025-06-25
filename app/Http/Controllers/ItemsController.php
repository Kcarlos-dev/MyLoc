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
}
