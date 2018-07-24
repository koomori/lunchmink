@extends('layouts.nonav')
@section('tagline message')
  <h2 class="logo-text-accent centered">
    Choose An Available Time
  </h2>
@endsection
@section('content')
<?php $availableOrderTimes = session()->get('availableOrderTimes');
      //dd($availableOrderTimes);
    //dd(count($availableOrderTimes));
 ?>

<div id="overlay">
  <section id="order-options-backing" class="rounded centered">
    @if(count($availableOrderTimes) != 0)
        <div class="brand-label-text">The earliest {{ $method }} time is<br> {{ $availableOrderTimes[0]}}. </div>
      @if(count($availableOrderTimes) > 0)
      <div class="centered enclosed-border">
        <form method="POST" action="/ordertimeset">
        {{ csrf_field() }}
        <input type="hidden" name="method" value="{{ $method }}">
        <label for="{{ $method }}-select" class="brand-label-text" >{{ ucfirst($method) }} Times Avaliable</label>
        <select name="selected_time" id="{{ $method }}-select" class="brand-label-text-small large-select-font time-select" >
          <optgroup label="Available Order Times">
          @foreach($availableOrderTimes as $index => $time)
            <option value="{{ $time }}" @if($index == 0) selected="selected" @endif >{{ $time }} </option>
          @endforeach
            <optgroup>  
        </select>
        <br>
        <button class="button-custom-gradient-small rounded centered">Choose This {{ ucfirst($method) }} Time</button>
         @if ($errors->has('method') || $errors->has('selected_time') )
           @include('partials.errors')
          @endif
        </form>
      </div>
     @endif
    @else
      @if(isset($closedForOrders))
      <div class="brand-label-text">It's too late to place an order. The last time to place an order is 4:30 pm.</div>
      @else
      <div class="brand-label-text">We're all booked up for {{ $method }} orders today.</div>
      @endif
       <a id="checkPickupTimes" href="/" class="centered button-custom-gradient-small rounded">Return to Home Page</a>
    @endif
  
    </section>
</div>
@endsection


