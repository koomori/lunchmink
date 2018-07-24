<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\CartOrderFood;
use App\CartOrder;
use Validator;

class Discount extends Model
{
  //number of purchases necessary for discount
  protected $neededPurchases = 7;

  public function getNeededPurchases()
  {
      return $this->neededPurchases;
  }

  public function addUserDiscountInfoToSession()
  {
      $user = Auth::user();
      $userOrders = $user->orders;
      $neededPurchases = $this->neededPurchases;

      //coffee variables
      $countCoffeePurchases = 0;
      $countCoffeeDiscountsUsed = 0;
      $coffeeDiscountAvaliable = false;      
      $coffeeToDiscount = 0;
      $coffeePurchasesRemainder = 0;
      $coffeePurchasesToNextDiscount = 0;
      $numberOfPossibleCoffeeDiscounts = 0;
      $numberOfRemainingCoffeeDiscounts = 0;

      //sandwich variables
      $countSandwichDiscounts = 0;
      $countSandwichPurchases = 0;
      $countSandwichDiscountsUsed = 0;
      $sandwichDiscountAvaliable = false;
      $sandwichesToDiscount = 0;
      $sandwichPurchasesRemainder = 0;
      $numberOfPossibleSandwichDiscounts = 0;
      $numberOfRemainingSandwichDiscounts = 0;
      
      $arrayQuantity = array();

       //check if the user has any previous orders
        if($userOrders->count() > 0)
        {
                //check orders for items that add to a discount
                foreach ($userOrders as $userOrder)
                {
                    $cart_id  = $userOrder->cart_order_id;
                    $userOrderFoods = DB::table('order_foods')
                        ->where('cart_order_id','=', "$cart_id")
                        ->select('order_foods.*')
                        ->get();

                    if($userOrderFoods->count() > 0)
                    {
                        //If there is only one food?
                        foreach($userOrderFoods as $userOrderFood)
                        {
                            //dd($orderFood);
                            $orderFoodId = $userOrderFood->food_id;
                           // dd($orderFoodId);
                            $foodIdentification = DB::table('foods')
                            ->leftJoin('order_foods', 'order_foods.food_id', '=','foods.id' )
                            ->select('foods.category_id', 'foods.sub_category_name', 'order_foods.*')
                            ->where('foods.id','=',"$orderFoodId")
                            ->where('cart_order_id','=', "$cart_id")
                            ->get();
                            //dd($foodIdentification);

                            $category = $foodIdentification[0]->category_id;
                            $subcategory = $foodIdentification[0]->sub_category_name;
                            $quantity = $foodIdentification[0]->food_quantity;
                            $arrayQuantity[] = $quantity;

                            if($category == 3 && $subcategory == "coffee")
                            {
                                $countCoffeePurchases += $quantity;

                                $discountPresent = $foodIdentification[0]->discount_id;
                                if(!is_null($discountPresent))
                                {
                                    ++$countCoffeeDiscountsUsed;
                                }
                            }

                            if($category == 2)
                            {
                                $countSandwichPurchases += $quantity;

                                $discountPresent = $foodIdentification[0]->discount_id;
                                if(!is_null($discountPresent))
                                {
                                    ++$countSandwichDiscountsUsed;
                                }
                            }
                        } // For each userOrderFood
                    } //$userOrderFoods->count() > 0
                } //for each user order
            } //user orders > 0

                    if($countSandwichPurchases > 0)
                    {
                        $sandwichPurchasesRemainder = $countSandwichPurchases % $neededPurchases;
                        $sandwichPurchasesToNextDiscount = $neededPurchases - $sandwichPurchasesRemainder;
                        if($countSandwichPurchases > $neededPurchases)
                        {
                            $numberOfPossibleSandwichDiscounts = (int)($countSandwichPurchases/$neededPurchases);

                           if($numberOfPossibleSandwichDiscounts > $countSandwichDiscountsUsed)
                            {
                                $numberOfRemainingSandwichDiscounts = $numberOfPossibleSandwichDiscounts - $countSandwichDiscountsUsed;

                                if($numberOfRemainingSandwichDiscounts > 0) {
                                    $sandwichDiscountAvaliable = true;
                                }
                            }
                        }
                    } else {
                        $sandwichPurchasesToNextDiscount = $neededPurchases;
                    }
                    session(['sandwich_discount' => $sandwichDiscountAvaliable]);
                    session(['remaining_sandwich_to_discount' => $sandwichPurchasesToNextDiscount]);
                    session(['number_of_sandwich_discounts' => $numberOfRemainingSandwichDiscounts]);

                    if($countCoffeePurchases > 0)
                    {
                       
                        $coffeePurchasesRemainder = $countCoffeePurchases % $neededPurchases;
                        $coffeePurchasesToNextDiscount = $neededPurchases - $coffeePurchasesRemainder;
                        
                        if($countCoffeePurchases > $neededPurchases) {
                            
                        $numberOfPossibleCoffeeDiscounts = (int)($countCoffeePurchases/$neededPurchases);

                            if($numberOfPossibleCoffeeDiscounts > $countCoffeeDiscountsUsed)
                            {
                                $numberOfRemainingCoffeeDiscounts = $numberOfPossibleCoffeeDiscounts - $countCoffeeDiscountsUsed;

                                if($numberOfRemainingCoffeeDiscounts > 0) {
                                    $coffeeDiscountAvaliable = true;
                                }
                            }

                            $coffeePurchasesMoreThanEight = $countCoffeePurchases % $neededPurchases;
                            $coffeePurchasesToNextDiscount = $neededPurchases - $coffeePurchasesMoreThanEight;

                         } else {
                            $coffeeToDiscount = $neededPurchases - $countCoffeePurchases;
                            //dd($coffeeToDiscount);
                            session(['remaining_coffee_to_discount' => $coffeeToDiscount]);
                           // dd(session()->all());
                        }

                    } // coffee purchases greater than 0
                    else {
                        $coffeePurchasesToNextDiscount = $neededPurchases;
                    }

                    session(['coffee_discount' => $coffeeDiscountAvaliable]);
                    session(['remaining_coffee_to_discount' => $coffeePurchasesToNextDiscount]);
                    session(['number_of_coffee_discounts' => $numberOfRemainingCoffeeDiscounts]);

  } //end addUserDiscountInfoTo Session

  // get discounts from the database
  public static function prepareDiscount(Request $request)
  {
    $orderId = Session::get('cart_order_id');
    $cartOrder = CartOrder::find($orderId);
    $cartOrderFoodIds = $cartOrder->cartOrderFoodIds($request);
    $discounts = self::all();
    $discountIds = $discounts->implode('id',',' );

    Validator::make($request->all(), [
      'cart_order_food_id' =>'required|numeric|in:'.$cartOrderFoodIds,
      'discount_id' => 'required|numeric|in:'.$discountIds
    ])->validate();
  }

  // return the dollar value of the discount
  public function findDiscountAmount($cartOrderFoodId, $discountId, $foodId)
  {
         $cartOrderFood = CartOrderFood::find($cartOrderFoodId);
          $cartOrderFood->discount_id = $discountId;
          $cartOrderFood->save();
          $discountAmount = $cartOrderFood->findPrice($foodId);
          return $discountAmount;
  }
}