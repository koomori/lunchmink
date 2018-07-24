<div class="option-group options-container">
  <label id="label-{{ $toppingNameWithUnderscore }}" for="{{ $toppingNameWithUnderscore }}" class="control control--{{ $inputType }}">
    <input type="{{ $inputType }}" id="{{ $toppingNameWithUnderscore }}"
    name="{{ $option['type'] }}" value="{{ $option['id'] }}" 
    @if(Auth::user())
        @foreach($userNonToppingChoices as $index => $selection )
          @if($selection["$type"] == $option['option_name'])
            checked="checked"
           @elseif ($option['option_name']== 'bread sticks' || $option['option_name']== 'chedder' || $option['option_name']== 'equal' || $option['option_name']== 'goldfish')
              checked = "checked" 
          @endif
        @endforeach
    @else
      @if($option['option_name']== 'bread sticks' || $option['option_name']== 'chedder' || $option['option_name']== 'equal' || $option['option_name']== 'goldfish')
        checked = "checked"
      @endif     
    @endif
    > <!-- end the input for food option -->
    <div class="control__indicator"></div>
     <span class="option-name-food" >{{ $option['option_name'] }}</span>
  </label>
  <p class="option-description">{{ $option['option_description'] }}</p>
  @if( $option['option_name'] == "vegetable salad")
    <div id="dressing-options">
      <p class="brand-label-text">Choose a dressing packet for your salad</p>
      @foreach($allFoodOptions as $dressings)
        @if($dressings['type'] == 'dressing')
         @include('foods.dressingoptions')
        @endif
      @endforeach
    </div>
  @endif
</div>
