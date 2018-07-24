@extends('layouts.app')
@section('tagline message')
	<h2 class="brand-label-text">@if($categoryName == "sandwich") {{$categoryName}}es
	@else {{ $categoryName }}s
	@endif</h2>
@endsection
@section('content')
<div class="card-columns">
	 @foreach ($foods as $food)
	 <?php $foodNameWithUnderScores = str_replace(" ","_",$food->food_name); ?>
		<div class="card subcategory ">
			<a class="card-food-link" href="/item/{{$food->id}}">
				<img class="card-img-top" src="{{asset('images/foodImages').'/'.$categoryName.'/'.$foodNameWithUnderScores.'_medium.jpg'}}" alt="a fresh {{$food->food_name}} waiting to be eaten">
				<div class="image-link-overlay"></div>
			</a>
			<a class="card-body button-custom-gradient" href="/item/{{$food->id}}">
				<p class="card-text">
				{{$food->food_name}}
				</p>
		 	</a>
		</div>
	@endforeach
	</div>
@endsection
