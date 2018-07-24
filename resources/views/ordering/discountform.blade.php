 <div class="applyDiscountForm">
          <form class="" method="POST" action="/useDiscount">
          {{ csrf_field() }}
            <input type="hidden" class="discount" name="discount_id" value="{{ $possibleDiscount }}">
          <input type="hidden" class="cart_order_food_id" name="cart_order_food_id" value="{{ $food['cart_order_food_id']}}">
          @if( ($food['sub_category_name'] == "coffee" && $food['discount_id'] == 1) || ($food['category_name'] == "sandwich"&& $food['discount_id'] == 2))
            <div class="button-custom-gradient-disabled applyDiscount">
              Discount Applied
            </div>
            <div class="button-custom-gradient-small removeDiscount">
              Remove one free {{ $food['category_name'] }} discount
            </div>
          @elseif(( ($food['sub_category_name'] == "coffee" && session()->get('coffee_discount') == true) || ($food['category_name'] == "sandwich"
      && session()->get('sandwich_discount') == true) ) && !session()->get('discount_currently_in_use'))
            <div class="button-custom-gradient-small applyDiscount">
                Apply free {{ $food['category_name'] }} discount
            </div>  
            <div class="button-custom-gradient-small removeDiscount discountNotApplied">
                Remove one {{ $food['category_name'] }}  discount
            </div>
          @else
            <div class="button-custom-gradient-small applyDiscount hideOtherDiscounts">
                Apply free {{ $food['category_name'] }} discount
            </div>
            <div class="button-custom-gradient-small removeDiscount discountNotApplied">
                Remove one {{ $food['category_name'] }}  discount
           </div>
          @endif
           <div id="error-{{ $food['cart_order_food_id'] }}" class="update-discount-message-area" >
              Updated Message Area
            </div>
          </form>
</div>
