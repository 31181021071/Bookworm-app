<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $message=[
            'email.email'=>"Lỗi Email",
            'email.required'=>"Nhập Email",
            'password.required'=>"Nhập mật khẩu",
        ];

        $validate = Validator::make($request->all(),[
            'email'=>'email|required',
            'password'=>'required',
        ],$message);

        if ($validate->fails()){
            return response()->json(
                [
                    'message'=>$validate->errors()
                ],
                404
            );
        }

        User::create([
            'first_name'=> $request->first_name,
            'last_name'=> $request->last_name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);

        return response()->json(
            [
                'message'=>"Created!"
            ],
            200
        );
    }

    public function login(Request $request)
    {
        $user = User::where('email',$request->email)->first();

        if (!$user || !Hash::check($request->password,$user->password,[])){
            return response()->json(
                [
                    'message'=>"tài khoản không tồn tại"
                ],
                404
            );
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(
            [
                'access_token'=>$token,
                'type_token'=>'Bearer'
            ],
            404
        );
    }

    public function getUser(Request $request){
        return $request->user();
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return "Logout";
    }
}
