<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index()
    {
        return Manager::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email|unique:managers',
            'phone' => 'nullable|string',
        ]);

        return Manager::create($data);
    }

    public function show($id)
    {
        return Manager::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $manager = Manager::findOrFail($id);
        $data = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'nullable|email|unique:managers,email,' . $id,
            'phone' => 'nullable|string',
        ]);

        $manager->update($data);
        return $manager;
    }

    public function destroy($id)
    {
        Manager::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
