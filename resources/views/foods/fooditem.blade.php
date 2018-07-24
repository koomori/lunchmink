@extends('layouts.app')

@section('tagline message')
<?php $foodNameWithUnderScores = str_replace(" ","_",$foodItem->food_name); ?>
<div >
  <div class="food-page-food-image">
    <img src="{{ asset('images/foodImages').'/'.$categoryName.'/'.$foodNameWithUnderScores.'_medium.jpg'}}" alt="a fresh {{$foodItem->food_name}} waiting to be eaten" >
  </div>
  <div class="food-page-info">
     <h2 class="logo-text centered" >{{ $foodItem->food_name}} {{ $categoryName }}</h2>
     <p class="brand-label-text-small">{{ $foodItem->food_description }}</p>
  </div>
</div>
@endsection
@section('content')
<div class="container ">
  <form id="fooditemorderform" method="POST" action="/addorderitem">
    {{ csrf_field() }}
    <div class="container">
    <div id="back-buttons">
      <a class="back-to-button button-custom-gradient-small rounded centered" href="/ourmenu">Back to Our Menu</a>
      <a class="back-to-button button-custom-gradient-small rounded centered" href="/food/{{$category->id}}">Back @if($categoryName == "sandwich") {{ucfirst($categoryName)}}es
    @else {{ ucfirst($categoryName) }}s @endif</a>
  </div>
      @if(Session::get('closed') == TRUE)
      @include('partials.closedmessage')
      @endif
      <div class="brand-label-text">Price : <span class="brand-label-text-small" >${{ bcadd($foodItem->price, 0, 2) }} </span></div>
      <input type="hidden" name="food_id" value="{{$foodItem->id}}">
      <label for="selectQuantity" class="brand-label-text">How Many?
      <select class="number-input" id="selectQuantity" name="food_quantity" >
        @for( $i = 1; $i <11 ; $i++)
          <option value="{{$i}}" @if ($i == 1) selected="selected" @endif>{{$i}}</option>
          @endfor
      </select>
      <div class="brand-label-text-small">Maximum order total for same day delivery and pickup is $75. Call at least a day ahead for larger orders.</div>
      </label>
      <?php //dd($errors); ?>
      @if ($errors->has('food_quantity'))
        @include('partials.errors')
      @endif
         <?php
          // group the options by type
           $currenttype = 'default';
           $categoryName = ucfirst($categoryName);
          ?>
      <div class="container">
      @if($foodItem['category_id'] != 3 || $foodItem->sub_category_name == "coffee" )
            @foreach($foodOptions as $option)
               @if($option['category_id'] == $foodItem->category_id && $option['category_id'] != 4)
                  <?php $type = $option['type'];
                    $toppingNameWithUnderscore = str_replace(" ", "_", $option['option_name']);
                  ?>
                  @if ( $type !=  $currenttype)
                    </div> <!-- keep to close type area -->
                      @if ($type == "topping")
                      <p class="food-option-heading brand-label-text centered">
                       Choose Your Desired {{ ucfirst($option['type']) }}s
                      </p>
                      <a href="#" class="button-custom-gradient-small rounded show" id="chooseAllToppings">Choose All Toppings</a>
                      @else
                      <p class="food-option-heading">
                       Choose One {{ ucfirst($option['type']) }}
                      </p>
                      @endif
                    <div class="d-flex flex-column flex-sm-row flex-wrap justify-content-between justify-content-lg-start food-option-box">
                  @endif
                  <?php $currenttype = $type; ?>
                  @if("topping" == $option['type'])
                    <?php $inputType = 'checkbox';
                     ?>
                    @include('foods.multipleoptiontypes')
                  @else
                    <?php $inputType = 'radio'; 
                    ?>
                    @include('foods.radiooptiontypes')
                  @endif
                @endif <!-- end printable foodoption -->
            @endforeach <!-- end food option -->
      @endif
      </div>
        <div class="centered">
        @if ($errors->has('food_id'))
            @include('partials.errors')
        @endif
        @if(Session::get('closed')== FALSE)
          @if(Session::has('order_started'))
            <button class="centered-button-wider btn-font button-custom-gradient-large" >Add {{ $categoryName }} To Order </button>
          @else
            <button class="centered-button-wider btn-font button-custom-gradient-large" >Start Online Order </button>
          @endif
        @endif  
          </div>
     </div>
   </form><!-- end fooditemorderform -->
 </div>
@endsection
