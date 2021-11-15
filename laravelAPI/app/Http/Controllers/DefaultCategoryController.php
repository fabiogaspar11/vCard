<?php

namespace App\Http\Controllers;

use App\Http\Requests\DefaultCategoryRequest;
use App\Http\Resources\DefaultCategoryResource;
use App\Models\DefaultCategories;
use Illuminate\Http\Request;

class DefaultCategoryController extends Controller
{

    public function getDefaultCategories(){
        $allDefaultCategories = DefaultCategories::all();
        return DefaultCategoryResource::collection($allDefaultCategories);
    }

    public function getDefaultCategory(DefaultCategoryRequest $defaultCategory){
        return new DefaultCategoryResource($defaultCategory);
    }
}
