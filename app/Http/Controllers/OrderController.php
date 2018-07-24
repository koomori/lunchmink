<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\CartOrder;
use App\OrderFood;
use App\Order;
use App\Tax;

class OrderController extends Controller
{
    //

  public function placeOrder(Request $request)
  {
      $modifiedCreditCard = (int) preg_replace('/[^0-9]/', '', $request->credit_card);
      $request->merge(array('modified_credit_card' => $modifiedCreditCard ));
      
      $this->validate($request,[
        'modified_credit_card' => 'required|regex:/^[0-9]{16}\z/',
      ]);
      
      $orderId = $request->session()->pull('cart_order_id');
      $cartOrder = CartOrder::find($orderId);
      $cartOrderFoods = $cartOrder->cartOrderFoods;
      $cartOrder = $cartOrder->toArray();
      $orderSubtotal = 0;

      foreach($cartOrderFoods as $cartOrderFood)
      {
        $itemSubtotal = 0;
        $food_id = $cartOrderFood->food_id;
        $price = $cartOrderFood->findPrice($food_id);
        $quantity = $cartOrderFood->food_quantity;
       
        $itemSubtotal = $price * $quantity;
        $orderSubtotal += $itemSubtotal;
      }
      $tax = new Tax;
      $localTaxPercentage = $tax->getLocalTax();
      $salesTaxPercentage = $tax->getSalesTax();

      $localTax = $localTaxPercentage * $orderSubtotal;
      $salesTax = $salesTaxPercentage * $orderSubtotal;

      $cartOrder['sales_tax_total'] = $salesTax;
      $cartOrder['local_tax_total'] = $localTax;
      $cartOrder['order_total'] = $orderSubtotal + $salesTax + $localTax;

      $cartOrder['cart_order_id'] = $cartOrder['id'];
      $orderTime = "";
      $method = "";

      if(!empty($cartOrder['pickup_time']))
      {
        $orderTime = $cartOrder['pickup_time'];
        $method = 'pickup';

      } elseif(!empty($cartOrder['delivery_time']))
      {
        $orderTime = $cartOrder['delivery_time'];
        $method = 'delivery';
      }

        //"2017-09-18 14:30:00"
     $time =  Carbon::createFromFormat('Y-m-d H:i:s', $orderTime);
     $friendlyTime = $time->format('g:i A');

      //remove unnecessary variables
      unset($cartOrder['id']);
      unset($cartOrder['cart_order_foods']);

      Order::insert($cartOrder);
      $cartOrderFoods = $cartOrderFoods->toArray();
      OrderFood::insert($cartOrderFoods);

      $request->session()->forget('order_started');
      $request->session()->forget('availableOrderTimes');
      $request->session()->forget('discount_currently_in_use');
      $request->session()->forget('sandwich_discount');
      $request->session()->forget('remaining_sandwich_to_discount');
      $request->session()->forget('number_of_sandwich_discounts');
      $request->session()->forget('remaining_coffee_to_discount');
      $request->session()->forget('coffee_discount');
      $request->session()->forget('number_of_coffee_discounts');
      $request->session()->save();
      
      return view('ordering.ordercompleted', compact('cartOrder', 'friendlyTime','method'));
  }
}
