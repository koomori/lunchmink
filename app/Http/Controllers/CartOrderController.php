<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\CartOrderFood;
use App\CartOrder;
use App\FoodOption;
use App\OrderTime;
use App\Discount;
use App\Food;
use App\User;
use App\Tax;
use Carbon\Carbon;

class CartOrderController extends Controller
{
  /* start an order from the menu or by choosing a food item on the item page */
  public function index(Request $request)
  {
      if(!$request->session()->has('order_started'))
      { 
        CartOrder::startOrder($request);
      }

      if(Auth::guest())
      { 
        return view('ordering.startguestorder');
      } else {
        return view('ordering.pickupordelivery');
      }
  }
  /* users can change order name using ajax in the cart */
  public function changeOrderName(Request $request)
  {

    $orderId = $request->session()->get('cart_order_id');
    $cartOrder = CartOrder::find($orderId);
    $cartOrder->order_name = $request->order_name;
    $cartOrder->save();
    return response()->json(['order_name' => $request->order_name]);
  }

/* return view for choosing picup or delivery */
  public function pickupordeliverymethod()
  {
    return view('ordering.pickupordelivery');
  }

  public function setorderaddress(Request $request)
  {
    $orderId = $request->session()->get('cart_order_id');
    $address = $request->delivery_location_street_address;
    $instructions = $request->delivery_location_instructions;
    CartOrder::where('id', '=', $orderId)
                ->update([
                  'delivery_location_street_address' =>
                   "$address",
                  'delivery_location_instructions' =>
                   "$instructions"
                  ]);
    return view('ordering.beginaddingitems');
  }

/* set the type of order as a pickup order and 
   show available times for a pickup order */
  public function makePickupOrder(Request $request)
  {
    
    $method = 'pickup';
    $orderId = $request->session()->get('cart_order_id');
    CartOrder::where('id', '=', $orderId)
                  ->update([
                  'delivery_location_street_address' =>
                   NULL,
                  'delivery_location_instructions' =>
                   NULL
                   ]);
    OrderTime::availableOrderTimes($method, $orderId, $request);

    //dd(session()->get('availableOrderTimes'));
    return view('ordering.choosetime', compact('method'));
   } 

/* show the whole order in the cart */
  public function show(Request $request)
  {
    $orderId = $request->session()->get('cart_order_id');
    $cartOrder = new CartOrder;
    $wholeOrder =  $cartOrder->displayCartOrder($orderId);
    if(Auth::user())
    {
      $customNames = User::prepareCustomNames();
      return view('ordering.order', compact('wholeOrder','customNames'));
    } else {
      return view('ordering.order', compact('wholeOrder'));
    }
  }

/* check if an order has been started and add a food item 
from the item's food page and put it in the cart */ 
  public function additem(Request $request)
  {

    $checkValues = FoodOption::foodOptionIdValues();

    /* validate the food option id values */

    $this->validate($request,[
        'set_default_name' => 'sometimes|numeric',
        'food_id' => 'required',
        'food_quantity' => 'required|numeric|min:1|max:15',
        'topping' => 'sometimes|array|in:'.$checkValues['topping'],
        'sweetener' => 'sometimes|in:'.$checkValues['sweetener'],
        'dressing' => 'sometimes|in:'.$checkValues['dressing'],
        'cracker' => 'sometimes|in:'.$checkValues['cracker'],
        'cheese' => 'sometimes|in:'.$checkValues['cheese'],
        'side' => 'sometimes|in:'.$checkValues['side']
    ]);

    if($request->session()->has('order_started'))
    {
      /* add item to order already started */

      $orderId = $request->session()->get('cart_order_id');
      CartOrderFood::saveOrderFood($request, $orderId);
      
      if(Auth::user())
      {  
        CartOrder::checkForLoginAfterOrderStarted($orderId);
        $customNames = User::prepareCustomNames();
      }

      $cartOrder = new CartOrder;
      $wholeOrder =  $cartOrder->displayCartOrder($orderId);

      if(Auth::user())
      {
        return view('ordering.order', compact('wholeOrder','customNames'));
      } else {
        return view('ordering.order', compact('wholeOrder'));
      }

    } else {
      /*
       start order and let user set pickup or delivery
      */
      CartOrder::startOrder($request);
      $orderId = $request->session()->get('cart_order_id');
      CartOrderFood::saveOrderFood($request, $orderId);

      if(Auth::user())
      {
        return view('ordering.pickupordelivery');
      } else {  
        return view('ordering.startguestorder');
      }
    }
  }
  /* remove a food from the cart */
  public function deletefood(Request $request)
  {
    $orderId = $request->session()->get('cart_order_id');
    $cartOrder = CartOrder::find($orderId);
    $foods = $cartOrder->cartOrderFoods;
    $food_ids = $foods->implode('id',',' );

    $this->validate($request, [
      'deleteorderfood_id' =>'required|numeric|in:'.$food_ids
      ]);
    $orderFood = CartOrderFood::find($request->deleteorderfood_id);
    $orderFood->delete();

    $cartOrder = new CartOrder;
    $wholeOrder =  $cartOrder->displayCartOrder($orderId);
    if(Auth::user())
    {
      $customNames = User::prepareCustomNames();
      return view('ordering.order', compact('wholeOrder','customNames'));
    } else {
      return view('ordering.order', compact('wholeOrder'));
    }
  }
  /* change the quantity of an item when it is in the chekout cart with ajax */
  public function changeQuantity(Request $request)
  {
      $orderId = $request->session()->get('cart_order_id');
      $cartOrder = CartOrder::find($orderId);
      $cartOrderFoodIds = $cartOrder->cartOrderFoodIds($request);

      $this->validate($request, [
          'food_quantity' => 'required|numeric|min:1|max:15',
          'cart_order_food_id' =>'required|numeric|in:'.$cartOrderFoodIds
        ]);
      $foodToUpdate = CartOrderFood::find($request->cart_order_food_id);
      $foodToUpdate->food_quantity = $request->food_quantity;
      $foodToUpdate->save();
      $foodId = $foodToUpdate->food_id;
      $discountId = $foodToUpdate->discount_id;
      if(empty($discountId) || is_null($discountId))
      {
        $discountId = 0;
      }
      $mainFood = Food::find($foodId);
      $mainFoodPrice = $mainFood->price;
      $tax = new Tax();
      $salesTax = $tax->getSalesTax();
      $localTax = $tax->getLocalTax();

      return response()->json(['foodPrice' => $mainFoodPrice, 'salesTax' => $salesTax, 'localTax' => $localTax, 'discountId' => $discountId ]);
  }

  /* change custom food name for a particular food */
  public function changeCustomFoodName(Request $request)
  {
    $customNames = Auth::user()->custom_names;
    $orderId = $request->session()->get('cart_order_id');
    $cartOrder = CartOrder::find($orderId);
    $cartOrderFoodIds = $cartOrder->cartOrderFoodIds($request);

    $this->validate($request, [
      'custom_name' => 'required|in:'.$customNames,
      'cart_order_food_id' =>'required|numeric|in:'.$cartOrderFoodIds
    ]);

    $foodToUpdate = CartOrderFood::find($request->cart_order_food_id);
    $foodToUpdate->custom_name = $request->custom_name;
    $foodToUpdate->save();

    return response()->json(['newName' => $request->custom_name]);
  }

  /* show the review order view and check for recent logins */ 
  public function reviewOrder()
  {
    $orderId = Session::get('cart_order_id');
    $cartOrder = new CartOrder;
    if(Auth::user())
    {  
      CartOrder::checkForLoginAfterOrderStarted($orderId);
      $customNames = User::prepareCustomNames();
    }

    $wholeOrder =  $cartOrder->displayCartOrder($orderId);
  
    if(Auth::user())
    {
      return view('ordering.placeorder', compact('wholeOrder','customNames'));
    } else {
      return view('ordering.placeorder', compact('wholeOrder'));
    }
  }

}
