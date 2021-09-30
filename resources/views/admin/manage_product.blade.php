@extends('admin/layout');
@section('page_title', 'Manage Product')
@section('product_select', 'active')
@section('container')

@if($id>0)
    {{$image_required=""}}
@else
    {{$image_required="required"}}
@endif

<h1 class="text-info text-center">Manage Product</h1>
<a href="{{url('admin/product')}}">
	<button class="btn btn-success">Back</button>
</a>

@if(session()->has('sku_error'))
<div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
    <span class="badge badge-pill badge-warning">Failed</span>
        {{session('sku_error')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
</div>
@endif

@error('attr_image.*')
<div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
    <span class="badge badge-pill badge-warning">Failed</span>
        {{$message}}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
@enderror

@error('images.*')
<div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
    <span class="badge badge-pill badge-warning">Failed</span>
        {{$message}}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
@enderror

<div class="row m-t-30">
    <div class="col-md-10 mx-auto">
    <!-- DATA TABLE-->
    <form action="{{route('product.manage_product_process')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="table-responsive m-b-40">
            <div class="card">
                <div class="card-body">
                    
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="name" class="control-label mb-1">Name</label>
                                    <input id="name" name="name" type="text" class="form-control" value="{{$name}}" placeholder="Enter Product">
                                    @error('name')
                                    <p class="alert alert-danger text-danger">
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="slug" class="control-label mb-1">Slug</label>
                                    <input id="slug" name="slug" type="text" class="form-control" value="{{$slug}}"  placeholder="Enter Product Slug">
                                    @error('slug')
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
                                    <label for="image" class="control-label mb-1">Image</label>
                                    <input id="image" name="image" type="file" class="form-control" {{$image_required}}>
                                    @if($image != "")
                                        <img src="{{asset('storage/media/'.$image)}}" width="100"> 
                                    @endif
                                    @error('image')
                                    <p class="alert alert-danger text-danger">
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="category_id" class="control-label mb-1">Category</label>
                                        <select id="category_id" name="category_id" type="text" class="form-control" aria-required="true">
                                            <option value="">Select Category</option>
                                            @foreach($category as $list)
                                                @if($category_id == $list->id)
                                                <option value="{{$list->id}}" selected>
                                                @else
                                                <option value="{{$list->id}}">
                                                @endif
                                                {{$list->category_name}}</option>
                                            @endforeach
                                        </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="brand" class="control-label mb-1">Brand </label>
                                    <select id="brand" name="brand" class="form-control" aria-required="true">
                                        <option value="">Select Brand</option>
                                        @foreach($brand as $list)
                                            @if($brands == $list->id)
                                            <option value="{{$list->id}}" selected>
                                            @else
                                            <option value="{{$list->id}}">
                                            @endif
                                            {{$list->name}}</option>
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="col-sm-6">
                                    <label for="model" class="control-label mb-1">Model</label>
                                    <input id="model" name="model" type="text" class="form-control" placeholder="Enter Model" value="{{$model}}">
                                    @error('model')
                                    <p class="alert alert-danger text-danger">
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="short_desc" class="control-label mb-1">Short Description</label>
                            <textarea id="short_desc" name="short_desc" type="text" class="form-control">{{$short_desc}}</textarea>
                            @error('short_desc')
                            <p class="alert alert-danger text-danger">
                                {{$message}}
                            </p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="desc" class="control-label mb-1">Description</label>
                            <textarea id="desc" name="desc" type="text" class="form-control">{{$desc}}</textarea>
                            @error('desc')
                            <p class="alert alert-danger text-danger">
                                {{$message}}
                            </p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="keywords" class="control-label mb-1">Keywords</label>
                            <textarea id="keywords" name="keywords" type="text" class="form-control">{{$keywords}}</textarea>
                            @error('keywords')
                            <p class="alert alert-danger text-danger">
                                {{$message}}
                            </p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="technical_specification" class="control-label mb-1">Technical Specification</label>
                            <textarea id="technical_specification" name="technical_specification" type="text" class="form-control">{{$technical_specification}}</textarea>
                            @error('technical_specification')
                            <p class="alert alert-danger text-danger">
                                {{$message}}
                            </p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="uses" class="control-label mb-1">Uses</label>
                            <textarea id="uses" name="uses" type="text" class="form-control">{{$uses}}</textarea>
                            @error('uses')
                            <p class="alert alert-danger text-danger">
                                {{$message}}
                            </p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="warrenty" class="control-label mb-1">Warrenty</label>
                            <textarea id="warrenty" name="warrenty" type="text" class="form-control" aria-required="true">{{$warrenty}}</textarea>
                            @error('warrenty')
                            <p class="alert alert-danger text-danger">
                                {{$message}}
                            </p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="lead_time" class="control-label mb-1">Lead Time</label>
                                    <input id="lead_time" name="lead_time" type="text" class="form-control" value="{{$lead_time}}" placeholder="Enter Lead Time"> 
                                </div>
                                <div class="col-sm-4">
                                    <label for="tax_id" class="control-label mb-1">Tax</label>
                                    <select id="tax_id" name="tax_id" class="form-control" aria-required="true">
                                        <option value="">Select Tax</option>
                                        @foreach($taxes as $list)
                                            @if($tax_id == $list->id)
                                            <option value="{{$list->id}}" selected>
                                            @else
                                            <option value="{{$list->id}}">
                                            @endif
                                            {{$list->tax_desc}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="is_promo" class="control-label mb-1">IS Promo</label>
                                    <select id="is_promo" name="is_promo" class="form-control" aria-required="true">
                                        @if($is_promo == '1')
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
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="is_featured" class="control-label mb-1">IS Featured</label>
                                    <select id="is_featured" name="is_featured" class="form-control" aria-required="true">
                                        @if($is_featured == '1')
                                            <option value="1" selected>Yes</option>
                                            <option value="0">No</option>
                                        @else
                                            <option value="1" >Yes</option>
                                            <option value="0" selected>No</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="is_tranding" class="control-label mb-1">IS Tranding</label>
                                    <select id="is_tranding" name="is_tranding" class="form-control" aria-required="true">
                                        @if($is_tranding == '1')
                                            <option value="1" selected>Yes</option>
                                            <option value="0">No</option>
                                        @else
                                            <option value="1">Yes</option>
                                            <option value="0" selected>No</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="is_discount" class="control-label mb-1">IS Discount</label>
                                    <select id="is_discount" name="is_discount" class="form-control" aria-required="true">
                                        @if($is_discount == '1')
                                            <option value="1" selected>Yes</option>
                                            <option value="0">No</option>
                                        @else
                                            <option value="1">Yes</option>
                                            <option value="0" selected>No</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <!-- END DATA TABLE-->
        <h2 class="text-center text-info">Product Images</h2>
        <div class="table-responsive m-b-40">
            @php
                $loop_count_num=1;
            @endphp

            
            
            <div class="card" >
                <div class="card-body">
                        <div class="form-group">
                            <div class="row" class="" id="product_images_box">
                            @foreach($productImagesArr as $key=>$val)

                            @php
                                $loop_count_prev=$loop_count_num;
                                $imgsArr = (array)$val;
                            @endphp
                            <input type="hidden" name="pImgAttrid[]" value="{{$imgsArr['id']}}">
                                <div class="col-sm-4 product_images_{{$loop_count_num++}}" >
                                    <label for="images" class="control-label mb-1">Image</label>
                                    <input id="images" name="images[]" type="file" class="form-control" >
                                    @if($imgsArr['images'] != "")
                                        <a href="{{asset('storage/media/'.$imgsArr['images'])}}" target="_blank">
                                            <img src="{{asset('storage/media/'.$imgsArr['images'])}}" width="100%">
                                        </a> 
                                    @endif
                                </div>
                                <div class="col-sm-2 pt-4">
                                    @if($loop_count_num==2)
                                    <button type="button" class="btn btn-sm py-2 btn-info btn-block" onclick="add_image_more()" >
                                        <label class="fas fa-plus"></label> Add Image
                                    </button>
                                    @else
                                    <a href="{{url('admin/product/product_images_delete')}}/{{$imgsArr['id']}}/{{$id}}">
                                        <button type="button" class="btn btn-sm py-2 btn-danger btn-block">
                                            <label class="fas fa-minus"></label> Remove
                                        </button>
                                    </a>
                                    @endif

                                    <input type="hidden" name="id" value="{{$id}}">
                                </div>
                            @endforeach
                            </div>
                        </div>
                    
                </div>
            </div>
        </div>

        <h2 class="text-center text-info">Product Attributes</h2>
        <div class="table-responsive m-b-40" id="product_attr_box">
            @php
                $loop_count_num=1;
            @endphp

            @foreach($productAttrArr as $key=>$val)

            @php
                $loop_count_prev=$loop_count_num;
                $valArr = (array)$val;
            @endphp
            <input type="hidden" name="pAttrid[]" value="{{$valArr['id']}}">
            <div class="card" id="product_attr_{{$loop_count_num++}}">
                <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="sku" class="control-label mb-1">SKU</label>
                                    <input id="sku" name="sku[]" type="text" class="form-control" placeholder="Enter SKU" value="{{$valArr['sku']}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="mrp" class="control-label mb-1">MRP</label>
                                    <input id="mrp" name="mrp[]" type="text" class="form-control"  placeholder="Enter Product MRP" value="{{$valArr['mrp']}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="price" class="control-label mb-1">Price</label>
                                    <input id="price" name="price[]" type="text" class="form-control" placeholder="Enter Price" value="{{$valArr['price']}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="size_id" class="control-label mb-1">Size</label>
                                        <select id="size_id" name="size_id[]" type="text" class="form-control" aria-required="true">
                                            <option value="">Select Size</option>
                                            @foreach($size as $list)
                                            @if($valArr['size_id']==$list->id)
                                                <option value="{{$list->id}}" selected>   {{$list->size}}</option>
                                                @else
                                                <option value="{{$list->id}}">   {{$list->size}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="color_id" class="control-label mb-1">Color</label>
                                    <select id="color_id" name="color_id[]" type="text" class="form-control" aria-required="true">
                                        <option value="">Select Color</option>
                                            @foreach($color as $list)
                                            @if($valArr['color_id']==$list->id)
                                                <option value="{{$list->id}}" selected>   {{$list->color}}</option>
                                                @else
                                                <option value="{{$list->id}}">   {{$list->color}}</option>
                                                @endif
                                            @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="qty" class="control-label mb-1">Qty</label>
                                    <input id="qty" name="qty[]" type="text" class="form-control" placeholder="Enter Qty" value="{{$valArr['qty']}}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="attr_image" class="control-label mb-1">Image</label>
                                    <input id="attr_image" name="attr_image[]" type="file" class="form-control" >
                                    @if($valArr['attr_image'] != "")
                                        <img src="{{asset('storage/media/'.$valArr['attr_image'])}}" width="100"> 
                                    @endif
                                </div>
                                <div class="col-sm-4 pt-4">
                                    @if($loop_count_num==2)
                                    <button type="button" class="btn btn-sm py-2 btn-info btn-block" onclick="add_more()" >
                                        <label class="fas fa-plus"></label> Add 
                                    </button>
                                    @else
                                    <a href="{{url('admin/product/product_attr_delete')}}/{{$valArr['id']}}/{{$id}}">
                                        <button type="button" class="btn btn-sm py-2 btn-danger btn-block">
                                            <label class="fas fa-minus"></label> Remove 
                                        </button>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="{{$id}}">
                    
                </div>
            </div>
            @endforeach
        </div>
        <div class="form-group mx-auto w-50">
            <button type="submit" class="btn btn-lg btn-info btn-block">
                Submit
            </button>
        </div>
    </form>
    </div>
</div>

<script type="text/javascript">
    var loop_count = 1;
    function add_more(){
        loop_count++;
       var html = '<input type="hidden" name="pAttrid[]"><div class="card" id="product_attr_'+loop_count+'"><div class="card-body"><div class="form-group"><div class="row">';

       html += '<div class="col-sm-4"><label for="sku" class="control-label mb-1">SKU</label><input id="sku" name="sku[]" type="text" class="form-control" placeholder="Enter SKU">@error('sku')<p class="alert alert-danger text-danger">{{$message}}</p>@enderror</div>';

       html += '<div class="col-sm-4"><label for="mrp" class="control-label mb-1">MRP</label><input id="mrp" name="mrp[]" type="text" class="form-control"  placeholder="Enter Product MRP">@error('mrp')<p class="alert alert-danger text-danger"> {{$message}}</p> @enderror</div>';

       html += '<div class="col-sm-4"><label for="price" class="control-label mb-1">Price</label><input id="price" name="price[]" type="text" class="form-control" placeholder="Enter Price" >@error('price')<p class="alert alert-danger text-danger">{{$message}}</p>@enderror</div>';

       var size_id_html = jQuery('#size_id').html();
       size_id_html = size_id_html.replace("selected","");
       html += '<div class="col-sm-4"><label for="size_id" class="control-label mb-1">Size</label><select id="size_id" name="size_id[]" type="text" class="form-control" aria-required="true">'+size_id_html+'</select>@error('size')<p class="alert alert-danger text-danger"> {{$message}}</p> @enderror</div>';
       
       var color_id_html = jQuery('#color_id').html();
       color_id_html = color_id_html.replace("selected","");
       html+= '<div class="col-sm-4"><label for="color_id" class="control-label mb-1">Color</label><select id="color_id" name="color_id[]" type="text" class="form-control" aria-required="true">'+color_id_html+'</select></div>';

       html += '<div class="col-sm-4"><label for="qty" class="control-label mb-1">Qty</label><input id="qty" name="qty[]" type="text" class="form-control" placeholder="Enter Qty" ></div>';

       html += '<div class="col-sm-4"><label for="attr_image" class="control-label mb-1">Image</label><input id="attr_image" name="attr_image[]" type="file" class="form-control" ></div>';

       html += '<div class="col-sm-4 pt-4"><button type="button" class="btn btn-sm py-2 btn-danger btn-block" onclick="remove_more('+loop_count+')" ><label class="fas fa-minus"></label> Remove </button></div>';

       html+='</div></div></div></div>';
        jQuery('#product_attr_box').append(html);
    }
    function remove_more(loop_count){
        jQuery('#product_attr_'+loop_count).remove();
    }


    var loop_image_count = 1;
    function add_image_more(){
        loop_image_count++;

        var html = '<input type="hidden" name="pImgAttrid[]" value=""><div class="col-sm-4 product_images_'+loop_image_count+'"><label for="images" class="control-label mb-1">Images</label><input id="images" name="images[]" type="file" class="form-control" ></div>';

        html += '<div class="col-sm-2 pt-3 product_images_'+loop_image_count+'"><button type="button" class="btn btn-sm py-2 btn-danger btn-block" onclick="remove_image_more('+loop_image_count+')" ><label class="fas fa-minus"></label> Remove </button></div>';

        jQuery('#product_images_box').append(html);
    }

    function remove_image_more(loop_image_count){
        jQuery('.product_images_'+loop_image_count).remove();
    }


</script>
@endsection