<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //const PASSPORT_SERVER_URL = "http://laravelapi.test";
    const CLIENT_ID = 3;
    const CLIENT_SECRET = 'ITFk6fkKqPtXJ4pfPBP2zgRV8YFN1wWYspLq9yoC';


    public function login(Request $request){
        try{
            $bodyHttpRequest = [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => '3',
                    'client_secret' => 'ITFk6fkKqPtXJ4pfPBP2zgRV8YFN1wWYspLq9yoC',
                    'username' => $request->username,
                    'password' => $request->password,
                    'scope' => ''
                ],
                'exceptions' => false,
            ];
            $url = 'http://laravelapi.test' . '/oauth/token';
            $http = new \GuzzleHttp\Client;
            $response = $http->post($url, $bodyHttpRequest);
            return json_decode((string) $response->getBody(), true);

        }catch(\GuzzleHttp\Exception\BadResponseException $e){
            if ($e->getCode() === 400){
                return response()->json(
                    ['msg' => 'Invalid Request. Please enter a username or password'], $e->getCode());
            }else if ($e->getCode() === 401){
                return response()->json(
                    ['msg' => 'User credentials are invalid'], $e->getCode());
            }
            return response()->json(['msg' => 'Something went wrong with the server'], $e->getCode());
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
