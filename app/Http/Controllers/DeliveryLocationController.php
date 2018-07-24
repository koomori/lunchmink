<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\OrderTime;
use App\CartOrder;

class DeliveryLocationController extends Controller
{
    //the user can set the delivery address from the cart as well as their account
  public function show(Request $request) {
    $addressPurpose = $request->addressPurpose;
    if($addressPurpose == 'changeAccountAddress' || $addressPurpose == 'setAccountAndDeliveryLocationAddress')
    {
      return view('partials.googlemap', compact('addressPurpose'));
    } elseif ($addressPurpose == 'changeDeliveryLocationAddress' || $addressPurpose == 'changeInitalDeliveryLocationAddress'){
      $cartOrderId = $request->session()->get('cart_order_id');
      $cartOrder = CartOrder::find($cartOrderId);
      return view('partials.googlemap', compact('addressPurpose','cartOrder'));
    } else {
      return view('partials.googlemap');
    }
  }

  /* after a user sets an address specifically for that delivery
  order send them to where they can choose a time for their order */
  public function goToDeliveryTimeAfterSettingOrderAddress(Request $request)
  {
      $method = 'delivery';
      $orderId = $request->session()->get('cart_order_id');
      $userId = Auth::user()->id;

      OrderTime::deleteOrderTime($orderId);
      OrderTime::availableOrderTimes($method, $orderId, $request);
      CartOrder::where('id', '=', $orderId)
                  ->update([
                    'user_id' => "$userId",
                    'pickup_time' => NULL
                    ]);
      return view('ordering.choosetime', compact('method'));
  }

  //function used when user has their account address as their order address
  public function setorderuseraddress(Request $request)
  {
    $orderId = $request->session()->get('cart_order_id');
    $userId = Auth::user()->id;
    $userName = Auth::user()->name;
    $userEmail = Auth::user()->email;
    $address = Auth::user()->delivery_location_street_address;
    $instructions = Auth::user()->delivery_location_instructions;
    $method = 'delivery';
    OrderTime::deleteOrderTime($orderId);
    OrderTime::availableOrderTimes($method, $orderId, $request);

    CartOrder::where('id', '=', $orderId)
                ->update([
                  'user_id' => "$userId",
                  'pickup_time' => NULL,
                  'delivery_location_street_address' =>
                   "$address",
                  'delivery_location_instructions' =>
                   "$instructions"
                  ]);

    return view('ordering.choosetime', compact('method'));
 }

   public function updateUserDeliveryLocation(Request $request)
   {
      $address = $request->delivery_location_street_address;
      $instructions = $request->delivery_location_instructions;
      $addressPurpose = $request->addressPurpose;

      if(is_null($instructions) || empty($instructions))
      {
        $request->delivery_location_instructions = "No Instructions";
      }
      //dd($request->delivery_location_instructions);

      $user = Auth::user();
      
      $this->validate($request, [
        'delivery_location_street_address' => 'required',
        'delivery_location_instructions' => 'sometimes|max:255',
        'addressPurpose' => [
        'sometimes', Rule::in(['changeAccountAddress','changeDeliveryLocationAddress','changeInitalDeliveryLocationAddress','setAccountAndDeliveryLocationAddress'])
        ]
      ]);

      $address = $request->delivery_location_street_address;
      $instructions = $request->delivery_location_instructions;

      if($addressPurpose == 'changeAccountAddress' || 'setAccountAndDeliveryLocationAddress')
      {  
          $user->delivery_location_street_address = $address; 
          $user->delivery_location_instructions = $instructions;
          $user->save();
      }

      $cartOrderId = $request->session()->get('cart_order_id');

      if ($addressPurpose == 'changeDeliveryLocationAddress' || $addressPurpose == 'changeInitalDeliveryLocationAddress')
      {
          CartOrder::where('id', '=', $cartOrderId)
                ->update([
                  'delivery_location_street_address' =>
                   "$address",
                  'delivery_location_instructions' =>
                   "$instructions"
                  ]);
      }
      return response()->json(['message' => 'sucessfully changed','cartOrderId' => $cartOrderId, 'address' => $address, 'instructions' => $instructions ]);
      }
}
