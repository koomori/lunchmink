@if($type != "dressing")
 <form method="POST" action="/user">
    {{ csrf_field() }}
      <p class="brand-label-text">{{ $type }} </p>
       @if($type == "topping")
         <a href="#" class="button-custom-gradient-small rounded show" id="chooseAllToppings">Choose All Toppings</a>
        <div class="d-flex flex-column flex-sm-row flex-wrap justify-content-between justify-content-lg-start food-option-box">
          <?php $inputType ="checkbox"; ?>
          @foreach($selection as $option) 
          <?php $toppingNameWithUnderscore = str_replace(" ", "_", $option['option_name']); ?>
            @include('foods.multipleoptiontypes')
          @endforeach
        </div>
        <input type="hidden" name="topping[]" value="A">
      @else
      <div class="d-flex flex-column flex-sm-row flex-wrap justify-content-between justify-content-lg-start food-option-box">
      <?php  $inputType = "radio"; ?>
        @foreach($selection as $option)
        <?php 
          $toppingNameWithUnderscore = str_replace(" ", "_", $option['option_name']); 
        ?>
        @include('foods.radiooptiontypes')
        @endforeach
      </div>
      @endif
       <div class="centered">
       <div class="error-food-selected-{{$type}}"></div>
       <div class="flash-message-{{$type}}">Flash Message Holder</div>
        <button id="account-{{$type}}" class="button-custom-gradient-small rounded save-account-food-option">Save {{ ucfirst($type) }} @if($type== "topping") Selections @else Selection @endif</button>
      </div>
</form>
@endif