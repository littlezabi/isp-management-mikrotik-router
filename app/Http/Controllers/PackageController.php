<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PackageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user || !$user->is_admin)
            return response()->json(['message' => 'Unauthorized.'], 403);

        $packages = Package::all();
        return response()->json($packages, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->is_admin)
            return response()->json(['message' => 'Unauthorized.'], 403);


        $validatedData = $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',
            'price'           => 'required|numeric',
            'bandwidth_limit' => 'required|numeric',
        ]);

        $package = Package::create($validatedData);

        return response()->json([
            'message' => 'Package created successfully',
            'package' => $package,
        ], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $user = auth()->user();
        if (!$user || !$user->is_admin)
            return response()->json(['message' => 'Unauthorized.'], 403);
        $package = Package::findOrFail($id);
        return response()->json($package, Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user || !$user->is_admin)
            return response()->json(['message' => 'Unauthorized.'], 403);
        $package = Package::findOrFail($id);
        $validatedData = $request->validate([
            'name'            => 'sometimes|required|string|max:255',
            'description'     => 'sometimes|nullable|string',
            'price'           => 'sometimes|required|numeric',
            'bandwidth_limit' => 'sometimes|required|numeric',
        ]);
        $package->update($validatedData);

        return response()->json([
            'message' => 'Package updated successfully',
            'package' => $package,
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if (!$user || !$user->is_admin)
            return response()->json(['message' => 'Unauthorized.'], 403);
        $package = Package::findOrFail($id);
        $package->delete();

        return response()->json([
            'message' => 'Package deleted successfully'
        ], Response::HTTP_OK);
    }
}
