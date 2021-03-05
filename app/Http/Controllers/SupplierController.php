<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Supplier;

class SupplierController extends Controller
{
    //
    public function create(Request $request)
    {
        try {
            $supplier = new Supplier();
            $supplier->name = $request->name;
            $supplier->save();
            return $supplier;
        } catch (\Throwable $e) {
            Log::error('Creating Supplier : ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function get($id)
	{
		try {
			if ($id == 'all')
				return Supplier::all();
			else return Supplier::find($id);
		} catch (\Throwable $e) {
			Log::error('Supplier (' . $id . ') : ' . $e->getMessage());
			return 'Supplier not found';
		}
	}

}
