@extends('front/layout')
@section('page_title', 'Order Details')
@section('container')

 <!-- Cart view section -->
 <section id="cart-view">
   <div class="container">
     <div class="row">
      <div class="col-md-6">
        <table cellpadding="10px" width="60%">
            <thead>
              <thead><h3 class="text-danger">Address Details</h3></thead>
            </thead>
            <tbody>
              <tr>
                <td class="orders_detail">Name (Mobile) : </td>
                <td>{{$orders_details[0]->name}}({{$orders_details[0]->mobile}})</td>
              </tr>
              <tr>
                <td class="orders_detail">Address : </td>
                <td>{{$orders_details[0]->address}}</td>
              </tr>
              <tr>
                <td class="orders_detail">City : </td>
                <td>{{$orders_details[0]->city}}</td>
              </tr>
              <tr>
                <td class="orders_detail">State : </td>
                <td>{{$orders_details[0]->state}}</td>
              </tr>
              <tr>
                <td class="orders_detail">Zip : </td>
                <td>{{$orders_details[0]->pincode}}</td>
              </tr>
            </tbody>
          </table>
      </div>
      <div class="col-md-6">
          <table cellpadding="10px" width="80%">
            <thead>
              <thead><h3 class="text-danger">Orders Detail</h3></thead>
            </thead>
            <tbody>
              <tr>
                <td class="orders_detail">Order Status : </td>
                <td>{{$orders_details[0]->orders_status}}</td>
              </tr>
              <tr>
                <td class="orders_detail">Payment Status : </td>
                <td>{{$orders_details[0]->payment_status}}</td>
              </tr>
              <tr>
                <td class="orders_detail">Payment Type : </td>
                <td>{{$orders_details[0]->payment_type}}</td>
              </tr>
              <?php 
              if ($orders_details[0]->payment_id!='') {
              echo "<tr>
                  <td class='orders_detail'>Payment ID : </td>
                  <td>".$orders_details[0]->payment_id."</td>
                </tr>";
              }
              ?>
            </tbody>
          </table> 
      </div>
       <div class="col-md-12">
         <div class="cart-view-area">
           <div class="cart-view-table">
             <form action="">
               <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Size</th>
                        <th>Color</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php
                      $totalAmt = '';
                      $totalAmt = $orders_details[0]->total_amt;
                      @endphp
                       @foreach($orders_details as $list)
                       <tr>
                         <td><img src="{{asset('storage/media/'.$list->attr_image)}}"></td>
                         <td>{{$list->pname}}</td>
                         <td>{{$list->size}}</td>
                         <td>{{$list->color}}</td>
                         <td>{{$list->price}}</td>
                         <td>{{$list->qty}}</td>
                         <td>{{$list->price*$list->qty}}</td>
                       </tr>
                       @endforeach
                       <?php
                       echo '<tr class="txt_style">
                               <td colspan="5">&nbsp</td>
                               <td>Final Ammount : </td>
                               <td>'.$totalAmt.'</td>
                            </tr>'; 
                          if ($orders_details[0]->coupon_value>0) {
                            echo '<tr class="txt_style">
                               <td colspan="5">&nbsp</td>
                               <td>Coupon <span class="apply_coupon_txt">('.$orders_details[0]->coupon_code.')</span></td>
                               <td>'.$orders_details[0]->coupon_value.'</td>
                            </tr>';
                            $coupon_value = $orders_details[0]->coupon_value;
                           $totalAmt = $totalAmt-$coupon_value;
                           echo '<tr class="txt_style">
                               <td colspan="5">&nbsp</td>
                               <td>Final Ammount : </td>
                               <td>'.$totalAmt.'</td>
                            </tr>';
                          }

                        ?>
                    </tbody>
                  </table>
                </div>
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

@endsection