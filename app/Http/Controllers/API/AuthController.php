<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email'     => 'required|email:rfc,dns',
                'password'  => 'required|string'
            ],
            [
                'email.required' => 'Lütfen e-mail alanını doldurunuz.',
                'email.email' => 'Lütfen geçerli bir e-mail hesabı giriniz.',
                'password.required'  => 'Lütfen şifre alanını doldurunuz.'
            ]


        );
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }


        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json( 
                 'E-mail veya şifre hatalı.'
             , 400);
        }


        $user   = User::where('email', $request->email)->firstOrFail();
        $token  = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'success',
            'token'  => $token,
            'name'  => $user->name,
            'id'  => $user->id,
            'country_code'  => $user->country_code,
            'phone'  => $user->phone,
            'type'  => $user->type,
            'email'  => $user->email,
            'status'  => "true",
            'token_type'    => 'Bearer'
        ]);
    }


    public function logout()
    {
        // Get the authenticated user
        $user = auth()->user();
        // revoke the users token     
        $user->tokens()->delete();

        return response()->json([
            "message" => "Logged out successfully"
        ], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|max:255|unique:users',
            'password'  => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'data'          => $user,
            'token'  => $token,
            'token_type'    => 'Bearer'
        ]);
    }
    public function updateUser(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'name'      => 'required|string|max:255',
        //     'email'     => 'required|string|max:255|unique:users',
        //     'password'  => 'required|string'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors());
        // }


        $user = User::where('id', $request->user()->id)->first();

        $user->fill([
            'name' => $request->name,
            'phone' => $request->phone,
            'country_code' => $request->country_code,

        ]);
        $user->save();


        // $user = User::create([
        //     'name'      => $request->name,
        //     'email'     => $request->email,
        //     'password'  => Hash::make($request->password)
        // ]);

        // $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([


            'name'  => $user->name,
            'id'  => $user->id,
            'country_code'  => $user->country_code,
            'phone'  => $user->phone,

            'email'  => $user->email,


        ]);
    }

    public function updatePassword(Request $request)
    {

        if (!Hash::check($request->oldPassword, auth()->user()->password)) {
            return response()->json("Girilen şifre doğru değildir.");
        }
        if ($request->password != $request->rePassword) {
            return response()->json("Şifreler uyuşmamaktadır.");
        }

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json("success");
    }
}
