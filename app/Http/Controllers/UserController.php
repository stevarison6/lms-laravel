<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            ->when($search, fn($query, $search) =>
                $query->where('name', 'like', "%{$search}%")
            )
            ->orderBy('name')
            ->paginate(5)
            ->withQueryString();

        return Inertia::render('Admin/UserManage', [
            'users' => $users,
            'filters' => $request->only('search'),
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,student,instructor',
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'Le rôle a été mis à jour avec succès!');
    }
}
