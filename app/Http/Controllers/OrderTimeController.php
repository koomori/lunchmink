<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CartOrder;
use App\OrderTime;
use Carbon\Carbon;

class OrderTimeController extends Controller
{
  public function ordertimeset(Request $request)
  {
  
    $orderId = $request->session()->get('cart_order_id');
    $orderTime = new OrderTime;
    $availableOrderTimes = $request->session()->get('availableOrderTimes');

    $availableTimesString = implode(',', $availableOrderTimes);
    $this->validate($request, [
    'method' => 'required|in:'.'pickup,delivery',
    'selected_time' => 'required'

      ]);
    $orderMethod = $request->method;
    $selectedTime = Carbon::parse($request->selected_time);
    $orderTime = new OrderTime;
    $orderTime->cart_order_id = $orderId;
    $orderTime->method = $orderMethod; 
    $orderTime->order_time = $selectedTime;
    $orderTime->save();

    $orderTimeHeldUntil = Carbon::now()->addMinutes(15);
    $orderTimeHeldUntil = $orderTimeHeldUntil->timestamp;

    $cartOrder = CartOrder::find($orderId);
    if($orderMethod == 'pickup' )
    {
      $cartOrder->pickup_time = $selectedTime;
      $cartOrder->delivery_time = NULL;
    } else {
      $cartOrder->delivery_time = $selectedTime;
      $cartOrder->pickup_time = NULL;
    }
    $cartOrder->order_time_held_until = $orderTimeHeldUntil;
    $cartOrder->save();
    return view('ordering.beginaddingitems');
  }

  public function removeTimeReservation()
  {
      $orderId = session()->get('cart_order_id');
      OrderTime::deleteOrderTime($orderId);
      $cartOrder = CartOrder::find($orderId); 
      $cartOrder->order_time_held_until = NULL;
      $cartOrder->delivery_time = NULL;
      $cartOrder->pickup_time = NULL;
      $cartOrder->delivery_location_street_address = NULL;
      $cartOrder->delivery_location_instructions= NULL;
      $cartOrder->save();

      return response()->json(['time' => 'removed']);
  }

}
