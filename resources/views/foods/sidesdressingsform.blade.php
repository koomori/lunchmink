
 <form method="POST" action="/user">
      <p class="food-option-heading">Choose {{ $arrayName }} </p>
      <div class="d-flex flex-wrap justify-content-between food-option-box">

    @foreach($array as $option)
        @include('partials.sideOptions')
    @endforeach

    </div>
    <div class="centered">
      <button class="centered-button btn-font button-custom-gradient-large">{{ $buttonMessage }} </button>
   </div>
</form>
