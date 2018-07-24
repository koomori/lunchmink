 @extends('layouts.nonav')
 @section('tagline message')
  <h2 class="logo-text-accent">Begin Online Order</h2>
 @endsection
 @section('content')
 <div id="overlay">
   <section id="order-options-backing" class="rounded centered">
   	<h2 class="logo-text-accent">Be Our Guest or Use Your Account</h2>
    <div class="brand-label-text centered">
    	<a class="button-custom-gradient-small centered-button-wider" href="{{ route('register') }}">Create Account</a>
      <p class="brand-label-text-small">Account holders can make Delivery Orders, have customization options and can qualify for discounts.</p>
      <div class="brand-label-text"><a class="button-custom-gradient-small centered-button-wider" href="{{ route('login') }}">Login</a></div>
    </div>
    <div class="brand-label-text"><a class="button-custom-gradient-small centered-button-wider"  href="/pickupordeliverymethod"> Continue Order as our Guest</a></div>
 </div>
@endsection
