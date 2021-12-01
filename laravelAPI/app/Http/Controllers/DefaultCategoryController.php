<?php

namespace App\Http\Controllers;

use App\Http\Requests\DefaultCategoryPost;
use App\Http\Requests\DefaultCategoryPut;
use App\Http\Resources\DefaultCategoryResource;
use App\Models\DefaultCategory;
use Illuminate\Http\Request;

class DefaultCategoryController extends Controller
{

    public function getDefaultCategories(){
        $allDefaultCategory = DefaultCategory::all();
        return DefaultCategoryResource::collection($allDefaultCategory);
    }

    public function getDefaultCategory(DefaultCategory $defaultCategory){
        return new DefaultCategoryResource($defaultCategory);
    }

    public function storeDefaultCategory(DefaultCategoryPost $request)
    {
        $validated_data = $request->validated();
        $defaultCategory = new DefaultCategory();
        $defaultCategory->fill($validated_data);
        $defaultCategory->save();
        return new DefaultCategoryResource($defaultCategory);
    }

    public function updateDefaultCategory(DefaultCategoryPut $request, DefaultCategory $defaultCategory)
    {
        $validated_data = $request->validated();
        $defaultCategory->fill($validated_data);
        $defaultCategory->save();
        return new DefaultCategoryResource($defaultCategory);
    }

    public function destroyDefaultCategory(DefaultCategory $defaultCategory){
        $defaultCategory->delete();
        return new DefaultCategoryResource($defaultCategory);
    }
}
