<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCategories(){
        return  CategoryResource::Collection(Category::all());
    }

    public function getCategory(Category $category){
        return new CategoryResource($category);
    }

    public function storeCategory(CategoryRequest $request)
    {
        $validated_data = $request->validated();
        $category = new Category();
        $category->fill($validated_data);
        $category->save();
        return new CategoryResource($category);
    }

    public function updateCategory(CategoryRequest $request, Category $category)
    {
        $validated_data = $request->validated();
        $category->fill($validated_data);
        $category->save();
        return new CategoryResource($category);
    }

    public function destroyCategory(Category $category){
        $category->delete();
        return new CategoryResource($category);
    }

}
