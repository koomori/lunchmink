@extends("layouts.maplayout")
@section("tagline message")
<h2 class="logo-text-accent">Check Delivery Area</h2>
   <ul class="brand-label-text-small">
     <li>We deliver to downtown, Duluth, MN from our location at: 302 W Superior St</li>
   </ul>
@endsection  
@section("content")
<div class="container">
  <div id="choose_location" class="form-stripe-desat side-borders">
  <div class="logo-text-accent"> Check Your Delivery Location: </div>
  <div id="map-instructions" class="brand-label-text">
        <ul class="brand-label-text-small show-dot">
          <li>Enter address on the map to make sure your location is in our delivery area.</li>
          <li>Our Delivery Area is enclosed by the black line border on the map. </li> 
          <li>If your location is not in the Delivery Area you can still place a Pickup order and pickup your order at our location.</li>
        </ul>
  </div>
  <div id="no-delivery-location" class="brand-label-text">That location is not in our delivery area</div>
  <div id="acceptable-delivery-location" class="brand-label-text-small">We can deliver to that address.</div>  
  <div id="addressPurpose">@if(isset($addressPurpose)){{ $addressPurpose }}@endif</div>
  @if(Auth::user() && !empty($addressPurpose))
    @if($addressPurpose == "setAccountAndDeliveryLocationAddress" || $addressPurpose == "changeInitalDeliveryLocationAddress" || $addressPurpose == "changeDeliveryLocationAddress" )
    <a class="button-custom-gradient-small rounded" href="/pickupordeliverymethod">Choose Order Method</a>
    @endif
  @endif  
  @if(Auth::user() && isset($addressPurpose))
      @if($addressPurpose == "changeAccountAddress" || $addressPurpose == "setAccountAndDeliveryLocationAddress" || $addressPurpose == "changeInitalDeliveryLocationAddress")
        <div id="account_location" class="brand-label-text">
            Current Account Delivery Location:
           <span class="brand-label-text-small">@if( !empty(Auth::user()->delivery_location_street_address)) {{ Auth::user()->delivery_location_street_address }} @else No location specified @endif</span>
        </div>
      @elseif($addressPurpose == "changeDeliveryLocationAddress") 
        <div id="account_location" class="brand-label-text">
            Current Order Delivery Location:
           <span class="brand-label-text-small">@if (!empty($cartOrder->delivery_location_street_address)) {{ $cartOrder->delivery_location_street_address }} @else location specified @endif</span>
        </div>
      @endif
      <div id="account_instructions" class="brand-label-text">
          Current Instructions: 
          <span class="brand-label-text-small">
          @if($addressPurpose == "changeAccountAddress" || $addressPurpose == "setAccountAndDeliveryLocationAddress" || $addressPurpose == "changeInitalDeliveryLocationAddress") 
            @if (!empty (Auth::user()->delivery_location_instructions) ) {{ Auth::user()->delivery_location_instructions }}
            @else No Instructions
            @endif
          @elseif($addressPurpose == "changeDeliveryLocationAddress")
            @if (!empty($cartOrder->delivery_location_instructions) ) {{ $cartOrder->delivery_location_instructions }}
            @else No Instructions
            @endif
          @endif
          </span>
          <br>
          @if(!empty(Auth::user()->delivery_location_street_address))
            <a id="change-delivery-instructions" class="button-custom-gradient-small rounded" href="#">Change Instructions</a>
          @endif
     </div>
      <div id="current-location" class="brand-label-text">Location: </div>
      <p id="display-delivery-location" class="brand-label-text-small"></p>
      <form id="delivery-location-form" method="POST" action="/setdeliverylocation" >
        {{ csrf_field() }}
        <input type="hidden" id="addressPurpose" name="addressPurpose" value="{{ $addressPurpose }}">
        <input type="hidden" id="delivery-location-input" name="delivery_location_street_address" value="{{ Auth::user()->delivery_location_street_address }}" >
        <label class="delivery-location-textarea brand-label-text" for="delivery_location_instructions">Tell Us Your Delivery Instructions:
        </label>
        <textarea class="user-form-input" name="delivery_location_instructions" id="delivery_location_instructions" cols="35" rows="5" placeholder="Ex: Delivery Parking in alley. Tell receptionist food delivery for Simon."></textarea>
        <div id="error-delivery-location" class="brand-label-text-small" >The instructions can be up to 255 characters.</div>
        <br>
        <button id="set-delivery-address" class="delivery-location-buttons button-custom-gradient-small rounded" >Set Delivery Address &amp; Instructions</button>
        </form>
      @endif
      <div id="showing-map">
         <input id="pac-input" class="controls" type="text" placeholder="Enter Delivery Location Address">
        <div id="map"></div>
      </div>
  </div>
</div>
@endsection

