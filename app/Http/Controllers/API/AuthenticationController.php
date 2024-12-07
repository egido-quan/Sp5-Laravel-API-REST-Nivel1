<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {

        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

            $email     = $request->email;
            $password  = $request->password;

            $user = User::where('email', $email)->first();

            if ($user && Hash::check($password, $user->password))
            {
                $accessToken = $user->createToken($user->email)->accessToken;
                
                $data = [];
                $data['response_code'] = '200';
                $data['message']       = 'Success Login';
                $data['user_info']     = $user;
                $data['token']         = $accessToken;
                return response()->json($data);
            } else {
                $data = [];
                $data['response_code']  = '401';
                $data['message']        = 'Unauthorized';
                return response()->json($data);
            }
    
    } 
}

