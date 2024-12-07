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
                
                $response = [];
                $response['response_code'] = '200';
                $response['message']       = 'Success Login';
                $response['user_info']     = $user;
                $response['token']         = $accessToken;
                return response()->json($response);
            } else {
                $response = [];
                $response['response_code']  = '401';
                $response['message']        = 'Unauthorized';
                return response()->json($response);
            }
    
    } 

    public function logout (Request $request) {
        $request->user()->token()->revoke();

        $response = [];
        $response['response_code']  = '200';
        $response['message']        = 'Success logout';
        return response()->json($response);

    }

}


