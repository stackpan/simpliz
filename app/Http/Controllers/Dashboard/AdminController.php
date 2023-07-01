<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\AdminCreateRequest;
use App\Http\Requests\Admin\AdminUpdateRequest;
use App\Models\User;
use \App\Enums\Role;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::select('id', 'name', 'email')
            ->where('role', Role::Admin->value)
            ->get()
            ->toArray();

        return view('dashboard.admin.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.admin.editor'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminCreateRequest $request)
    {
        $validated = $request->validated();
        $admin = array_merge($validated, ['role' => Role::Admin->value]);

        User::create($admin);

        return redirect()->route('dashboard.admin');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = $this->getUserById($id);

        return view('dashboard.admin.editor')
            ->with('user', $user); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminUpdateRequest $request, string $id)
    {
        $user = $this->getUserById($id);

        $validated = $request->validated();

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->back()->with(['status' => 'Admin updated!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function getUserById(string $id)
    {
        return User::select('id', 'name', 'email', 'gender')
            ->where('id', $id)
            ->first();
    }
}
