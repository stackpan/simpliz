<?php

namespace App\Http\Controllers\Auth;

use App\Data\RegisterUserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreRegisteredUserRequest;
use App\Providers\RouteServiceProvider;
use App\Service\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(StoreRegisteredUserRequest $request): RedirectResponse
    {
        $data = new RegisterUserData(
            name: $request->name,
            email: $request->email,
            password: $request->password
        );

        $user = $this->userService->register($data);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
