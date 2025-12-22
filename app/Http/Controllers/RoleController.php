<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Lấy tất cả vai trò
    public function index()
    {
        return Role::all();
    }

    // Tạo vai trò mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $role = Role::create($validated);
        return response()->json($role, 201);
    }

    // Lấy vai trò theo ID
    public function show($id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    // Cập nhật vai trò
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
        ]);

        $role->update($validated);
        return response()->json($role);
    }

    // Xóa vai trò
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json(null, 204);
    }
}
