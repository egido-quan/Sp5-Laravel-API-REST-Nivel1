<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegistrationController extends Controller
{
    public function register(Request $request) {        
        try {
            $request->validate([
                'name'      => 'required|min:4',
                'surname'   => 'required|min:4',
                'email'     => 'required|string|email|max:255|unique:users',
                'password'  => 'required|min:3',
                'role'      => 'required|string|in:admin,user'
            ]);
    } catch (ValidationException $e) {
        return response()->json([
            'response_code' => 422,
            'status'        => 'error',
            'message'       => 'Validation failed',
            'errors'        => $e->errors()
        ], 422);
    }


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

    public function delete(Request $request)
    {
        $email = $request->email;

        $user = User::where('email', $email)->first();

        if ($user ) {               

            $data = [];
            $data['response_code'] = '200';
            $data['status']        = 'success';
            $data['message']       = 'User deleted';
            $data['user_info']     = $user;

            $user->delete();
            return response()->json($data);
        } else {
            $data = [];
            $data['response_code']  = '404';
            $data['status']         = 'error';
            $data['message']        = 'Unknown user';
            return response()->json($data);            
        }
    }

}
