<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function register(Request $request) {        

        $request->validate([
            'name'      => 'required|min:4',
            'surname'   => 'required|min:4',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|min:3',
            'role'      => 'required|string|in:admin,user'
        ]);


        $user = new User();
        $user->name         = $request->name ;
        $user->surname      = $request->surname ;
        $user->email        = $request->email;
        $user->password     = Hash::make($request->password);
        $user->assignRole($request->role);

        $user->save();

        $data = [];
        $data['response_code']  = '200';
        $data['status']         = 'success';
        $data['message']        = 'Registration successful';
        return response()->json($data);

    }

}
