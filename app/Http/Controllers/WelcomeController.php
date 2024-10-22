<?php
namespace App\Http\Controllers;

class WelcomeController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Selamat Datang',
            'list' =>
            [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'Welcome', 'url' => url('/Welcome')],
            ]
            
        ];

        $activeMenu = 'dashboard';

        return view('welcome', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }
}