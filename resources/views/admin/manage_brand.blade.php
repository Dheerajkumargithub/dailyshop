@extends('admin/layout');
@section('page_title', 'Manage Brand')
@section('brand_select', 'active')
@section('container')

@if($id>0)
    {{$image_required=""}}
@else
    {{$image_required="required"}}
@endif

<h1 class="text-info text-center">Manage Brand</h1>
<a href="{{url('admin/brand')}}">
	<button class="btn btn-success">Back</button>
</a>


@error('image')
<div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
    <span class="badge badge-pill badge-warning">Failed</span>
        {{$message}}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
@enderror

<div class="row m-t-30">
    <div class="col-md-8 mx-auto">
    <!-- DATA TABLE-->
        <div class="table-responsive m-b-40">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('brand.manage_brand_process')}}" method="post" enctype="multipart/form-data" >
                        @csrf
                        <div class="form-group">
                            <label for="name" class="control-label mb-1">Brand</label>
                            <input id="name" name="name" type="text" class="form-control" value="{{$name}}" placeholder="Enter Color">
                            @error('name')
                            <p class="alert alert-danger text-danger">
                                {{$message}}
                            </p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="image" class="control-label mb-1">Image</label>
                            <input id="image" name="image" type="file" class="form-control" {{$image_required}}>
                            @if($image != "")
                                <img src="{{asset('storage/media/brand/'.$image)}}" width="100"> 
                                @endif
                            @error('image')
                            <p class="alert alert-danger text-danger">
                                {{$message}}
                            </p>
                            @enderror
                        </div>
                        <div class="col-sm-8">
                                <label for="is_home" >Show In a home page  
                                </label>&nbsp
                                <input id="is_home" name="is_home" type="checkbox" {{$is_home_selected}}>
                                    
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