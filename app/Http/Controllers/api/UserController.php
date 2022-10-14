<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    public function login(Request $request) {

        $request->validate([
            'token' => 'required'
        ]);

        try {
            $sUser = Socialite::driver('azure')->userFromToken($request->token);
            $user = User::updateOrCreate(['email' => $sUser['userPrincipalName']], [
                'nombres' => $sUser['givenName'],
                'apellidos' => $sUser['surname'],
                'azure_id' => $sUser['id'],
                'password' => Hash::make($sUser['id'])
            ]);
            //Auth::login($user, true);

            $token = $user->createToken('marketing-token');
            $user->load('proyecto');
            $user->token = $token->plainTextToken;

            return response()->json($user);

        } catch(\Exception $ex) {
            return response([
                'message' => 'Invalid User'
            ], 401);
        }

    }

    public function currentUser() {
        return auth('sanctum')->user();
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response([
            'message' => 'Usuario deslogueado correctamente'
        ]);
    }
}
