<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Brand;

class BrandController extends Controller
{
    //
    public function create(Request $request)
    {
        try {
            $brand = new Brand();
            $brand->user_id = Auth::user()->id;
            $brand->name = $request->name;
            $brand->category_id = $request->category_id;
            $brand->parent_id = $request->parent_id;
            $brand->country = $request->country;
            $brand->currency = $request->currency;
            $brand->selling = $request->selling;
            $brand->comments = $request->comments;
            $brand->save();
            return $brand;
        } catch (\Throwable $e) {
            Log::error('Creating brand : ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function get($id)
	{
		try {
			if ($id == 'all')
				return Brand::with('category')->where('user_id', Auth::user()->id)->get();
			else return Brand::with('category')->where('user_id', Auth::user()->id)->where('id', $id)->first();
		} catch (\Throwable $e) {
			Log::error('Get Brand (' . $id . ') : ' . $e->getMessage());
			return 'Brand not found';
		}
	}

    public function filter(Request $request)
    {
        $category_id = isset($request->filter['category_id']) ? $request->filter['category_id'] : null;
        try {
            $brands = Brand::with(['category'])
            ->when($category_id, function($query) use ($category_id) {
                $query->whereHas('category', function($query) use ($category_id) {
                    $query->where('category_id', $category_id);
                });
            })->get();
            return $brands;
        } catch (\Throwable $e) {
			Log::error('Filter Brand' . $e->getMessage());
			return 'Brand not found';
		}
    }

}
