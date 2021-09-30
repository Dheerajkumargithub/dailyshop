@extends('front/layout')
@section('page_title', 'Checkout Page')
@section('container')

 <section id="checkout">
   <div class="container">
     <div class="row">
       <div class="col-md-12">
        <div class="checkout-area">
          <form id="frmPlaceOrder">
            <div class="row">
              <div class="col-md-8">
                <div class="checkout-left">
                  <div class="panel-group" id="accordion">
                    
                    <!-- Login section -->
                    @if(session()->has('FRONT_USER_LOGIN')==null)
                    <input type="button" value="Login" name="" class="aa-browse-btn" data-toggle="modal" data-target="#login-modal" >
                    <br/><br/>
                    Or
                    <br/><br/>
                    @endif
                    <!-- Shipping Address -->
                    <div class="panel panel-default aa-checkout-billaddress">
                      <div class="panel-heading">
                        <h4 class="panel-title">
                          <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                            User Details Address
                          </a>
                        </h4>
                      </div>
                      <div id="collapseFour" class="panel-collapse ">
                        <div class="panel-body">
                         <div class="row">
                            <div class="col-md-4">
                              <div class="aa-checkout-single-bill">
                                <input type="text" placeholder="Name*" name="name" value="{{$customers['name']}}" required>
                              </div>                             
                            </div>
                            <div class="col-md-4">
                              <div class="aa-checkout-single-bill">
                                <input type="email" placeholder="Email Address*" name="email" value="{{$customers['email']}}" required>
                              </div>                             
                            </div>
                            <div class="col-md-4">
                              <div class="aa-checkout-single-bill">
                                <input type="tel" placeholder="Phone*" name="mobile" value="{{$customers['mobile']}}" required>
                              </div>
                            </div>
                          </div>  
                          <div class="row">
                            <div class="col-md-12">
                              <div class="aa-checkout-single-bill">
                                <textarea cols="8" rows="3" name="address" required>{{$customers['address']}} </textarea>
                              </div>                             
                            </div>                            
                          </div>    
                          <div class="row">
                            <div class="col-md-4">
                              <div class="aa-checkout-single-bill">
                                <input type="text" placeholder="City / Town*" name="city" value="{{$customers['city']}}" required>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="aa-checkout-single-bill">
                                <input type="text" placeholder="State*" name="state" value="{{$customers['state']}}" required>
                              </div>                             
                            </div>
                            <div class="col-md-4">
                              <div class="aa-checkout-single-bill">
                                <input type="text" placeholder="Postcode / ZIP*" name="zip" value="{{$customers['zip']}}" required>
                              </div>
                            </div>
                          </div>              
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="checkout-right">
                  <h4>Order Summary</h4>
                  <div class="aa-order-summary-area">
                    <table class="table table-responsive">
                      <thead>
                        <tr>
                          <th>Product</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php
                          $totalPrice = 0;
                        @endphp
                        @foreach($cart_data as $list)
                          @php
                            $totalPrice = $totalPrice+($list->price*$list->qty);
                          @endphp
                        <tr>
                          <td>{{$list->name}} <strong> x  {{$list->qty}}</strong><br/>
                          <span class="cart_color">Color : {{$list->color}} &nbsp </span>
                          <span class="cart_size">Size : {{$list->size}}</span>
                          </td>
                          <td>INR {{$list->price*$list->qty}}</td>
                        </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr class="hide show_coupon_box">
                          <th>Coupon Code 
                            <a href="javascript:void(0)" onclick="remove_coupon_code()" class="remove_coupon_code_link">Remove</a>
                          </th>
                          <td id="coupon_code_str"></td>
                        </tr>
                         <tr>
                          <th>Total Amount</th>
                          <td>INR {{$totalPrice}}</td>
                        </tr>
                        <tr class="hide show_coupon_box">
                          <th>Payable Amount</th>
                          <td id="total_price"></td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                  <!--  Coupon code Section  -->
                  <h4>Coupon Code</h4>
                  <div class="aa-payment-method coupon_code">   
                    <input type="text" placeholder="Coupon Code" class="aa-coupon-code coupon-code apply_coupon_code_box" name="coupon_code" id="coupon_code">
                    <input type="button" value="Apply Coupon" class="aa-browse-btn btn_style apply_coupon_code_box" onclick="applyCouponCode()">  
                    <div id="coupon_code_msg"></div>
                  </div>

                  <!--  Payment Getway Section  -->
                  <h4>Payment Method</h4>
                  <div class="aa-payment-method">                    
                    <label for="cod">
                      <input type="radio" id="cod" name="payment_type" value="COD" checked> Cash on Delivery 
                    </label>
                    <label for="instamojo">
                      <input type="radio" id="instamojo" name="payment_type" value="Gateway"> Via Instamojo 
                    </label>    
                    <input type="submit" value="Place Order" class="aa-browse-btn" id="btnPlaceOrder">
                    <p>Use for test payment gateway 
                      Number   : 4242 4242 4242 4242
                      date     : 20/2035
                      CVV      : 111
                      password : 1221
                        </p>
                  </div>
                  <div id="order_place_msg"></div>
                </div>
              </div>
            </div>
            @csrf
          </form>
         </div>
       </div>
     </div>
   </div>
 </section>

@endsection