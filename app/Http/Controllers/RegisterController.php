<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function index(): View
    {
        $data = [
            'title' => 'Register Page',
        ];

        return view('register', $data);
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'username' => ['required', 'min:6'],
            'password' => 'required'
        ]);

        $response = Http::post(env('APP_API') . '/api/users', $request);

        if ($response->status() === 201) {
            return redirect()->to('login')->with('success', 'User berhasil terdaftar');
        } else {
            return redirect()->to('register')->withErrors(json_decode($response, true));
        }
    }
}
