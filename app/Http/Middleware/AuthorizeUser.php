<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AuthorizeUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ... $roles): Response
    {
        $user_role = $request->user()->getRole(); //ambil data user yg login
                                  //fungsi user() diambil dari UserModel.php
        if (in_array($user_role, $roles)) {  //cek apakah user memiliki role nya
        return $next($request);
    }
    //jika tidak punya role, tampilkan eror 403
    abort(403, 'Forbidden. Kamu tidak punya akses ke halaman ini');
}

    public function register(Request $request): Response
    {
        // Validasi input untuk registrasi
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        // Simpan user baru
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ]);
        // Redirect atau respons sukses
        return response()->json(['message' => 'Registration successful.'], 201);
    }
}
