<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
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
    //    $user = UserModel::firstOrNew(
    //     [
    //         'username'=> 'manager33',
    //         'nama' => 'Manager Tiga Tiga',
    //         'password' => Hash::make('12345'),
    //         'level_id' => 2
    //     ],);
    //     $user->save();
    // return view('user', ['data' => $user]);

    //JS 4 Prak 2.5 no 1
    // $user = UserModel::create([
    //     'username' => 'manager55',
    //     'nama' => 'Manager55',
    //     'password' => Hash::make('12345'),
    //     'level_id' => 2,
    // ]);

    // $user->username = 'manager 56';

    // $user->isDirty(); // true
    // $user->isDirty( 'username'); // true
    // $user->isDirty('nama'); // false
    // $user->isDirty(['nama', 'username']); // true

    // $user->isClean(); // false
    // $user->isClean('username'); // false
    // $user->isClean('nama'); // true
    // $user->isClean(['nama', 'username']); // false

    // $user->save();

    // $user->isDirty(); // false
    // $user->isClean(); // true
    // dd($user->isDirty());

    //JS 4 Prak 2.5 no 3
    // $user = UserModel::create( [
    //     'username' => 'manager 11',
    //     'nama' => 'Manager11',
    //     'password' => Hash:: make('12345'),
    //     'level_id' => 2,
    //     ]);
        
    //     $user->username = 'manager12';
        
    //     $user->save();
        
    //     $user->wasChanged(); // true
    //     $user->wasChanged('username'); // true
    //     $user->wasChanged( ['username', 'level_id']); // true
    //     $user->wasChanged('nama'); // false
    //     dd($user->wasChanged(['nama', 'username'])); // true

    //JS 4 Prak 2.6 no 2 & 6 & 9
    // $user = UserModel::all();
    // return view('user', ['data' => $user]);

    //JS 4 Prak 2.7

        $user = UserModel::with('level')->get();
        //dd($user);
        return view('user', ['data' => $user]);
    }


public function tambah()
    {
        return view('user_tambah');
    }

    public function tambah_simpan(Request $request)
{
    UserModel::create([
        'username' => $request->username,
        'nama' => $request->nama,
        'password' => Hash::make('$request->password'),
        'level_id' => $request->level_id
    ]);
    
    return redirect('/user');
}
public function ubah($id)
    {
        $user = UserModel::find($id);
        return view('user_ubah', ['data' => $user]);
    }

    public function ubah_simpan($id, Request $request){
        $user = UserModel::find($id);

        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->password = Hash::make('$request->password');
        $user->level_id = $request->level_id;

        $user->save();

        return redirect('/user');
    }

    public function hapus($id)
    {
        $user = UserModel::find($id);
        $user->delete();

        return redirect('/user');
    }
}