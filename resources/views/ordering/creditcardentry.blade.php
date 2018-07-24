<div class="centered brand-label-text">
	<form method="POST" action="/placeorder" >
	{{ csrf_field() }}
	<label for="creditCard">Enter Credt Card Number & Place Order</label>
	<input id="creditCard" type="text" name="credit_card" placeholder="4444-4444-4444-4444" maxlength="19">
	@if($errors->has('credit_card')|| $errors->has('modified_credit_card'))
		@include('partials.errors')
	@endif	
	<button class="button-custom-gradient-small rounded" >Place Order</button>
	</form>
</div>	