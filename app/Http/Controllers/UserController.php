<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    /**
     * Fungsi untuk mendaftarkan pengguna baru.
     *
     * @param UserRegisterRequest $request Request validasi untuk data pendaftaran pengguna.
     * @return JsonResponse Respons JSON dengan status 201 (Created).
     */
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        // existing in database?
        if (User::where('username', $data['username'])->count() == 1) {
            throw new HttpResponseException(response([
                "errors" => [
                    "username" => [
                        "Username already registered"
                    ]
                ]
            ], 400));
        }

        $user = new User($data);

        // back crypt password
        $user->password = Hash::make($data['password']);
        $user->save();

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    /**
     * Fungsi untuk melakukan login pengguna.
     *
     * @param UserLoginRequest $request Request validasi untuk data login pengguna.
     * @return UserResource Sumber daya pengguna setelah berhasil login.
     */
    public function login(UserLoginRequest $request): UserResource
    {
        $data = $request->validated();

        $user = User::where('username', $data['username'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        "username or password wrong"
                    ]
                ]
            ], 401));
        }

        $user->token = \Illuminate\Support\Str::uuid()->toString();
        $user->save();

        return new UserResource($user);
    }

    /**
     * Fungsi untuk mendapatkan informasi pengguna terautentikasi.
     *
     * @return UserResource Sumber daya pengguna terautentikasi.
     */
    public function get(): UserResource
    {
        $user = Auth::user();
        Log::info($user);
        return new UserResource($user);
    }

    /**
     * Fungsi untuk memperbarui informasi pengguna terautentikasi.
     *
     * @param UserUpdateRequest $request Request validasi untuk data pembaruan pengguna.
     * @return UserResource Sumber daya pengguna setelah berhasil diperbarui.
     */
    public function update(UserUpdateRequest $request): UserResource
    {
        $data = $request->validated();

        $user = Auth::user();
        $user = User::where('username', $user->username)->first();

        if (isset($data['name'])) {
            $user->name = $data['name'];
        }

        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return new UserResource($user);
    }

    /**
     * Fungsi untuk melakukan logout pengguna.
     *
     * @return JsonResponse Respons JSON dengan status 200 (OK) setelah berhasil logout.
     */
    public function logout()
    {
        $user = Auth::user();
        $user = User::where('username', $user->username)->first();

        $user->token = null;
        $user->save();

        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }
}
