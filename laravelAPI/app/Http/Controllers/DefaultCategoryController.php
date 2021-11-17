<?php

namespace App\Http\Controllers;

use App\Http\Requests\DefaultCategoryRequest;
use App\Http\Resources\DefaultCategoryResource;
use App\Models\DefaultCategory;
use Illuminate\Http\Request;

class DefaultCategoryController extends Controller
{

    public function getDefaultCategories(){
        $allDefaultCategory = DefaultCategory::all();
        return DefaultCategoryResource::collection($allDefaultCategory);
    }

    public function getDefaultCategory(DefaultCategoryRequest $defaultCategory){
        return new DefaultCategoryResource($defaultCategory);
    }

    public function storeDefaultCategory(DefaultCategoryRequest $request)
    {
        $validated_data = $request->validated();
        $defaultCategory = new DefaultCategory();
        $defaultCategory->fill($validated_data);
        $defaultCategory->save();
        return new DefaultCategoryResource($defaultCategory);
    }

    public function updateDefaultCategory(DefaultCategoryRequest $request, DefaultCategory $defaultCategory)
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
