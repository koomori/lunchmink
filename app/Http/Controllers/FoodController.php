<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Food;
use App\Category;
use App\FoodOption;

class FoodController extends Controller
{
    //

  public function index(Category $catid)
  {   $categoryName = $catid->category_name;
      $foods = Food::where('category_id', "=", $catid->id)->get();
      return view('foods.foodsubcategorylist', compact('foods','categoryName'));
  }

  public function show(Food $foodItem)
  {
      /* get the food options that need to
        be displayed for the main food category */
      $category = $foodItem->category;
      $categoryName = $category->category_name;
      $foodOptions = $category->foodOptions;
      $allFoodOptions = FoodOption::all();
      //dd($allFoodOptions);
      $foodOptions = $foodOptions->toArray();

      if(Auth::guest())
      {
        return view('foods.fooditem', compact('foodItem','foodOptions','allFoodOptions','category', 'categoryName'));
      } else {
        $user = new User;
        $userid = Auth::user()->id;
        $userToppingChoices = $user->userToppingSelections($userid);
        $userNonToppingChoices = User::select('sandwich','soup','beverage','side','sweetener','dressing','cracker','cheese')
        ->where('id', '=', $userid) ->get();

        return view('foods.fooditem', compact('foodItem','foodOptions','userToppingChoices','allFoodOptions','customNames','userNonToppingChoices','category', 'categoryName'));
      }

  }
}
