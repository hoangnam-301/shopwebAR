<?php

namespace App\Http\Controllers;

use App\Models\ARSession;
use Illuminate\Http\Request;

class ARSessionController extends Controller
{
    public function index()
    {
        return ARSession::with('ar_model')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ar_model_id' => 'required|exists:ar_models,id',
            'user_id' => 'required|exists:users,id',
            'duration' => 'nullable|integer'
        ]);

        $session = ARSession::create($validated);
        return response()->json($session, 201);
    }
}
