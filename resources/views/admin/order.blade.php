@extends('admin/layout');
@section('page_title', 'Order')
@section('tax_select', 'active')
@section('container')


<h1 class="text-info text-center">Order</h1>
                        
<div class="row m-t-30">
    <div class="col-md-12">
    <!-- DATA TABLE-->
        <div class="table-responsive m-b-40">
            <table class="table table-borderless table-data3">
                <thead>
                	<tr>
                        <th>Order ID</th>
                        <th>Customer Details</th>
                        <th>Total Amt</th>
                        <th>Order Status</th>
                        <th>Payment Status</th>
                        <th>Payment Type</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $orders as $list)
                    <tr>
                        <td><button class="btn btn-outline-info"><a href="{{url('admin/order_detail')}}/{{$list->id}}">{{$list->id}}</a></button></td>
                        <td>
                            {{$list->name}}<br/>
                            {{$list->email}}<br/>
                            {{$list->mobile}}<br/>
                            {{$list->address}}, {{$list->city}}, {{$list->state}}, {{$list->pincode}},
                        </td>
                        <td>{{$list->total_amt}}</td>
                        <td>{{$list->orders_status}}</td>
                        <td>{{$list->payment_status}}</td>
                        <td>{{$list->payment_type}}</td>
                        <td>{{$list->created_at}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- END DATA TABLE-->
    </div>
</div>

@endsection