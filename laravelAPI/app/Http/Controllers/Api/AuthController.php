<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function login(Request $request){
        try{
            $bodyHttpRequest = [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => env('CLIENT_ID'),
                    'client_secret' => env('CLIENT_SECRET'),
                    'username' => $request->username,
                    'password' => $request->password,
                    'scope' => ''
                ],
                'exceptions' => false,
            ];
            $url = 'http://laravelapi.test' . '/oauth/token';
            $http = new \GuzzleHttp\Client;
            $response = $http->post($url, $bodyHttpRequest);
            $user = User::where('username', '=', $request->username)->firstOrFail();;
            if($user->blocked == 1){
                return response()->json(
                    ['login' => 'User is blocked'], 401);
            }
            return json_decode((string) $response->getBody(), true);
        }catch(\GuzzleHttp\Exception\BadResponseException $e){
            if ($e->getCode() === 400){
                if( $request->username == null){
                    return response()->json(
                        ['username' => 'Username is mandatory'], $e->getCode());
                }
                if( $request->password == null){
                    return response()->json(
                        ['password' => 'Password is mandatory'], $e->getCode());
                }
                return response()->json(
                    ['login' => 'The credentials are invalid'], $e->getCode());
            }else if ($e->getCode() === 401){
                return response()->json(
                    ['login' => 'The credentials are invalid'], $e->getCode());
            }
            return response()->json(['login' => 'Something went wrong with the server'], $e->getCode());
        }
    }

    public function logout(Request $request){
        /*auth()->user()->tokens->each (function ($token, $key){
            $token->revoke();
            $token->delete();
        });*/

        $accessToken = $request->user()->token();
        //$token = $request->user()->tokens->find($accessToken);
        $accessToken->revoke();
        $accessToken->delete();

        return response()->json('Logged out successfully', 200);
    }

}
