@extends('layouts.app')
@section('tagline message')
<p class="brand-label-text">Fresh lunches prepared daily, pickup and delivery, in downtown Duluth, MN</p>
  @if(Session::get('closed') == TRUE)
    @include('partials.closedmessage')
  @endif
@endsection
@section('content')
<div class="layout side-borders">
<div id="homepage-food-images">
    <img src="{{ asset('images/foodImages/homepageimages/hamsandwich.jpg') }}" alt="a large tasty ham sandwich with lots of meat and onions and tomatoes"><img src="{{ asset('images/foodImages/homepageimages/carrotsoup.jpg') }}" alt="a large bowl of warm carrot soup with a garnish of parsely"><img src="{{ asset('images/foodImages/homepageimages/coffeeinhands.jpg') }}" alt="a warm cup of coffee warming a lady's hands">
</div>
    <section id="order-online-message">
      @if(!Session::has('order_started') && Session::get('closed') == FALSE)
      <a href="/onlineorder" class="button-custom-gradient-wide">Start Online Order</a>
      @endif
      <a href="/deliverylocation" class="button-custom-gradient-wide no-border-top">Check our Delivery Area</a>
    </section>
    <section id="discounts" class="bright-background d-flex flex-column flex-md-row align-items-md-start align-items-stretch">
    <div class="discounts-container delivery-and-pickup">
      <ul class="show-dot">
        <li class="order-message">We can deliver same-day orders of $10 to $75 to account holders in our <a class="text-link" href="/deliverylocation">delivery area</a>.</li>
        <li class="order-message">Pickup your order at our location.</li>
        <li class="order-message"> Your Pickup or Delivery time will be reserved for 15 minutes.</li>
        <li class="order-message">If your order isn't placed after 15 minutes, you will need to choose another available time.</li>
      </ul>
    </div>
     @if (Auth::user())
    <div class="discounts-container discount-progress d-flex flex-column justify-content-between">
    <h3 class="logo-text-accent-light discount-title centered">Your Discount Progress</h3>
    @else
    <div class="discounts-not-logged-in discount-progress" >
    <h3 class="logo-text-accent-light discount-title centered">Account Holder Discounts</h3>
    @endif
        <ul class="next-discounts">
             @if(Auth::user())
             @foreach($discounts as $discount)
               @if($discount->id == 1 )
                  <li class="brand-label-text">{{ $discount->discount_description }}
                  </li>
                  <li class="brand-label-text-small">
                    Progress: You need <span class="brand-label-text-small">{{ session()->get('remaining_coffee_to_discount') }} of {{ $neededPurchases }} coffees for next coffee discount.</span>
                  </li>
                  @if(session()->get('coffee_discount') === true)
                  <li class="brand-label-text-small">
                    Progress: You have {{ session()->get('number_of_coffee_discounts') }} coffee
                    @if(session()->get('number_of_coffee_discounts') === 1){{ 'discount' }}  @else
                    {{'discounts'}}
                    @endif
                     available.
                  </li>
                  @else
                    <li class="brand-label-text-small">No coffee discounts are available</li>
                  @endif
                @endif
                @if($discount->id == 2)
                     <li class="brand-label-text">{{ $discount->discount_description }}
                  </li>
                  <li class="brand-label-text-small">
                    Progress: You need <span class="brand-label-text-small">{{session()->get('remaining_sandwich_to_discount') }} of {{ $neededPurchases }} sandwiches for next sandwich discount.</span>
                  </li>
                  @if(session()->get('sandwich_discount') === true)
                  <li class="brand-label-text-small">
                      Progress: You have {{ session()->get('number_of_sandwich_discounts') }} sandwich
                      @if(session()->get('number_of_sandwich_discounts') === 1){{ 'discount' }}  @else
                       {{'discounts'}}
                     @endif
                     available.
                  </li>
                  @else
                    <li class="brand-label-text-small">No sandwich discounts are available</li>
                  @endif
                @endif
              @endforeach
            @else
              @foreach ($discounts as $discount)
                <li class="brand-label-text-small">{{ $discount->discount_description }}</li>
              @endforeach
            @endif
         </ul>
         <div class="one-discount">One Discount Per Order</div>
    </div>   
  </section>
</div>
@endsection('content')

