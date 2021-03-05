<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Agent;

class AgentController extends Controller
{
    //
    public function create(Request $request)
    {
        try {
            $agent = new Agent();
            $agent->user_id = Auth::user()->id;
            $agent->name = $request->name;
            $agent->brand_id = $request->brand_id;
            $agent->category_id = $request->category_id;
            $agent->country = $request->country;
            $agent->currency = $request->currency;
            $agent->selling = $request->selling;
            $agent->comments = $request->comments;
            $agent->save();
            return $agent;
        } catch (\Throwable $e) {
            Log::error('Creating agent : ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function get($id)
	{
		try {
			if ($id == 'all')
				return Agent::with(['brand', 'category'])->where('user_id', Auth::user()->id)->get();
			else return Agent::with(['brand', 'category'])->where('user_id', Auth::user()->id)->where('id', $id)->first();
		} catch (\Throwable $e) {
			Log::error('Get Agent (' . $id . ') : ' . $e->getMessage());
			return 'Agent not found';
		}
	}

    public function filter(Request $request)
    {
        $category_id = isset($request->filter['category_id']) ? $request->filter['category_id'] : null;
        try {
            $agents = Agent::with(['brand', 'category'])
            ->when($category_id, function($query) use ($category_id) {
                $query->whereHas('category', function($query) use ($category_id) {
                    $query->where('category_id', $category_id);
                });
            });
            return $agents;
        } catch (\Throwable $e) {
			Log::error('Filter Agent' . $e->getMessage());
			return 'Agent not found';
		}
    }

}
