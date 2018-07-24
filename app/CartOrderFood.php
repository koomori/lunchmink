<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\FoodOption;

class CartOrderFood extends Model
{

  protected $guarded = ['id'];

  public function cartOrder()
  {
    return $this->belongsTo(CartOrder::class);
  }

  public function findPrice($food_id)
  {
      $price = Food::where('id','=',"$food_id")
              ->select('price')
              ->get();

      $price = $price->toArray();
      $price = $price[0]['price'];

      return $price;
  }

  public static function foodOptions($choosenFoodOptionIdString)
  {
      $choosenFoodOptionIdArray = explode(",",$choosenFoodOptionIdString);
      return $choosenFoodOptions = FoodOption::find($choosenFoodOptionIdArray);
  }


   public static function saveOrderFood(Request $request, $orderId)
   {
      /* save ids of options choosen for that food */

      $foodId = $request->food_id;
      $choosenOptions = array();
      $foodOptionSelections = FoodOption::foodOptionTypes();
      foreach($foodOptionSelections as $option)
      {
        if($request->$option)
        {
          $choosenOptions = array_merge($choosenOptions,(array) $request->$option);
        }
      }

      $cartOrderFood = new CartOrderFood;
      $cartOrderFood->cart_order_id = $orderId;
      $cartOrderFood->food_id = $request->food_id;
      $cartOrderFood->food_quantity = $request->food_quantity;
      $cartOrderFood->food_option_ids = implode(",", $choosenOptions);

      if(Auth::user() && Auth::user()->primary_custom_name != "")
      {
          $cartOrderFood->custom_name = Auth::user()->primary_custom_name;
      }

      $cartOrderFood->save();

      /* recalculate total, sales tax and local tax */

      $cartOrder = CartOrder::find($orderId);
      $cartOrderFoods = $cartOrder->cartOrderFoods;
      $runningTotal = 0;
      
      foreach($cartOrderFoods as $cartOrderFood)
      {
        $quantity = $cartOrderFood->food_quantity;
        $food = Food::find($cartOrderFood->food_id);
        $foodPrice = $food->price;

        if(isset($cartOrderFood->discount_id))
        {
          $quantity -= $quantity;
        }

        $runningTotal += $quantity * $foodPrice;
      }

      $tax = new Tax();
      $localTax = $tax->getLocalTax();
      $salesTax = $tax->getSalesTax();
      $localTaxTotal = $runningTotal * $localTax;
      $salesTaxTotal = $runningTotal * $salesTax;

      $cartOrder->local_tax_total = bcadd($localTaxTotal, 0, 2);
      $cartOrder->sales_tax_total = bcadd($salesTaxTotal, 0, 2);
     
      $orderTotal = $runningTotal + $localTaxTotal + $salesTaxTotal;
      $cartOrder->order_total = bcadd($orderTotal, 0, 2);
      $cartOrder->save();
   }
}
