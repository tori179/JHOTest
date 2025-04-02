<?php

namespace App\Http\Controllers;

use App\Models\ListContact;
use Illuminate\Http\Request;

class ListContactController extends Controller
{
    public function index()
    {
        return response()->json(ListContact::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $listContact = ListContact::create($request->all());
        return response()->json($listContact, 201);
    }

    public function show($id)
    {
        return response()->json(ListContact::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $listContact = ListContact::findOrFail($id);
        $listContact->update($request->all());
        return response()->json($listContact);
    }

    public function destroy($id)
    {
        ListContact::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}

