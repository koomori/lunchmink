<?php

namespace App\Http\Controllers;
use App\Category;

class CategoryController extends Controller
{
    public function show()
    {
      $foodcategories = Category::where('id','<=', 3)->orderBy('category_name', 'desc')->get();

      return view('foods.foodcategories', compact('foodcategories'));
    }

}
