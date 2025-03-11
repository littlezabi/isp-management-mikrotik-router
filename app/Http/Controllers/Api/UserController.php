<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user || !$user->is_admin)
            return response()->json(['message' => 'Unauthorized.'], 403);
        return response()->json(User::where('is_admin', 0)->get());
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->is_admin)
            return response()->json(['message' => 'Unauthorized.'], 403);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = auth()->user();
        if (!$user || !$user->is_admin)
            return response()->json(['message' => 'Unauthorized.'], 403);
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user || !$user->is_admin)
            return response()->json(['message' => 'Unauthorized.'], 403);
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $data = $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'email'    => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'sometimes|required|string|min:8',
        ]);

        if (isset($data['name'])) {
            $user->name = $data['name'];
        }
        if (isset($data['email'])) {
            $user->email = $data['email'];
        }
        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if (!$user || !$user->is_admin)
            return response()->json(['message' => 'Unauthorized.'], 403);
        $user = User::find($id);
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
