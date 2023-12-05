<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function index(): View
    {

        $data = [
            'title' => 'Login Page',
            'data' => ''
        ];

        return view('login', $data);
    }

    public function login(Request $request): RedirectResponse
    {
        $reqData = [
            'username' => $request->post('username'),
            'password' => $request->post('password')
        ];

        $response = Http::post(env('APP_API') . '/api/users/login', $reqData);

        $data = [
            'title' => 'Login Page',
            'data' => json_decode($response, true)
        ];

        if ($response->status() != 200) {
            return redirect()->to('login')->withErrors($data['data']['errors']);
        }

        return redirect()->to('book')->withHeaders([
            'Authorization' => $data['data']['data']['token']
        ]);
    }
}
