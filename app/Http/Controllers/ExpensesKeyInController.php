<?php

namespace App\Http\Controllers;

use App\Models\ExpensesKeyIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ExpensesKeyInController extends Controller
{
    public function create(Request $request)
    {
        //
        try {
            $expensesKeyIn = new ExpensesKeyIn();
            $expensesKeyIn->user_id = Auth::user()->id;
            $expensesKeyIn->brand_id = $request->brand_id;
            $expensesKeyIn->date = $request->date;
            $expensesKeyIn->sum = $request->sum;
            $expensesKeyIn->comments = $request->comments;
            $expensesKeyIn->currency = $request->currency;
            $expensesKeyIn->country = $request->country;
            $expensesKeyIn->expenses_type_id = $request->expenses_type_id;
            $expensesKeyIn->save();
            return $expensesKeyIn;
        } catch (\Throwable $e) {
            Log::error('Creating ExpensesKeyIn : ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function filter(Request $request)
    {
        $type = $request->filter['type'];
        $brand_id = isset($request->filter['brand_id']) ? $request->filter['brand_id'] : null;
        $payment_method = isset($request->filter['payment_method']) ?  $request->filter['payment_method'] : null;
        $expenses_type = isset($request->filter['expenses_type']) ?  $request->filter['expenses_type'] : null;
        $country = isset($request->filter['country']) ? $request->filter['country'] : null;
        $status = isset($request->filter['status']) ? $request->filter['status'] : null;

        $date_range = $request->date_range;
        $startDate = $date_range['startDate'];
        $endDate = $date_range['endDate'];

        try {
            $expensesKeyIn = ExpensesKeyIn::with(['brand', 'user', 'expensesType', 'paymentMethods'])
            ->when($type && $type !== 'all', function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($brand_id && $brand_id != 'all', function ($query) use ($brand_id) {
                $query->whereHas('brand', function($query) use ($brand_id) {
                    $query->where('id', $brand_id);
                });
            })
            ->when($country && $country !='all', function ($query) use ($country) {
                $query->where('country', $country);
            })
            ->when($status && $status !='all', function ($query) use ($status) {
                $query->where('received', $status);
            })
            ->when($expenses_type && $expenses_type != 'all', function ($query) use ($expenses_type) {
                $query->whereHas('expenses_type', function($query) use ($expenses_type) {
                    $query->where('id', $expenses_type);
                });
            })
            ->when(($startDate && $endDate), function ($query) use ($startDate, $endDate) {
                $query->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate);
            })
            ->get();

            if ($payment_method && $payment_method != 'all') {
                $newExpensesKeyIn = [];
                foreach($expensesKeyIn as $key=>$value) {
                    $paymentIds = array_map(
                        function($o) { return $o->id;},
                        json_decode($value)->payment_methods
                    );
                    if (in_array($payment_method, $paymentIds)) {
                        $newExpensesKeyIn[$key] = $value;
                    }
                }
                return $newExpensesKeyIn;
            } else
                return $expensesKeyIn;

            return $expensesKeyIn;
        } catch (\Throwable $e) {
            Log::error('Filter KeyIn : ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function get(Request $request, $id)
    {
        try {
            if ($id ==='all') {
                if (Auth::user()->user_type === 'Admin')
                    $expenses = ExpensesKeyIn::where('user_id', Auth::user()->id)->with(['paymentMethods', 'user', 'brand', 'expensesType'])->get();
                else
                    $expenses = ExpensesKeyIn::with(['paymentMethods', 'user', 'brand', 'expensesType'])->get();
            } else{
                $expenses = ExpensesKeyIn::where('id', $id)->with(['paymentMethods', 'user', 'brand', 'expensesType'])->get();
            }
            return $expenses;
        } catch(\Throwable $e) {
            Log::error('Get Expenses KeyIn : ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

}
