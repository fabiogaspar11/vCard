<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function getUsers(){
        return UserResource::collection(User::all());
    }

    public function getUser(User $user)
    {
        return new UserResource($user);
    }

    public function storeUser(UserRequest $request)
    {
        if($request->validated()){
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->remember_token ="abcd";
        }
        $user->save();
        //$validated_data = $request->validated();
        //User::create($validated_data);
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
        $user->delete();
        return new UserResource($user);
    }
}
