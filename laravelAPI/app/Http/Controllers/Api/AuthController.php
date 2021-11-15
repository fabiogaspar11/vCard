<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //const PASSPORT_SERVER_URL = "http://laravelapi.test";
    //const CLIENT_ID = 2;
    //const CLIENT_SECRET = 'fjjQYHslnhnRu6Xi17ZzdOPyWfyrwq6rPb4mVsog';


    public function login(Request $request){
        try{
            $bodyHttpRequest = [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => '94e00292-11a4-4113-93b9-9b62ebf7d0f7',
                    'client_secret' => 'YTWKq1HPvMfyGmEbwfq1y5R5QzFUtTN9sKUOjEwl',
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

        /*$accessToken = $request->user()->token();
        //$token = $request->user()->tokens->find($accessToken);
        $accessToken->revoke();
        $accessToken->delete();*/

        //return response()->json('Logged out successfully', 200);
        return $request->user()->token();
    }

}
