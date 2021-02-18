<?php

namespace App\Http\Controllers;

use App\Models\IncomeKeyIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class IncomeKeyInController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            $incomeKeyIn = new IncomeKeyIn();
            $incomeKeyIn->user_id = Auth::user()->id;
            $incomeKeyIn->type = $request->type;
            $incomeKeyIn->brand_id = $request->brand_id;
            $incomeKeyIn->date = $request->date;
            $incomeKeyIn->sum = $request->sum;
            $incomeKeyIn->payment_method = $request->payment_method;
            $incomeKeyIn->received = $request->received;
            $incomeKeyIn->comments = $request->comments;
            $incomeKeyIn->save();
            return $incomeKeyIn;
        } catch (\Throwable $e) {
            Log::error('Creating IncomeKeyIn : ' . $e->getMessage());
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
            $incomeKeyIn = IncomeKeyIn::with(['brand', 'user', 'payment_method'])
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
            // ->when($payment_method && $payment_method != 'all', function ($query) use ($payment_method) {
            //     $query->whereHas('payment_method', function($query) use ($payment_method) {
            //         $query->whereIn('id', $payment_method);
            //     });
            // })
            ->when(($startDate && $endDate), function ($query) use ($startDate, $endDate) {
                $query->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate);
            })
            ->get();
            return $incomeKeyIn;
        } catch (\Throwable $e) {
            Log::error('Filter IncomeKeyIn : ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

}
