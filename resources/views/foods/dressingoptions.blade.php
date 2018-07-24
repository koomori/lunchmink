<div class="option-group dressing-backing">
  <label for="{{ $dressings['option_name'] }}" class="control control--{{ $inputType }}">
    <input class="dressing-for-salad" type="{{ $inputType }}" id="{{ $dressings['option_name'] }}"
    name="{{ $dressings['type'] }}" value="{{ $dressings['id'] }}"
    @if(Auth::user())
     @foreach($userNonToppingChoices as $defaultuserchoice)
       @if ($defaultuserchoice[$dressings['type']] == $dressings['option_name'])
          checked= "checked"
       @endif
     @endforeach
    @endif
     > <!-- end the input for food option -->
    <div class="control__indicator"></div>
     <span class="option-name-food"  >{{ $dressings['option_name'] }}</span>
  </label>
  <p class="option-description">{{ $dressings['option_description'] }}</p>
</div>
