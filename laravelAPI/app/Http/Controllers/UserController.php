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
        //Str::random(10);

        $validated_data = $request->validated();
        //$validated_data->remember_token = ;
        $request->password = 9874654;
        return $request;

        //User::cre

        User::create($validated_data);
        return new UserResource($request);
    }

    public function updateUser(UserRequest $request, User $user)
    {
        $user->fill($request->validated());
        $user->password = bcrypt($request->password);
        $user->save();
        return new UserResource($request);
    }

    public function destroyUser(User $user){
        return new VcardResource($user->delete());
    }
}
