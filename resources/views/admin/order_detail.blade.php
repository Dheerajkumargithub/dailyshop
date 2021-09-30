@extends('admin/layout');
@section('page_title', 'Order Detail')
@section('tax_select', 'active')
@section('container')


<h1 class="text-info text-center">Order Details</h1>&nbsp
<!--  change status -->
<section id="cart-view">
  <div class="container bg-white py-2">
    <div class="row">
      <div class="col-md-6">
        <h4 class="text-info my-2">Update Order Status</h4>
        <select class="form-control" id="order_status" onchange="update_order_status('{{$orders_details[0]->id}}')">
          <?php 
          foreach ($orders_status as $list) {
            if ($orders_details[0]->order_status==$list->id) {
              ?><option value="<?php echo $list->id; ?>" selected><?php echo $list->orders_status; ?></option><?php
            }else{
              ?><option value="<?php echo $list->id; ?>"><?php echo $list->orders_status; ?></option><?php
            }
            
          }
           ?>
        </select>
      </div>
      <div class="col-md-6">
        <h4 class="text-info my-2">Update Payment Status</h4>
        <select class="form-control" id="payment_status" onchange="update_payment_status('{{$orders_details[0]->id}}')">
          <?php 
          foreach ($payment_status as $list) {

            if ($orders_details[0]->payment_status==$list) {
              ?><option value="<?php echo $list; ?>" selected><?php echo $list; ?></option><?php
              /*echo "<option="."$list"." selected>".$list."</option>";*/
            }else{
              ?><option value="<?php echo $list; ?>"><?php echo $list; ?></option><?PHP
            }
            
          }

           ?>
        </select>
      </div>
    </div>
  </div>
</section>
&nbsp
<section id="cart-view">
  <div class="container bg-white py-2">
    <form method="post" action="{{url('admin/update_track_details')}}/{{$orders_details[0]->id}}">
      @csrf
      <div class="row">
          <div class="col-md-6">
            <h4 class="text-info my-2">Track Detail</h4>
            <textarea name="track_details" class="form-control">{{$orders_details[0]->track_details}}</textarea>
          </div>
          <div class="col-md-6">
            <br/><br/>
            <input type="submit" name="submit" class="form-control btn btn-outline-success w-50" value="Submit">
          </div>
      </div>
    </form>
  </div>
</section>
&nbsp
                      
<section id="cart-view">
   <div class="container bg-white py-2">
     <div class="row ">
      <!--  View details --> 
      <div class="col-md-6">
        <table cellpadding="10px" width="80%">
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

    <!--  Product details --> 
       <div class="col-md-12 py-2 bg-white">
         <div class="cart-view-area">
           <div class="cart-view-table">
             <form action="">
               <div class="table-responsive">
                  <table width="100%" cellpadding="30px" cellspacing="10px"  class="text-center">
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
                         <td><img src="{{asset('storage/media/'.$list->attr_image)}}" width="70" height="70"></td>
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
                               <td style="font-weight:bold">Total Ammount : </td>
                               <td style="font-weight:bold">'.$totalAmt.'</td>
                            </tr>';
                          if ($orders_details[0]->coupon_value>0) {
                            echo '<tr class="txt_style">
                               <td colspan="5">&nbsp</td>
                               <td style="font-weight:bold">Coupon <span class="text-danger">('.$orders_details[0]->coupon_code.')</span>:</td>
                               <td style="font-weight:bold">'.$orders_details[0]->coupon_value.'</td>
                            </tr>';
                            $coupon_value = $orders_details[0]->coupon_value;
                           $totalAmt = $totalAmt-$coupon_value;
                           echo '<tr class="txt_style">
                               <td colspan="5">&nbsp</td>
                               <td style="font-weight:bold">Final Ammount : </td>
                               <td style="font-weight:bold">'.$totalAmt.'</td>
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

@endsection