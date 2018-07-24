@extends('layouts.app')
<?php
    $page = 'review';
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
<div class="container side-borders form-stripe-color">
<section >
    <div  class="brand-label-text">Order Name:
      <div id="currentOrderName" class="brand-label-text-small">
        {{ $order->order_name }}
      </div>
    </div>
        <!-- removed change order name -->
</section>
<section id="order-time-location">
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
  </div>                  
    @endif
  </section>
  <section class="extra-border-bottom extra-margin-bottom">
  <?php $total = 0; ?>
  @if(count($mainFoodItems) !== 0)
    <?php $runningTotal = 0; ?>
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
       <div class="brand-label-text"> Custom Name: {{ $food['custom_name'] }} </div>
  </div>
  @endif
  <div class="brand-label-text-small extra-border-bottom  d-flex flex-md-row justify-content-between align-items-center flex-wrap flex-column">
    <div id="discountContainer">
    </div><!-- end discount container -->
      <div id="quantityChangingDiv" class="brand-label-text align-right ">
       Quantity: {{ $food['food_quantity'] }}
       <div class="brand-label-text-small align-right"> x Price: ${{ bcadd($food['price'], 0, 2) }} </div>
        @if(Auth::user())
          @if(!empty($food['discount_id']))
          <div id="discount-price-show-{{ $food['cart_order_food_id'] }}">- Discount: ${{ $food['price'] }}</div>
          @else
            <div id="discount-price-{{ $food['cart_order_food_id'] }}">- Discount: ${{ $food['price'] }}</div>
          @endif
        @endif
        <div class="brand-label-text" > = SubTotal: $<span class="subtotal" id="subtotal-{{ $food['cart_order_food_id'] }}" class="brand-label-text">{{ bcadd($subtotal,0,2) }}</span>
        </div>
        <div id="discount-message-{{ $food['cart_order_food_id'] }}" class="updated-quantity-message-area" >Updated Message Area </div>
      </div>
    </div>  
  @endforeach
  <?php $totalSalesTax = bcadd(round($runningTotal * $salesTax, 2), 0, 2);
        $totalLocalTax = bcadd(round($runningTotal * $localTax, 2), 0, 2);
        $total = bcadd(round($runningTotal + $totalLocalTax + $totalSalesTax, 2),0, 2);
  ?>
@endif
  <div class="brand-label-text align-right extra-border-bottom" >Sub Total: $<span id="orderSubTotal" >{{ bcadd($runningTotal,0,2) }}</span></div>
  <div class="brand-label-text align-right">Sales Tax: $<span id="salesTax" >{{ $totalSalesTax }}</span></div>
  <div class="brand-label-text align-right">Local Tax: $<span id="localTax" >{{ $totalLocalTax }}</span></div>
  <div class="brand-label-text align-right">Order Total: $<span id="orderTotal" >{{ $total }}</span></div>
  </section>    
<div id="resetOrderTime" class="centered">
  <a class="button-custom-gradient-small rounded" href="/ourmenu">Back To Our Menu</a>
  @if($total > 0)
  <div id="allowPlaceOrder">
  @if(!empty($order->pickup_time))
    @if($total <= 75 && $total > 0)
      @include('ordering.creditcardentry')
    @else 
    <a id="reviewAndPay" class="button-custom-gradient-small rounded ordertoolarge" href="/revieworder">Review Order</a>
    @endif
  @endif
  @if(Auth::user())
      @if( !empty($order->delivery_time))
        @if($total < 10 || $total > 75)
          <div id="deliveryPriceWarning" class="brand-label-text">
            Your Order Must Be between $10 and $75 for a Delivery Order
          </div>
        @elseif($total >= 10 && $total <= 75)
          @include('ordering.creditcardentry')
        @endif
      @endif
  </div>
  @endif
@endif
</div>
</div>
@endsection
