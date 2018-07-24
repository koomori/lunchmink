@extends('layouts.nonav')
@section('content')
 <div id="overlay">
  <section id="order-options-backing" class="rounded center">
  <h2 class="logo-text-accent"></h2>
  @if(Auth::guest())
    <div class="brand-label-text">Guest,</div>
  @else
    <div class="brand-label-text"> {{ Auth::user()->name }},</div>
  @endif
    <div class="enclosed-border">
        <div class="brand-label-text-small">
           Choose <a class="text-link" href="/vieworder">View Order</a> in the menu to see your order.
        </div>
        <div class="centered">
          <a class="button-custom-gradient-small rounded"  href="/ourmenu">Continue To Our Menu</a>
        </div>
    </div>  
    </section>
 </div>
@endsection
