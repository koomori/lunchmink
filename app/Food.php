<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;

class Food extends Model
{
  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public static function coffeeIds()
  {
    $coffeIds = self::where('sub_category_name', '=', 'coffee')
                ->pluck('id')
                ->toArray();

    return $coffeIds;
  }

  public static function sandwichIds()
  {
    $sandwichIds = self::where('category_id', '=', 2)
    ->pluck('id')
    ->toArray();
    return $sandwichIds;
  }

}
