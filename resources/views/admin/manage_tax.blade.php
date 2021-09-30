@extends('admin/layout');
@section('page_title', 'Manage Tax')
@section('tax_select', 'active')
@section('container')

<h1 class="text-info text-center">Manage Tax</h1>
<a href="{{url('admin/tax')}}">
	<button class="btn btn-success">Back</button>
</a>
                        
<div class="row m-t-30">
    <div class="col-md-8 mx-auto">
    <!-- DATA TABLE-->
        <div class="table-responsive m-b-40">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('tax.manage_tax_process')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="tax_value" class="control-label mb-1">Tax Value</label>
                            <input id="tax_value" name="tax_value" type="text" class="form-control" value="{{$tax_value}}" placeholder="Enter Tax Value">
                            @error('tax_value')
                            <p class="alert alert-danger text-danger">
                                {{$message}}
                            </p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tax_desc" class="control-label mb-1">Tax Desc</label>
                            <input id="tax_desc" name="tax_desc" type="text" class="form-control" value="{{$tax_desc}}" placeholder="Enter Tax Desc">
                            @error('tax_desc')
                            <p class="alert alert-danger text-danger">
                                {{$message}}
                            </p>
                            @enderror
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