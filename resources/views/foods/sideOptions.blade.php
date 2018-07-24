        <div class="option-group options-container">
          <label for="{{ $arrayName }}" class="control control--{{ $inputType }}">
            <input type="{{ $inputType }}" id="{{ $arrayName }}" name="{{ $arrayName }}" value="{{ $arrayName }}" checked="@if(Auth::user() && $arrayName == 'toppings')
                           @if($toppingSelection)
                              {{ 'checked' }}
                            @endif
                          @endif
             "> <!-- end the input for food option -->
            <div class="control__indicator"></div><span class="option-name-food"

            >{{ $option->option_name }}</span>
          </label>
          <p class="option-description">{{ $option->option_description }}</p>
        </div>
