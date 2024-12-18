<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
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

    public function editUser(Request $request, int $id) {

         try {
            $request->validate([
                'name'      => 'min:4',
                'surname'   => 'min:4',
                'email'     => 'string|email|max:255|unique:users',
                'password'  => 'min:3',
                'role'      => 'string|in:admin,user'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'response_code' => 422,
                'status'        => 'error',
                'message'       => 'Validation failed',
                'errors'        => $e->errors()
            ], 422);
        }

        $userToEdit = User::find($id);
        $userToEdit->name = ($request->name == "") ? $userToEdit->name : $request->name;
        $userToEdit->surname = ($request->surname == "") ? $userToEdit->surname : $request->surname;
        $userToEdit->email = ($request->email == "") ? $userToEdit->email : $request->email;
        $userToEdit->password = ($request->password == "") ? $userToEdit->password : Hash::make($request->password);
        if (!$request->role == "") $userToEdit->assignRole($request->role);

        $userToEdit->save();

        $data = [];
        $data['response_code']  = '200';
        $data['status']         = 'success';
        $data['message']        = 'User data modification successful';
        return response()->json($data);
    }

    public function searchUser(Request $request) {

        $users = User::
        where('name', 'like', '%' . $request->name . '%')
        ->where('surname', 'like', '%' . $request->surname . '%')
        ->where('email', 'like', '%' . $request->email . '%')
        ->get();

        if (count($users) == 0) {
            $message = "There is no user matching the search";
        } else {
            $message = "User search successful";
        }

        $data['response_code']  = '200';
        $data['status']         = 'success';
        $data['message']        = $message;
        $data['users_list']     = $users;
        return response()->json($data);
        
/*
        return view('players.index', ['players' => $players]);

        $resultado = [];

            foreach ($listaTareas as $dato) {
                $j = 0;
                if ($busqueda["id"] == "" || $busqueda["id"] == $dato["id"]) {
                    $j ++;
                }
                if ($busqueda["tarea"] == "" || str_contains(self::arreglar($dato["tarea"]), self::arreglar($busqueda["tarea"]))) {
                    $j ++;
                }
                if ($busqueda["responsable"] == "" ||
                    str_contains(self::arreglar($dato["responsable"]), self::arreglar($busqueda["responsable"]))) {
                    $j ++;
                }
                if ($busqueda["estado"] == "" || $busqueda["estado"] == $dato["estado"]) {
                    $j ++;
                }
                if ($busqueda["inicio"] == "" || $busqueda["inicio"] == $dato["inicio"]) {
                    $j ++;
                }
                if ($busqueda["fin"] == "" || $busqueda["fin"] == $dato["fin"]) {
                    $j ++;
                }
            
                if ($j == 6) {
                    $resultado [] = $dato;
                }   
            }    */ 
    }

}
