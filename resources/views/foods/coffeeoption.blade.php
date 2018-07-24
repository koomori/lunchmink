<h2> One Choice Side </h2>
@foreach($foodOptions as $option)
  <p>{{ $option['option_name'] }}</p>
  <p>{{ $option['option_description'] }}</p>
@endforeach
