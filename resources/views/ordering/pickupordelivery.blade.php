@extends('layouts.nonav')
@section('content')
<div id="overlay">
  <section id="order-options-backing" class="rounded center">
@if(Auth::guest())
  <p class="brand-label-text-small">Only account holders can make a Delivery Order</p>
@endif
<div class="centered">
  <h2 class="logo-text-accent">
    Make a Pickup Order
  </h2>
  <div class="enclosed-border extra-margin-bottom  d-flex flex-column center">
    <a class="button-custom-gradient-small rounded no-margin-bottom" href="/pickuporder">
      Pickup Your Order
    </a>
  </div>
</div>
@if(Auth::user())
  <h2 class="centered logo-text-accent">
  Make a Delivery Order
  </h2>
  <div class="enclosed-border">
    <div class="brand-label-text">
      Account Address:
      <span class="brand-label-text-small">
        @if(!empty(Auth::user()->delivery_location_street_address)){{ Auth::user()->delivery_location_street_address }} 
        @else No Location 
        @endif 
      </span>
    </div>
    <div class="brand-label-text">
        Instructions:
         <span class="brand-label-text-small">
           @if(!empty(Auth::user()->delivery_location_instructions))
            <?php
             $shortInstructions = substr(Auth::user()->delivery_location_instructions,0,40).'...';
            ?>
             {{  $shortInstructions }}
            @else 
                No Instructions
              </span>
            @endif
    </div>
  @if(!empty(Auth::user()->delivery_location_street_address))
  <div class="centered">
        <a class="button-custom-gradient-small rounded" href="/keepusersdeliveryaddress">
          Use Account Delivery Address &amp; Instructions
        </a>
  </div>
  <div class="centered">
      <form method="get" action="/deliverylocation">
        <input type="hidden" name="addressPurpose" value="changeInitalDeliveryLocationAddress">
        <button class="button-custom-gradient-small rounded" >
          Set Different Order Delivery Address
        </button>
      </form>
  </div>
  @else
  <div class="centered">
      <form method="get" action="/deliverylocation">
        <input type="hidden" name="addressPurpose" value="setAccountAndDeliveryLocationAddress">
        <button class="button-custom-gradient-small rounded" >Set Delivery Address
        </button>
      </form>
  </div>
  @endif
@endif
  </div>
  </section>
</div><!-- end overlay -->
@endsection