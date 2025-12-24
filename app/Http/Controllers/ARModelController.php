<?php

namespace App\Http\Controllers;

use App\Models\ARModel;
use Illuminate\Http\Request;

class ARModelController extends Controller
{
    public function index()
    {
        return ARModel::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file_path' => 'required|string'
        ]);

        $model = ARModel::create($validated);
        return response()->json($model, 201);
    }
}
