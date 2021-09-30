@extends('admin/layout');
@section('page_title', 'Manage Coupon')
@section('coupon_select', 'active')
@section('container')

<h1 class="text-info text-center">Manage Coupon</h1>
<a href="{{url('admin/coupon')}}">
	<button class="btn btn-success">Back</button>
</a>
                        
<div class="row m-t-30">
    <div class="col-md-10 mx-auto">
    <!-- DATA TABLE-->
        <div class="table-responsive m-b-40">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('coupon.manage_coupon_process')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="title" class="control-label mb-1">Coupon Title</label>
                                    <input id="title" name="title" type="text" class="form-control" value="{{$title}}" placeholder="Enter Coupon Title">
                                    @error('title')
                                    <p class="alert alert-danger text-danger">
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="code" class="control-label mb-1">Coupon Code</label>
                                    <input id="code" name="code" type="text" class="form-control" value="{{$code}}" placeholder="Enter Coupon Code">
                                    @error('code')
                                    <p class="alert alert-danger text-danger">
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="value" class="control-label mb-1">Coupon Value</label>
                                    <input id="value" name="value" type="text" class="form-control" value="{{$value}}"  placeholder="Enter Coupon Value">
                                    @error('value')
                                    <p class="alert alert-danger text-danger">
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="type" class="control-label mb-1">Type</label>
                                    <select id="type" name="type" class="form-control" aria-required="true">
                                        @if($type == 'Value')
                                            <option value="Value" selected>Value</option>
                                            <option value="Per">Per</option>
                                        @elseif($type == 'Per')
                                            <option value="Value" >Value</option>
                                            <option value="Per" selected>Per</option>
                                        @else
                                            <option value="Value" >Value</option>
                                            <option value="Per" >Per</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="min_order_amt" class="control-label mb-1">Min Order Amount</label>
                                    <input id="title" name="min_order_amt" type="text" class="form-control" value="{{$min_order_amt}}" placeholder="Enter Min Amount">
                                </div>
                                <div class="col-sm-6">
                                    <label for="is_one_time" class="control-label mb-1">IS One Time</label>
                                    <select id="is_one_time" name="is_one_time" class="form-control" aria-required="true">
                                        @if($is_one_time == '1')
                                            <option value="1" selected>Yes</option>
                                            <option value="0">No</option>
                                        @else
                                            <option value="1" >Yes</option>
                                            <option value="0" selected>No</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-lg btn-info btn-block">
                                Submit
                            </button>
                        </div>
                        <input type="hidden" name="id" value="{{$id}}">
                    </form>
                </div>
            </div>
        </div>
        <!-- END DATA TABLE-->
    </div>
</div>

@endsection