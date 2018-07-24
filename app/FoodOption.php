<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FoodOption extends Model
{

  protected static $foodOptionSelections = ['topping','side','sweetener','cracker','cheese','dressing'];

    public function foodOptionCategory()
    {
      return $this->belongsTo(Category::Class);
    }

    // in the user table, the toppings' column name is the 'type' with an underscore replacing a space
    public static function toppingsColumnNameArray()
    {
      $toppingNames =  self::select('option_name')->where('type','=','topping')->get();
      $names = array();
      foreach ( $toppingNames as $key => $value)
      {
          $names[] = str_replace(' ','_', $value['option_name']);
      }

      return $names;
    }

    public static function foodOptionIdValues()
    {

           $checkValues = array();

        /*
          get the ids that the food options should should
          have for each food option, grouped by type
        */
        $typePossibleValues = DB::table('food_options')->select('id','type')->get();
        $typePossibleValues = $typePossibleValues->groupBy('type');
        //$typePossibleValues = $typePossibleValues->toArray();
        
        foreach($typePossibleValues as $type => $values)
        {
             $checkValues[$type] = $values->implode('id', ',');
        }

        return $checkValues;
    }

        public static function foodOptionTypes()
        {
            $types = self::select('type')
             ->groupBy('type')
             ->get();
            $types = $types->toArray();
            $types = array_column($types,'type');

            return $types;
        }

}
