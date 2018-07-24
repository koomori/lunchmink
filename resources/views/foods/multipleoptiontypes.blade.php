        <div class="option-group options-container">
          <label for="{{ $toppingNameWithUnderscore }}" class="control control--{{ $inputType }}">
           <?php
                //dd($foodOptions);
                //dd($userToppingChoices);
                //dd($toppingNameWithUnderscore);
                //dd($option);
           $optionChecked = false;
            ?>
          <input type="{{ $inputType }}" id="{{ $toppingNameWithUnderscore }}"
          
            name="topping[]" value="{{ $option['id'] }}"
          @if(Auth::user())
            @foreach ($userToppingChoices as $toppingChoice)
                @if(array_key_exists("$toppingNameWithUnderscore", $toppingChoice))
                  <?php //dd($toppingChoice["$toppingNameWithUnderscore"]); ?>
                  @if($toppingChoice["$toppingNameWithUnderscore"] === 1)
                     checked="checked"
                  @endif
                @endif
            @endforeach
          @endif
            ><!-- end the input for food option -->
            <div class="control__indicator"></div><span class="option-name-food"

            >{{ $option['option_name'] }}</span>
          </label>
          <p class="option-description">{{ $option['option_description'] }}</p>
        </div>




