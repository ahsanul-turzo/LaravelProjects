<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function discover(Request $request)
    {
        $search = $request->input('search');

        $query = User::query()
            ->where('id', '!=', auth()->id());

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        } else {
            // Suggest users: people the current user doesn't follow yet
            $followingIds = auth()->user()->following()->pluck('following_id')->toArray();
            $query->whereNotIn('id', $followingIds)
                  ->inRandomOrder();
        }

        $users = $query->paginate(20);

        return view('users.discover', compact('users'));
    }
}
