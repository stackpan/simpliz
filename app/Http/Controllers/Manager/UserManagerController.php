<?php

namespace App\Http\Controllers\Manager;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Facades\UserService;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Manager\UserCreateRequest;
use App\Http\Requests\Manager\UserUpdateRequest;

class UserManagerController extends Controller
{

    public function index(): View
    {
        return view('manager.user')
            ->with([
                'users' => UserService::getExamineers(),
            ]);
    }

    public function create(): View
    {
        return view('manager.user.editor')
            ->with([
                'meta' => [
                    'title' => 'Create',
                ],
                'href' => [
                    'store' => route('manager.users.store'),
                ]
            ]);
    }

    public function store(UserCreateRequest $request): RedirectResponse
    {
        $userId = UserService::create($request->validated());

        return redirect()->route('manager.users.edit', $userId);
    }

    public function edit(string $id): View
    {
        $user = User::find($id);

        return view('manager.user.editor')
            ->with([
                'user' => $user,
                'meta' => [
                    'title' => 'Editor',
                ],
                'href' => [
                    'store' => route('manager.users.update', $user->id),
                ],
            ]);
    }

    public function update(UserUpdateRequest $request, string $id): RedirectResponse
    {
        $user = User::find($id);

        UserService::update($user, $request->validated());

        return redirect()->back();
    }

    public function destroy(string $id): RedirectResponse
    {
        UserService::delete($id);

        return redirect()->back();
    }
}
