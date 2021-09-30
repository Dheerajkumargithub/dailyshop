@extends('admin/layout');
@section('page_title', 'Manage Category')
@section('category_select', 'active')
@section('container')

<h1 class="text-info text-center">Manage Category</h1>
<a href="{{url('admin/category')}}">
    <button class="btn btn-success">Back</button>
</a>

@error('category_image')
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
                    <form action="{{route('category.manage_category_process')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="category_name" class="control-label mb-1">Category Name</label>
                                    <input id="category_name" name="category_name" type="text" class="form-control" value="{{$category_name}}" placeholder="Enter Category">
                                    @error('category_name')
                                    <p class="alert alert-danger text-danger">
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="category_slug" class="control-label mb-1">Category Slug</label>
                                    <input id="category_slug" name="category_slug" type="text" class="form-control" value="{{$category_slug}}"  placeholder="Enter Category Slug">
                                    @error('category_slug')
                                    <p class="alert alert-danger text-danger">
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="parent_category_id" class="control-label mb-1">Parent Category</label>
                                    <select id="parent_category_id" name="parent_category_id" type="text" class="form-control" aria-required="true">
                                        <option value="0">Select Category</option>
                                    @foreach($category as $list)
                                    @if($parent_category_id == $list->id)
                                        <option value="{{$list->id}}" selected>
                                    @else
                                        <option value="{{$list->id}}">
                                    @endif
                                        {{$list->category_name}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="category_image" class="control-label mb-1">Category Image</label>
                                    <input id="category_image" name="category_image" type="file" class="form-control">
                                    @if($category_image != "")
                                        <img src="{{asset('storage/media/category/'.$category_image)}}" width="100"> 
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <label for="is_home" >Show In a home page  
                                </label>&nbsp
                                <input id="is_home" name="is_home" type="checkbox" {{$is_home_selected}}>
                                    
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
@endsection