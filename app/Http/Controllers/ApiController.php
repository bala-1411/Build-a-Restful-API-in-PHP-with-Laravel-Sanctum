<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\models\User;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function register(Request $request){
       $validator = Validator::make($request->all(),
        [
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
            'c_password'=>'required|same:password'
        ]
        );
        if ($validator->Fails()) {
            return response()->json(['message'=>'validator error'],401);
        }

        $data = $request->all();
        $data['password'] = Hash::make( $data['password']);
        $user = User::create($data);

        $response['token'] = $user->createToken('Myapp');
        $response['name'] = $user->name;
        return response()->json($response,200);

        
    }

    public function login(Request $request){
        if(Auth::attempt(['email'=>$request->input('email'),'password'=>$request->input('password')] )) 
        {
                $user = Auth::user();
                $response['token'] = $user->createToken('Myapp');
                $response['name'] = $user->name;
                return response()->json($response,200);

        }else{
            return response()->json(['message'=>'Invalid credentials error'],401);
        }
        
    }

    public function detail(){
        $user = Auth::user();
        $response['user'] = $user;  
        return response()->json($response,200);      
    }
}
