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
}
