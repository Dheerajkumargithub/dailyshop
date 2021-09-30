@extends('front/layout')
@section('page_title','Order Placed')
@section('container')

<div>
	&nbsp
</div>
<div class="container" style="padding-top:40px; padding-bottom: 40px;">
	<div class="text-center py-4 my-4">
		<h2 class="my-4">Your order has been placed</h2>
		<h2 class="my-4">Order ID :- {{session()->get('ORDER_ID')}} </h2>
		<h3 class="text-success text-bold">Thank you</h3>
	</div>
</div>

@endsection