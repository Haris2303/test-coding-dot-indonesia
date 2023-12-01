<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Mengambil token dari header Authorization
        $token = $request->header('Authorization');
        $authenticate = true;

        // Jika token tidak ada, set autentikasi menjadi false
        if (!$token) {
            $authenticate = false;
        }

        $user = User::where('token', $token)->first();

        // Jika pengguna tidak ditemukan, set autentikasi menjadi false
        if (!$user) {
            $authenticate = false;
        } else {
            Auth::login($user);
        }

        if ($authenticate) {
            return $next($request);
        } else {
            // Jika autentikasi gagal, kembalikan respons JSON dengan status 401 (Unauthorized)
            return response()->json([
                'errors' => [
                    'message' => [
                        'unauthorized'
                    ]
                ]
            ])->setStatusCode(401);
        }
    }
}
