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
            $brand->name = $request->brand_name;
            $brand->supermaster = $request->supermaster;
            $brand->master = $request->master;
            $brand->agent = $request->agent;
            $brand->api = $request->api;
            $brand->white_label = $request->white_label;
            $brand->other = $request->other;
            $brand->country = $request->country;
            $brand->currency = $request->currency;
            $brand->selling = $request->selling;
            $brand->comment = $request->comment;
            $brand->save();
            return $brand;
        } catch (\Throwable $e) {
            Log::error('Creating brand : ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
