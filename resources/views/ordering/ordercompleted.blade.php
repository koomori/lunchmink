@extends('layouts.app')
@section('tagline message')
<h2 class="brand-label-text">Your Order Has Been Placed</h2>
@endsection
@section('content')
  <div class="container side-borders form-stripe-color">
  	<h3 class="brand-label-text"> Thank You: {{ $cartOrder['name'] }}</h3>
  	@if($method == "pickup")
  	<p class="brand-label-text-small">Your {{ $method }} order will be ready by {{ $friendlyTime }}.
  	</p> 
  	@elseif($method == "delivery")
  	<p class="brand-label-text-small">Your {{ $method }} order will be delivered by {{ $friendlyTime }}.
  	</p> 
  	@endif
  </div>	
@endsection
