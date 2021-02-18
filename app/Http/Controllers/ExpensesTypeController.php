<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ExpensesType;

class ExpensesTypeController extends Controller
{
    //
    public function create(Request $request)
    {
        try {
            $expensesType = new ExpensesType();
            $expensesType->name = $request->name;
            $expensesType->save();
            return $expensesType;
        } catch (\Throwable $e) {
            Log::error('Creating Expenses type : ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function get($id)
	{
		try {
			if ($id == 'all')
				return ExpensesType::all();
			else return ExpensesType::find($id);
		} catch (\Throwable $e) {
			Log::error('Expenses type (' . $id . ') : ' . $e->getMessage());
			return 'Expenses type not found';
		}
	}

}
