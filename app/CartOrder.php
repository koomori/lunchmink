<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Carbon\Carbon;

class CartOrder extends Model
{
    protected $orderTimeFirstPicked;
     protected $guarded = [
        'id'
    ];

   public function cartOrderFoods()
   {
     return $this->hasMany(CartOrderFood::class)->orderBy('created_at', 'desc');
   }

    public function orderTime()
   {
     return $this->hasOne(OrderTime::class);
   }

  public static function startOrder(Request $request) {
    $request->session()->put('order_started', 'started');

      // Give the order a default name
      $nowInDuluth = Carbon::now();

      $now = $nowInDuluth->format('F jS l, Y');

      if(Auth::guest())
      {
      $cartOrderId = self::insertGetId([
        'user_id' => 0,
        'name' => 'GUEST',
        'order_name' => "$now"
        ]);
      }else {
        $userId = Auth::user()->id;
        $userName = Auth::user()->name;
        $userEmail = Auth::user()->email;

        $cartOrderId = self::insertGetId([
        'user_id' => $userId,
        'name' => "$userName",
        'email' => "$userEmail",
        'order_name' => "$now"
        ]);
    }
            $request->session()->put('cart_order_id', $cartOrderId);
   }

   public function displayCartOrder($orderId)
   {
      if(Auth::user() && !session()->has('number_of_coffee_discounts'))
      {   $discount = new Discount;
          $discount->addUserDiscountInfoToSession();
      }

      $order = self::find($orderId);
      if(!empty($order->pickup_time))
      {
        $order->pickup_time_friendly = Carbon::parse($order->pickup_time);
        $order->pickup_time_friendly = $order->pickup_time_friendly->format('g:i A');
      } elseif(!empty($order->delivery_time)) {
         $order->delivery_time_friendly = Carbon::parse($order->delivery_time);
         $order->delivery_time_friendly = $order->delivery_time_friendly->format('g:i A');
      }

      if(!empty($order->order_time_held_until))
      {
        $order->order_time_held_until_friendly = Carbon::createFromTimestamp($order->order_time_held_until);
        $order->order_time_held_until_friendly = $order->order_time_held_until_friendly->format('g:i A');
      }
      
      $orderFoods = $order->cartOrderFoods;
      $foodIdentification = array();
      $foodOptionsWithFoodId = array();
      $mainFoodItems = array();
      $wholeOrder=array();

      $discountInUse = false;

      $tax = new Tax();
      $salesTax = $tax->getSalesTax();
      $localTax = $tax->getLocalTax();

      foreach($orderFoods as $food)
      {
        /* get display information for the main food item
        from the food table and combine with order information */
        $discountIds = Discount::pluck('id');
        $discountIds = $discountIds->toArray();
        //dd($discountIds);

        if(in_array($food->discount_id, $discountIds) )
        {
            $discountInUse = true;
        }

        $cardorderfoodid = $food->id;
        $mainFood = collect(Food::where('id', '=', $food->food_id)
          ->get());
        $mainFood = $mainFood->toArray();
        $cartfood = collect($food);
        $cartfood->toArray();
        $foodIdentification = $cartfood->merge($mainFood[0]);
        $foodIdentification = $foodIdentification->toArray();
        $foodIdentification['cart_order_food_id'] = $cardorderfoodid;

        //get the category_name and add it to the identification
        $foodItem = Food::find($food->food_id);
        $foodCategory = $foodItem->category;
        $cardOrderFoodCategory = ['category_name' => $foodCategory->category_name];
        $foodIdentification['category_name'] = $cardOrderFoodCategory['category_name'];
        $mainFoodItems[]  = $foodIdentification;
        /* get all food options for the whole order with
           the specific main food item indicated by the cart_order_food_id
        */
        $choosenFoodOptionIdString = $food->food_option_ids;
        $foodOptions = CartOrderFood::foodOptions($choosenFoodOptionIdString);

        foreach($foodOptions as $option )
        {
            $optionArray = $option->toArray();
            $optionArray['cart_order_food_id'] = $cardorderfoodid;
            $foodOptionsWithFoodId[]  = $optionArray;
        }

      }

        session()->put('discount_currently_in_use', $discountInUse);

        $wholeOrder["order"] = $order;
        $wholeOrder["mainFoodItems"] = $mainFoodItems;
        $wholeOrder["foodOptionsWithFoodId"] = $foodOptionsWithFoodId;
        $wholeOrder["salesTax"] = $salesTax;
        $wholeOrder["localTax"] = $localTax;

       return $wholeOrder;
  }
  public function cartOrderFoodIds($request)
  {
        $cartOrderFoods = $this->cartOrderFoods;
        return $cartOrderFoods->implode('id',',' );
  }

  public static function checkForLoginAfterOrderStarted($orderId)
  {
      $cartOrder = self::find($orderId);
      if(empty($cartOrder->user_id))
      { 
        $cartOrder->user_id = Auth::user()->id;
        $cartOrder->name = Auth::user()->name;
        $cartOrder->email = Auth::user()->email;
        $cartOrder->save();
      }
  }     
}
