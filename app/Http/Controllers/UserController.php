<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'admin') abort(403);
        return view('users.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,engineer',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);
        
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show($id)
    {
        abort(404);
    }

    public function edit(User $user)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,engineer',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('settings.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);
        return back()->with('success', 'Profile updated successfully.');
    }
}
