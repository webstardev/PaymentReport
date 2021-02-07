<?php

namespace App\Http\Controllers;

use App\Models\KeyIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class KeyInController extends Controller
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
            $keyIn = new KeyIn();
            $keyIn->user_id = Auth::user()->id;
            $keyIn->type = $request->type;
            $keyIn->brand_id = $request->brand_id;
            $keyIn->date = $request->date;
            $keyIn->sum = $request->sum;
            $keyIn->payment_method = json_encode($request->payment_method);
            $keyIn->received = $request->received;
            $keyIn->comments = $request->comments;
            $keyIn->currency = $request->currency;
            $keyIn->country = $request->country;
            $keyIn->expenses_type = $request->expenses_type;
            $keyIn->save();
            return $keyIn;
        } catch (\Throwable $e) {
            Log::error('Creating KeyIn : ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function filter(Request $request)
    {
        $type = $request->filter['type'];
        $brand_id = $request->filter['brand_id'];
        $date_range = $request->date_range;
        $startDate = $date_range['startDate'];
        $endDate = $date_range['endDate'];
        // $super_master = $request->$date_range['supermaster'];
        // $master = $request->$date_range['master'];
        // $agent = $request->$date_range['agent'];
        // $api = $request->$date_range['api'];
        // $whitelabel = $request->$date_range['whitelabel'];
        $expenses_type = $request->filter['expenses_type'];
        $status = $request->filter['status'];

        try {
            $keyIn = KeyIn::with(['brand', 'user'])
            ->when($type && $type !== 'all', function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($brand_id && $brand_id != 'all', function ($query) use ($brand_id) {
                $query->whereHas('brand', function($query) use ($brand_id) {
                    $query->where('id', $brand_id);
                });
            })
            // ->when($super_master && $super_master != 'all', function ($query) use ($super_master) {
            //     $query->whereHas('brand', function($query) use ($super_master) {
            //         $query->where('super_master', $super_master);
            //     });
            // })
            // ->when($master && $master != 'all', function ($query) use ($master) {
            //     $query->whereHas('brand', function($query) use ($master) {
            //         $query->where('master', $master);
            //     });
            // })
            // ->when($agent && $agent != 'all', function ($query) use ($agent) {
            //     $query->whereHas('brand', function($query) use ($agent) {
            //         $query->where('master', $agent);
            //     });
            // })
            // ->when($api && $api != 'all', function ($query) use ($api) {
            //     $query->whereHas('brand', function($query) use ($api) {
            //         $query->where('api', $api);
            //     });
            // })
            // ->when($whitelabel && $whitelabel != 'all', function ($query) use ($whitelabel) {
            //     $query->whereHas('brand', function($query) use ($whitelabel) {
            //         $query->where('whitelabel', $whitelabel);
            //     });
            // })
            // ->when($payment_method, function ($query) use ($date_range) {
            //     $query->where('date', 'between', [$date_range->startDate, $date_range->endDate]);
            // })
            ->when($status && $status !='all', function ($query) use ($status) {
                $query->where('received', $status);
            })
            ->when($expenses_type && $expenses_type != 'all', function ($query) use ($expenses_type) {
                $query->where('expenses_type', $expenses_type);
            })
            ->when(($startDate && $endDate), function ($query) use ($startDate, $endDate) {
                $query->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate);
            })
            ->get();
            return $keyIn;
        } catch (\Throwable $e) {
            Log::error('Filter KeyIn : ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KeyIn  $keyIn
     * @return \Illuminate\Http\Response
     */
    public function show(KeyIn $keyIn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KeyIn  $keyIn
     * @return \Illuminate\Http\Response
     */
    public function edit(KeyIn $keyIn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KeyIn  $keyIn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KeyIn $keyIn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KeyIn  $keyIn
     * @return \Illuminate\Http\Response
     */
    public function destroy(KeyIn $keyIn)
    {
        //
    }
}
