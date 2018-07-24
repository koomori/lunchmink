<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CartOrderFood;
use App\CartOrder;
use App\Discount;
use App\Food;
use App\Tax;

class DiscountController extends Controller
{
    //

  public function useDiscount(Request $request)
  {
    $discountId = $request->discount_id;
    Discount::prepareDiscount($request);

    $cartOrderFoodId = $request->cart_order_food_id;
    $discountId = $request->discount_id;
    $cartOrderFood = CartOrderFood::find($cartOrderFoodId);
    $foodId = $cartOrderFood->food_id;
    $foodQuantity = $cartOrderFood->food_quantity;
    $coffeeFoodIds = Food::coffeeIds();
    $sandwichIds = Food::sandwichIds();
     if($discountId == 1)
    {
      if(in_array($foodId, $coffeeFoodIds))
      {
         $discount = new Discount;
         $discountAmount = $discount->findDiscountAmount($cartOrderFoodId, $discountId,$foodId);

          session()->put('discount_currently_in_use', true);

      } else {
          return response()->json(['errorMessage' => 'unable to add coffee discount to that item']);
      }
    }

     if($discountId == 2)
    {
      if(in_array($foodId, $sandwichIds))
      {
           $discount = new Discount;

           $discountAmount = $discount->findDiscountAmount($cartOrderFoodId, $discountId,$foodId);

          session()->put('discount_currently_in_use', true);
          
      } else {
          return response()->json(['errorMessage' => 'unable to add sandwich discount to that item']);
        }
    } 
  
    $tax = new Tax;
    $salesTax = $tax->getSalesTax();
    $localTax = $tax->getLocalTax();

    //$cartOrderFoodId = $request->cart_order_food_id;
    //$cartOrderFood = CartOrderFood::find($cartOrderFoodId);

    return response()->json(['discount' => $discountId, 'discountAmount' => $discountAmount, 'salesTax' => $salesTax, 'localTax' => $localTax, 'quantity' => $foodQuantity, 'discountCurrentlyUsed' => true ]);
  }

  public function removeDiscount(Request $request)
  {
    Discount::prepareDiscount($request);

    $discountId = $request->discount_id;
    $cartOrderFood = CartOrderFood::find($request->cart_order_food_id);
    $cartOrderFood->discount_id = NULL;
    $cartOrderFood->save();

    $tax = new Tax;
    $salesTax = $tax->getSalesTax();
    $localTax = $tax->getLocalTax();

    $cartOrderFoodId = $request->cart_order_food_id;
    $cartOrderFood = CartOrderFood::find($cartOrderFoodId);
    $foodQuantity = $cartOrderFood->food_quantity;
    $food_id = $cartOrderFood->food_id;
    $price = $cartOrderFood->findPrice($food_id);

    session()->put('discount_currently_in_use', false);
    return response()->json(['discount' => $discountId, 'price' => $price, 'salesTax' => $salesTax, 'localTax' => $localTax, 'quantity' => $foodQuantity,'discountCurrentlyUsed' => false ]);
  }

}
