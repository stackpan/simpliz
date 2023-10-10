<?php

namespace App\Http\Controllers\Manager;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Facades\UserService;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\Manager\UserCreateRequest;

class UserEditorController extends Controller
{

    public function create(): View
    {
        return view('manager.user.editor')
            ->with([
                'meta' => [
                    'title' => 'Create',
                ],
                'href' => [
                    'store' => route('manager.user.store'),
                ]
            ]);
    }

    public function edit(User $user): View
    {
        return view('manager.user.editor')
            ->with([
                'user' => $user,
                'meta' => [
                    'title' => 'Editor',
                ],
                'href' => [
                    'store' => route('manager.user.update', $user->id),
                ],
            ]);
    }

    public function store(UserCreateRequest $request): RedirectResponse
    {
        $userId = UserService::create($request->validated());

        return redirect()->route('manager.user.editor', $userId);
    }

    public function update(User $user, UserUpdateRequest $request): RedirectResponse
    {
        UserService::update($user, $request->validated());

        return redirect()->back();
    }
}
