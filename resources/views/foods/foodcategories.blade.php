@extends('layouts.app')

@section('tagline message')
<h2 class="brand-label-text">Choose a Food Category</h2>
@endsection
@section('content')
	<div class="card-group">
	@foreach ($foodcategories as $category)
		<div class="card category">
			<a class="card-food-link" href="/food/{{$category->id}}">
				<img class="card-img-top" src="{{asset('images/foodImages/categories').'/category-'.$category->category_name}}.jpg" alt="a fresh {{$category->category_name}} in a container">
				<div class="image-link-overlay"></div>
			</a>	
			<a class="card-body button-custom-gradient" href="/food/{{$category->id}}">
				<p class="card-text">
				 @if($category->category_name == "sandwich") {{$category->category_name }}es
				 @else {{ $category->category_name }}s
				 @endif
				</p>
		 	</a>
		</div>
	@endforeach
	</div>
@endsection
