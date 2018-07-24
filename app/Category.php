<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\FoodOption;

class Category extends Model
{
    //

   public function foods()
   {
     return $this->hasMany(Food::class);
   }

   public function foodOptions()
   {
      return $this->hasMany(FoodOption::class)->orderBy('type', 'desc')->orderBy('option_name');
   }

}
