@extends('admin/layout');
@section('page_title', 'Manage Color')
@section('color_select', 'active')
@section('container')

<h1 class="text-info text-center">Manage Color</h1>
<a href="{{url('admin/color')}}">
	<button class="btn btn-success">Back</button>
</a>
                        
<div class="row m-t-30">
    <div class="col-md-8 mx-auto">
    <!-- DATA TABLE-->
        <div class="table-responsive m-b-40">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('color.manage_color_process')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="color" class="control-label mb-1">Color</label>
                            <input id="color" name="color" type="text" class="form-control" value="{{$color}}" placeholder="Enter Color">
                            @error('color')
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