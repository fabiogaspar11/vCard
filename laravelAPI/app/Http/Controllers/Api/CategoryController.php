<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryPost;
use App\Http\Requests\CategoryPut;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Exception;
use Illuminate\Validation\ValidationException;


class CategoryController extends Controller
{
    public function getCategories(){

        return  CategoryResource::Collection(Category::all());
    }

    public function getCategory(Category $category){
        return new CategoryResource($category);
    }

    public function storeCategory(CategoryPost $request)
    {
        $validated_data = $request->validated();
        $category = new Category();
        $category->fill($validated_data);
        try{
            $category->save();
        }
        catch(Exception $e){
            throw ValidationException::withMessages(['default' => 'Error creating category']);
        }
        return new CategoryResource($category);
    }

    public function updateCategory(CategoryPut $request, Category $category)
    {
        $validated_data = $request->validated();
        $category->fill($validated_data);
        try{
            $category->save();
        }
        catch(Exception $e){
            throw ValidationException::withMessages(['default' => 'Error updating category']);
        }
        return new CategoryResource($category);
    }

    public function destroyCategory(Category $category){
        $category->delete();
        return new CategoryResource($category);
    }

}
