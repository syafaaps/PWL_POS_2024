<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){

        //JS 4 Prak 2.1 no 6
        // $user = UserModel::firstwhere('level_id', 1);

        //JS 4 Prak 2.1 no 8
        /*$user = UserModel::findOr(20, ['username', 'nama'], function(){
            abort(404);
        });*/

        //JS 4 Prak 2.2 no 1
        // $user = UserModel::findOrFail(1);

        //JS 4 Prak 2.2 no 3
       // $user = UserModel::where('username', 'manager9')->firstOrFail();
    
        // JS 4 Prak 2.3 no 1
        // $user = UserModel::where('level_id', 2)->count();
        // dd($user);

        // JS 4 Prak 2.3 no 3
       // $user = UserModel::where('level_id', 2)->count();

        //JS 4 Prak 2.4 no 6
    //    $user = UserModel::firstOrCreate(
    //     [
    //         'username'=> 'manager',
    //         'nama' => 'Manager',
    //     ],);

       // JS 4 Prak 2.4 no1-4
    //    $user = UserModel::firstOrCreate(
    //     [
    //         'username'=> 'manager22',
    //         'nama' => 'Manager Dua Dua',
    //         'password' => Hash::make('12345'),
    //         'level_id' => 2
    //     ],
    // );
    
    //     //JS 4 Prak 2.4 no 6
    //    $user = UserModel::firstOrNew(
    //     [
    //         'username'=> 'manager',
    //         'nama' => 'Manager',
    //     ],);

        //JS 4 Prak 2.4 no 8
       $user = UserModel::firstOrNew(
        [
            'username'=> 'manager33',
            'nama' => 'Manager Tiga Tiga',
            'password' => Hash::make('12345'),
            'level_id' => 2
        ],);
        $user->save();
    return view('user', ['data' => $user]);

        

        //JS 4 Prak 1
        /*
       $data = [
        'level_id' => 2,
        'username' => 'manager_tiga',
        'nama' => 'Manager 3',
        'password' => Hash::make('12345')
       ];
       UserModel::create($data);
        */
      
      //coba akses model UserModel
        //$user = UserModel::all(); //Ambil semua data dari tabel m_user
        // return view('user', ['data' => $user]);

         //JS 3
                // tambah data user dengan Eloquent Model
        /*$data = [
            'username' => 'customer-1',
            'nama'  => 'Pelanggan',
            'password' => Hash::make('12345'),
            'level_id' => 4
        ];
        UserModel::insert($data); //tambahkan data ke tabel m_user
        */

        /*$data = [
            'nama'  => 'Pelanggan Pertama',
        ];
        UserModel::where('username', 'customer-1')->update($data); //update data user
       */
         
    }
}