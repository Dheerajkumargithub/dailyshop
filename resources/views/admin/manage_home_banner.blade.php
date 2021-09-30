@extends('admin/layout');
@section('page_title', 'Manage Home Banner')
@section('home_banner_select', 'active')
@section('container')

<h1 class="text-info text-center">Manage Home Banner</h1>
<a href="{{url('admin/home_banner')}}">
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
                    <form action="{{route('home_banner.manage_home_banner_process')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="btn_txt" class="control-label mb-1">btn Text</label>
                                    <input id="btn_txt" name="btn_txt" type="text" class="form-control" value="{{$btn_txt}}" placeholder="Enter btn_txt">
                                    @error('btn_txt')
                                    <p class="alert alert-danger text-danger">
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="btn_link" class="control-label mb-1"> btn Link</label>
                                    <input id="btn_link" name="btn_link" type="text" class="form-control" value="{{$btn_link}}"  placeholder="Enter btn Link">
                                    @error('btn_link')
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
                                    <label for="title" class="control-label mb-1">Title</label>
                                    <input id="title" name="title" type="text" class="form-control" value="{{$title}}"  placeholder="Enter Title">
                                    @error('title')
                                    <p class="alert alert-danger text-danger">
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="description" class="control-label mb-1">Description</label>
                                    <input id="description" name="description" type="text" class="form-control" value="{{$description}}"  placeholder="Enter Description">
                                    @error('description')
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
                                    <label for="image" class="control-label mb-1"> Image</label>
                                    <input id="image" name="image" type="file" class="form-control">
                                    @if($image != "")
                                        <img src="{{asset('storage/media/banner/'.$image)}}" width="100"> 
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    <label for="" class="control-label mb-1">&nbsp</label>
                                    <button type="submit" class="btn btn-lg btn-info btn-block">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="{{$id}}">
                    </form>
                </div>
            </div>
        </div>
        <!-- END DATA TABLE-->
    </div>
@endsection