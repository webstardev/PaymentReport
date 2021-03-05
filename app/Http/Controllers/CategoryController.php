<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function create(Request $request)
    {
        try {
            $category = new Category();
            $category->name = $request->name;
            $category->parent = -1;
            $category->had_child = -1;
            $category->save();
            return response()->json(['category'=>$category], 201);
        } catch (\Throwable $e) {
            Log::error('Creating Category : ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function get($id)
    {
        try{
            if ($id === 'all')
                return Category::all();
            else return Category::find($id);
        } catch (\Throwable $e) {
            Log::error('Get Category : ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }

    }

    public function getSubCategory($id)
    {
        try {
            $category = Category::where('parent', $id)->get();
            return $category;
        } catch (\Throwable $e) {
            Log::error('Get Category : ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
