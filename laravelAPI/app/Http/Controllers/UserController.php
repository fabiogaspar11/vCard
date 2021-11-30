<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function getAdministrators(){
        $administrators=User::all()->where("user_type","=",'A');
        return UserResource::collection($administrators);
    }

    public function getUser(User $user)
    {
        return new UserResource($user);
    }

    public function storeUser(UserRequest $request)
    {
        $user = new User();
        $user->fill($request->validated());
        $user->password = bcrypt($request->password);
        $user->remember_token = Str::random(10);
        $user->save();
        return new UserResource($user);
    }

    public function updateUser(UserRequest $request, User $user)
    {
        $user->fill($request->validated());
        $user->password = bcrypt($request->password);
        $user->save();
        return new UserResource($request);
    }

    public function destroyUser(User $user){
        $userFound = DB::table('users')->where('id', $user->id);
        $userFound->delete();
        return new UserResource($user);
    }
}
