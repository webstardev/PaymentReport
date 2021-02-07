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
