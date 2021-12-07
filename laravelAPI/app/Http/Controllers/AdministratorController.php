<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Administrator;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AdministratorPost;
use App\Http\Resources\AdministratorResource;

class AdministratorController extends Controller
{
    public function getAdministrator(Administrator $admin)
    {
        return new AdministratorResource($admin);
    }

    public function getAdministrators()
    {
        $allAdministrators = Administrator::all()->except(auth()->user()->id);
        return AdministratorResource::collection($allAdministrators);
    }

    public function storeAdministrator(AdministratorPost $request)
    {
        $admin = new Administrator();
        $admin->fill($request->validated());
        $admin->password = Hash::make($request->password);
        $admin->remember_token = Str::random(10);
        $admin->save();
        return new AdministratorResource($admin);
    }

    public function destroyAdministrator(Administrator $admin){
        $admin->delete();
        return new AdministratorResource($admin);
    }
}
