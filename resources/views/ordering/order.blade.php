@extends('layouts.app')
<?php
    $page = 'order';
    $order = $wholeOrder["order"];
    $orderHeldUntil = $order->order_time_held_until;
    $mainFoodItems = $wholeOrder["mainFoodItems"];
    $foodOptionsWithFoodId = $wholeOrder["foodOptionsWithFoodId"];
    $salesTax = $wholeOrder["salesTax"];
    $localTax = $wholeOrder["localTax"];
?>
@section('tagline message')
@if(Session::get('closed') == FALSE)
    <div id="holdTime" data-time="{{ $orderHeldUntil }}"></div>
    @if(!empty($order->pickup_time))
    <div class="logo-text-accent">Checkout - View Pickup Order</div>
    <p id="timeMessage" class="brand-label-text-small">Remining Time Your Pickup Time is Held:</p>
    <div id="timeReservedUntil" data-method="pickup"></div>  
    @elseif(!empty($order->delivery_time))
    <div class="logo-text-accent">Checkout - View Delivery Order</div>
    <p id="timeMessage" class="brand-label-text-small">Remining Time Your Delivery Time is Held:</p>
    <div id="timeReservedUntil" data-method="delivery"></div>   
    @else
    <div class="centered brand-label-text choose-order-method-message">Before You Can Check Out: 
      <a class="button-custom-gradient-small rounded" href="/pickupordeliverymethod" >Choose Pickup or Delivery Order
      </a>
    </div>
    @endif
  @else 
    @include('partials.closedmessage')
  @endif 
  @endsection
  @section('content')
  @if(Session::get('closed') == FALSE)
  <div class="container side-borders form-stripe-color">
    <section>
      <div  class="brand-label-text">Order Name:
        <div id="currentOrderName" class="brand-label-text-small">
          {{ $order->order_name }}
        </div>
      </div>
    @if(Auth::user())
    <div class="brand-label-text d-flex justify-content-between align-items-center flex-wrap  extra-border-bottom">
        <div class="form-toggle extra-margin-bottom" >
              <a class="button-custom-gradient-small rounded show" id="change-name" data-text="Change Name" href="#" aria-expanded="false">
              Change Order Name
              </a>
        </div>
    </div>
    <div class="display-user-form ">
      <div class="d-flex flex-column" >
        <form class="extra-background" id="order-name-form" method="POST" action="/changeOrderName">
        {{ csrf_field() }}
          <label class="extra-margin-bottom" for="newOrderName">New Order Name
          <input id="newOrderName" name="order_name" type="text" placeholder="Tuesday Shrimp Sandwich"></label>
          <button class="button-custom-gradient-small rounded" >Set New Name</button>
          <div>
             <div id="orderNameError"></div>
          </div>
        </form>
      </div>
    </div>
    @endif
     </section>
     <section id="order-time-location">     
    @if(!empty($order->pickup_time || !empty($order->delivery_time)))
      <div class="d-flex flex-column" >
      <div class="time-info">
        @if(!empty($order->pickup_time))
        <div class="brand-label-text">
          Pickup Time: {{ $order->pickup_time_friendly }}
        </div>
        @endif
        @if(Auth::user())
          @if(!empty($order->delivery_location_street_address))
          <div class="brand-label-text">
          Delivery Time: {{ $order->delivery_time_friendly }}
          </div>
          @endif
        @endif
        @if(!empty($order->order_time_held_until))
          <div class="brand-label-text-small">
            <ul class="show-dot">
            <li>This order time is held for 15 minutes.</li>
            <li>Please place your order before {{ $order->order_time_held_until_friendly }} <br>or your time may no longer be available.</li>
             </ul>
          </div>
        @endif
      </div><!-- close time info -->  
      @if(!empty($order->delivery_location_street_address))
      <div class="address-instruction-container">
        <div class="brand-label-text">Delivery Address:
          <div class="brand-label-text-small">{{ $order->delivery_location_street_address }}</div>
        </div>
        @if($order->delivery_location_instructions !="")
        <div class="brand-label-text">Instructions:
          <div class="brand-label-text-small">{{  $order->delivery_location_instructions }}</div>
        </div>
      </div>  
        @endif
      <div class="location-alter" >
          <form  id="change-address" action="/deliverylocation" method="get" aria-expanded="false">
           {{ csrf_field() }}
          <input type="hidden" name="addressPurpose" value="changeDeliveryLocationAddress">
           <button class="rounded show button-custom-gradient-small">Change Location/Instructions</button>
          </form>
      </div>
    </div>                  
    @endif
  @endif  
  </section>
  <section>
  <?php $total = 0; ?>
  @if(isset($mainFoodItems))
  <?php $runningTotal = 0 ?>
  <h2 class="centered logo-text-accent-header" >Order Foods</h2>
  @foreach ($mainFoodItems as $food)
  <div class="order-display-light">
      <?php
        $subtotal = 0;
        if($food['discount_id'] == 1 || $food['discount_id'] == 2)
        {
           $subtotal =  ($food['food_quantity'] - 1) * $food['price'];
        } else {
            $subtotal =  $food['food_quantity'] * $food['price'];
        }
        $type = "default";
        $runningTotal += $subtotal;
      ?>
    <div class="logo-text-accent">{{ ucwords($food['food_name']) }}
       @if($food['category_name'] !="beverage")
          {{ ucwords($food['category_name']) }}
       @endif
    </div>
    <ul>
    @if($foodOptionsWithFoodId)
    <?php
        $outputToppingNames = array();
    ?>
      @foreach($foodOptionsWithFoodId as $foodOption)
        @if($foodOption['cart_order_food_id'] == $food['cart_order_food_id'])
          <?php $newType = $foodOption['type'] ?>
              @if($newType !== "topping")
                  @if($newType !== $type )
                  <li class="brand-label-text ">{{ $newType }}: <span class="brand-label-text-small">{{$foodOption['option_name'] }}</span></li>
                  @else
                  <li class="brand-label-text-small">
                   {{$foodOption['option_name'] }}
                  </li>
                  @endif
              @else
                  <?php $outputToppingNames[] = $foodOption['option_name'] ?>
              @endif
        <?php $type = $foodOption['type'] ?>
        @endif
      @endforeach
      @if(count($outputToppingNames)> 0)
      <?php $names = implode(', ', $outputToppingNames); ?>
      <li class="brand-label-text"> toppings: <span class="brand-label-text-small"> {{ $names }}</span></li>
      @endif
      <?php $type = "default"; ?>
    @endif
    </li>
  </li>
  </ul>
  </div>
  @if(Auth::user())
  <div class="order-display-light">
      @include('foods.showcustomnamesforfoods')
  </div>
  @endif
    <div class="brand-label-text-small order-display-light thick-border-bottom  d-flex flex-md-row justify-content-between align-items-center flex-wrap flex-column">
    <div id="discountContainer">
    @if(Auth::user())
      @if($food['sub_category_name'] == "coffee")
        @if(session()->get('coffee_discount') == false)
            <?php $coffeesToGet = session()->get('remaining_coffee_to_discount'); ?>
            <div class="brand-label-text">
              You have {{ $coffeesToGet }} @if( $coffeesToGet > 1 ) coffees @else coffee @endif to get before your next discount.
            </div>
         @else
          <?php $possibleDiscount = 1; ?>
         @include('ordering.discountform')
         @endif
      @endif
      @if($food['category_name'] == "sandwich" )
        @if(session()->get('sandwich_discount') == false)
        <?php $sandwichesToGet = session()->get('remaining_sandwich_to_discount'); ?>
          <div class="brand-label-text">
             You have {{ $sandwichesToGet }} @if( $sandwichesToGet > 1 ) sandwiches @else sandwich @endif to get before your next discount.
          </div>
        @else
          <?php  $possibleDiscount = 2; ?>
          @include('ordering.discountform')
        @endif
      @endif
    @endif  
    </div><!-- end discount container -->
    <div id="quantityChangingDiv">
      <form method="POST" action="/deleteorderfood">
       {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <input type="hidden" name="deleteorderfood_id" value="{{ $food['cart_order_food_id'] }}">
      <button class="button-custom-gradient-small rounded" >Remove {{ $food['food_name'] }}
           @if($food['category_name'] !="beverage")
              {{ $food['category_name'] }}
           @endif
      </button>
           @if ($errors->has('deleteorderfood_id'))
            @include('partials.errors')
          @endif
    </form>
    <form  method="POST" action="/itemquantity">
     {{ csrf_field() }}
     <div class="align-right brand-label-text" >
      <label for="selectQuantity" class="align-right">Quantity:
          <input type="hidden" class="cart_order_food_id" name="cart_order_food_id" value="{{ $food['cart_order_food_id']}}">
          <select class="number-input selectQuantity" name="food_quantity" >
            @for( $i = 1; $i < 11 ; $i++)
              <option value="{{$i}}" @if ($food['food_quantity'] == $i) selected="selected" @endif>{{$i}}</option>
            @endfor
          </select>
        </label>
        <div class="brand-label-text-small align-right"> x Price: ${{ bcadd($food['price'], 0, 2) }} </div>
          @if(Auth::user())
            @if(!empty($food['discount_id']))
            <div id="discount-price-show-{{ $food['cart_order_food_id'] }}">- Discount: ${{ $food['price'] }}</div>
            @else
              <div id="discount-price-{{ $food['cart_order_food_id'] }}">- Discount: ${{ $food['price'] }}</div>
            @endif
          @endif
          <div class="brand-label-text"> = SubTotal: $<span class="subtotal" id="subtotal-{{ $food['cart_order_food_id'] }}" class="brand-label-text">{{ bcadd($subtotal,0,2) }}</span>
          </div>
          <div id="discount-message-{{ $food['cart_order_food_id'] }}" class="updated-quantity-message-area" >Updated Message Area </div>
        </div>
     </form>
    </div>
  </div>
    @endforeach
    <?php $totalSalesTax = bcadd(round($runningTotal * $salesTax, 2), 0, 2);
          $totalLocalTax = bcadd(round($runningTotal * $localTax, 2), 0, 2);
          $total = bcadd(round($runningTotal + $totalLocalTax + $totalSalesTax, 2),0, 2);
    ?>
  @endif
  <div class="brand-label-text align-right extra-border-bottom">Sub Total: $<span id="orderSubTotal" >{{ bcadd($runningTotal,0,2) }}</span></div>
  <div class="brand-label-text align-right">Sales Tax: $<span id="salesTax" >{{ $totalSalesTax }}</span></div>
  <div class="brand-label-text align-right">Local Tax: $<span id="localTax" >{{ $totalLocalTax }}</span></div>
  <div class="brand-label-text align-right">Order Total: $<span id="orderTotal" >{{ $total }}</span></div>
  </section>
  <div id="resetOrderTime" class="centered">
    <a class="button-custom-gradient-small rounded" href="/ourmenu">Back To Our Menu</a>
  @if($total > 0)
    <span id="allowReviewOrder">
    @if(!empty($order->pickup_time))
      @if($total < 75)
     <a id="reviewAndPay" class="button-custom-gradient-small rounded" href="/revieworder">Review Pickup &amp; Pay</a>
    @else 
      <a id="reviewAndPay" class="button-custom-gradient-small rounded ordertoolarge" href="/revieworder">Review Pickup &amp; Pay</a>
    @endif
    @endif
    @if(Auth::user())
        @if( !empty($order->delivery_time))
          @if($total < 10 || $total > 75)
            <div id="deliveryPriceWarning" class="brand-label-text">
              Your Order Must Be between $10 and $75 for a Delivery Order
            </div>
          @elseif($total >= 10 && $total <= 75)
          <a id="reviewAndPay" class="button-custom-gradient-small rounded" href="/revieworder">Review Delivery &amp; Pay</a>
          @elseif($total > 75)
            <a id="reviewAndPay" class="button-custom-gradient-small rounded ordertoolarge" href="/revieworder">Review Pickup &amp; Pay</a>
          @endif
        @endif
    </span>
    @endif
  @endif
  </div>
  </div>
@endif
@endsection
