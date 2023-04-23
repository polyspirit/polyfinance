<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        Auth::setDefaultDriver('web');
    }

    // API

    public function register(Request $request)
    {
        if ($this->getUser()->hasPermissionTo('register users')) {

            // $userRoles = Role::whereNotIn('name', ['superadmin'])->get()->pluck('name')->toArray();
            // $userRolesRule = 'in:' . implode(',', $userRoles);

            $attr = $request->validate([
                'email' => 'required|string|email|unique:users,email|max:255',
                'password' => 'required|string|min:6|confirmed',
                'name' => 'string|max:255',
                // 'role' => $userRolesRule
            ]);

            $attr['name'] = $attr['name'] ?? explode('@', $attr['email'])[0];

            $user = User::create([
                'name' => $attr['name'],
                'password' => bcrypt($attr['password']),
                'email' => $attr['email']
            ]);

            $role = $request->input('role') ?? 'user';
            $user->assignRole($role);

            return $this->success(['id' => $user->id], 'User was registered');
        } else {
            return $this->forbiddenError('You can not register users');
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
        } else {
            return $this->error('Credentials not match', 401);
        }

        $token = $this->getUser()->createToken('API Token')->plainTextToken;
        $tokenData = explode('|', $token);
        $this->setCookie('token_id', $tokenData[0]);

        return $this->success([
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
        } catch (\Throwable $th) {
            $tokenId = $request->cookie('token_id');
            if ($tokenId > 0) {
                $request->user()->tokens()->where('id', $tokenId)->delete();
            } else {
                $request->user()->tokens()->delete();
            }
        }

        Auth::guard('web')->logout();
        $this->setCookie('token_id', 0);

        return $this->success($request->cookie('token_id'), 'Tokens Revoked');
    }

    public function test(Request $request)
    {
        if ($request->cookie('token_id')) {
            return $this->success(['token' => $request->cookie('token_id')], 'Test');
        } else {
            return $this->success([
                'message' => 'Test done'
            ]);
        }
    }

    public function user(Request $request)
    {
        $user = $this->getUser();
        $this->addAppends($request, $user);

        return $this->success($user);
    }


    // PRIVATE

    private function getUser(): User
    {
        $user = auth()->user();
        if (empty($user)) {
            $this->login(request());
            $user = auth()->user();
        }

        return $user;
    }
}
