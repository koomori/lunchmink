@if(Auth::user())
    @if(count($customNames) > 0 || strlen($food['custom_name'])>0)
        <form method="POST" action="/changeCustomFoodName">
       {{ csrf_field() }}
       <div class="brand-label-text" id="custom-name-{{ $food['cart_order_food_id'] }}"> Custom Name : {{ $food['custom_name'] }} </div>

         @if(isset($page) && $page != "review")
          <label class="brand-label-text-small" for="custom-name">Select Custom Name For this {{ $food['category_name'] }} </label>

          <select class="name-input brand-label-text-small custom-food-name" name="custom_name">
            @for( $i = 0; $i < count($customNames); $i++)
              @if ($customNames[$i] == $food['custom_name'])
              <option value="{{ $customNames[$i] }}") selected="selected" >{{ $customNames[$i] }}</option>
              @endif
            @endfor
            @for( $i = 0; $i < count($customNames); $i++)
              @if ($customNames[$i] != $food['custom_name'])
              <option value="{{ $customNames[$i] }}") >{{ $customNames[$i] }}</option>
              @endif
            @endfor
          </select>
          <input type="hidden" name="orderfoodid" value="{{ $food['cart_order_food_id'] }}">
        </form>
        @endif
      @else
         @if(isset($page) && $page != "review")
          <div class="brand-label-text" for="custom-name">You Have No Custom Names</div>
        @endif
      @endif
          @if(isset($page) && $page != "review")
            <div class="brand-label-text-margin" >
              <a class="button-custom-gradient-small rounded" href="/account">
                Manage Custom Names</a>
            </div>
          @endif

@endif
