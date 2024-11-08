<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = UserModel::all();
        return response()->json($users);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'  => 'required',
            'nama'      => 'required',
            'password'  => 'required',
            'level_id'  => 'required',
            'avatar'    => 'required|image|mimes:jpeg,png,jpg,giv,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = UserModel::create($request->all());
        return response()->json($user, 201);
    }
    public function show(UserModel $user)
    {
        return response()->json($user);
    }
    public function update(Request $request, UserModel $user)
    {
        $user->update($request->all());
        return response()->json($user, 200);
    }
    public function destroy(UserModel $user)
    {
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus'
        ]);
    }
}