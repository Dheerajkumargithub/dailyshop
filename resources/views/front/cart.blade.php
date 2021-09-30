@extends('front/layout')
@section('page_title', 'Cart Page')
@section('container')

 <!-- Cart view section -->
 <section id="cart-view">
   <div class="container">
     <div class="row">
       <div class="col-md-12">
         <div class="cart-view-area">
           <div class="cart-view-table">
             <form action="">
              @if(isset($list[0]))
               <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Remove</th>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($list as $data)
                      <tr id="cart_box{{$data->attrid}}">
                        <td>
                          <a class="remove" href="javascript:void(0)" onclick="deleteCartProduct('{{$data->pid}}','{{$data->size}}','{{$data->color}}','{{$data->attrid}}')"><fa class="fa fa-close"></fa>
                          </a>
                          </td>
                        <td>
                          <a href="{{asset('storage/media/'.$data->image)}}" target="_blank"><img src="{{asset('storage/media/'.$data->image)}}" alt="img" >
                          </a>
                        </td>
                        <td>
                          <a class="aa-cart-title" href="{{'product/'.$data->slug}}">{{$data->name}}</a>
                          @if($data->size!="")
                           <br/>SIZE : {{$data->size}}
                          @endif
                          @if($data->color!="")
                           <br/>SIZE : {{$data->color}}
                          @endif
                        </td>
                        <td>Rs {{$data->price}}</td>
                        <td><input id="qty{{$data->attrid}}" class="aa-cart-quantity" type="number" value="{{$data->qty}}" onchange="updateQty('{{$data->pid}}','{{$data->size}}','{{$data->color}}','{{$data->attrid}}','{{$data->price}}')"></td>
                        <td id="total_price_{{$data->attrid}}">Rs {{$data->price*$data->qty}}</td>
                      </tr>
                      @endforeach
                      <tr>
                        <td colspan="6" class="aa-cart-view-bottom">
                          <div class="aa-cart-coupon">
                            <input class="aa-coupon-code" type="text" placeholder="Coupon">
                            <input class="aa-cart-view-btn" type="submit" value="Apply Coupon">
                          </div>
                          <a class="" href="{{url('/checkout')}}"><input class="aa-cart-view-btn" type="button" value="Check Out"></a>                         
                        </td>
                      </tr>
                      </tbody>
                  </table>
                </div>
                @else
                <h3>Cart Empty</h3>
                @endif
             </form>
           </div>
         </div>
       </div>
     </div>
   </div>
 </section>

  <section id="aa-subscribe">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="aa-subscribe-area">
            
          </div>
        </div>
      </div>
    </div>
  </section>
<input type="hidden" value="1" id="qty">
  <form id="frmAddToCart">
    @csrf
    <input type="hidden" name="size_id" id="size_id">
    <input type="hidden" name="color_id" id="color_id">
    <input type="hidden" name="pqty" id="pqty">
    <input type="hidden" name="product_id" id="product_id">
  </form>
@endsection