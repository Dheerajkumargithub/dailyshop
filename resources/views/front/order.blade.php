@extends('front/layout')
@section('page_title', 'Orders')
@section('container')

 <!-- Cart view section -->
 <section id="cart-view">
   <div class="container">
     <div class="row">
       <div class="col-md-12">
         <div class="cart-view-area">
           <div class="cart-view-table">
             <form action="">
               <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Order ID</th>
                        <th>Order Status</th>
                        <th>Payment Type</th>
                        <th>Payment Status</th>
                        <th>Payment ID</th>
                        <th>Total Amt</th>
                        <th>Placed At</th>
                        <th>Details</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $list)
                      <tr>
                        <td>
                          <a href="{{url('order_detail')}}/{{$list->id}}">{{$list->id}}</a>
                        </td>
                        <td>{{$list->orders_status}}</td>
                        <td>{{$list->payment_type}}</td>
                        <td>{{$list->payment_status}}</td>
                        <td>{{$list->payment_id}}</td>
                        <td>{{$list->total_amt}}</td>
                        <td>{{$list->created_at}}</td>
                        <td><a href="{{url('order_detail')}}/{{$list->id}}"><input class="btn btn-success" type="button" value="Check"></a></td>
                      </tr>
                        @endforeach
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