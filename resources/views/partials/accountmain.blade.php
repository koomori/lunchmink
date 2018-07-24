@extends('layouts.app')
@section('tagline message')
<h2 class="logo-text-accent">Your Account</h2>
@endsection
@section('content')
<div class="container side-borders">
<section>
    <fieldset id="account" class="form-group">
    <h2 class="logo-text-accent need-top-space">Personal Information</h2>
      <div class="brand-label-text form-stripe-desat d-flex justify-content-between align-items-center flex-wrap">
        <div class="user-info">Name: <span id="user-name" class="brand-label-text-small">{{ Auth::user()->name }}</span>
        </div>
        <div class="form-toggle" >
            <a class="button-custom-gradient-small rounded show" id="change-name" data-text="Change Name" href="#" aria-expanded="false">
            Change Name
            </a>
        </div>
      </div>
      <div class="display-user-form">
       <div class="form-stripe-color d-flex justify-content-between align-items-center flex-wrap" >
          <form id="name-form" class="user-update-form bright-background" method="post" action="/user">
          {{ csrf_field() }}
          <label class="brand-label-text" for="name-imput">New Name:</label>
          <input type="text" class="user-form-input extra-margin-bottom"  name="name" id="name-input" maxlength="60" placeholder="Enter Name" >
          <div id="error-username"></div>
          <button id="change-username" class="button-custom-gradient-small rounded">Change Name</button>
          </form>
        </div>
      </div>
    <div class="brand-label-text form-stripe-desat d-flex justify-content-between align-items-center flex-wrap">
      <div class="user-info">
        Email: <span id="user-email" class="brand-label-text-small">{{ Auth::user()->email }}</span>
      </div>
      <div class="form-toggle" >
          <a class="button-custom-gradient-small rounded show" id="change-email" data-text="Change Email" href="#" aria-expanded="false">
            Change Email
          </a>
      </div>
    </div>
     <div class="display-user-form">
       <div class="form-stripe-color d-flex justify-content-between align-items-center flex-wrap" >
          <form id="email-form" class="user-update-form bright-background" method="post" action="/user">
          {{ csrf_field() }}
          <label class="brand-label-text" for="name-imput">New Email:</label>
          <input type="text" class="user-form-input extra-margin-bottom" placeholder="Ex: smith@mail.com" name="email" id="email-input" >
          <div id="error-email"></div>
          <button id="change-useremail" class="button-custom-gradient-small rounded">Change Email</button>
          </form>
        </div>
    </div>
     <div class="display-user-form">
         <div class="form-stripe-desat d-flex justify-content-between align-items-center flex-wrap">
             <div class="form-toggle" >
              <a class="rounded show" id="change-name" href="#" aria-expanded="false">
                Change<br> Password
              </a>
             </div>
          </div>
         <div class="form-stripe-color d-flex justify-content-between align-items-center flex-wrap" >
            <form id="password-form" class="user-update-form" method="post" action="/user">
              {{ csrf_field() }}
              {{ method_field('PATCH') }}
              <label for="password" class="brand-label-text">Password</label>
              <input id="password" type="password" class="user-form-input" name="password" placeholder="enter password" required>
              @if ($errors->has('password'))
                  @include('partials.errors')
              @endif
              <label for="password-confirm" class="brand-label-text">Confirm Password</label>
              <input id="password-confirm" type="password" class="user-form-input" name="password_confirmation" placeholder="" required>
              @if ($errors->has('password_confirmation'))
                  @include('partials.errors')
              @endif
              <button class="button-custom-gradient-small rounded" >Save Password</button>
          </form>
        </div>
      </div>
  </section>  
  <section>
      <h2 class="logo-text-accent">Customize Names</h2>
    @include('partials.changecustomnames')

  </section>
  <section>
      <h2 class="logo-text-accent need-top-space">Delivery Location</h2>
      <div class="form-stripe-desat d-flex justify-content-between align-items-center flex-wrap">
          <div class="user-info">
            <div class="brand-label-text">Delivery Location: 
            <div class="brand-label-text-small" id="location-name"> @if(!empty(Auth::user()->delivery_location_street_address )) {{ Auth::user()->delivery_location_street_address }} @else No Location Specified @endif</span></div>
            <div class="brand-label-text">Instructions:</div>
              <p class="brand-label-text-small" id="location-instructions">@if(!empty(Auth::user()->delivery_location_instructions))
              {{ Auth::user()->delivery_location_instructions }} 
              @else No Instructions 
              @endif 
              </p>
           </div>
          </div>  
          <div class="location-alter" >
            <form  id="change-address" action="/deliverylocation" method="get" aria-expanded="false">
             {{ csrf_field() }}
            <input type="hidden" name="addressPurpose" value="changeAccountAddress">
             <button class="rounded show button-custom-gradient-small">Change Location/Instructions</button>
            </form>
            <form method="POST" action="/deleteuserinfo">
              {{ csrf_field() }}
              <input type="hidden" id="delivery_location_input" class="delivery-location-input" name="delivery_location_street_address" value="{{ Auth::user()->delivery_location_street_address }} ">
              <input type="hidden" class="user-form-input" id="delivery-location-instructions" name="delivery_location_instructions" cols="35" rows="5" >
              <div id="error-delete-address"></div>
              <button id='delete-address' class="rounded show button-custom-gradient-small" aria-expanded="false">
              Remove Location &amp; Instructions
              </button>
            </form>
          </div>
        </div>
  <h2 class="logo-text-accent need-top-space">Choose Food and Beverage Preferences</h2>
  @foreach ($foodOptions as $type => $selection)
   @include('foods.accountoptionsform')
  @endforeach
  </section>
</div><!-- div container -->
@endsection
