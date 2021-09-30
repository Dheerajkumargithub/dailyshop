@extends('front/layout')
@section('page_title',$product[0]->name)
@section('container')

<section id="aa-product-details">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="aa-product-details-area">
            <div class="aa-product-details-content">
              <div class="row">
                <!-- Product detail section -->
                <div class="col-md-5 col-sm-5 col-xs-12">                              
                  <div class="aa-product-view-slider">                                
                    <div id="demo-1" class="simpleLens-gallery-container">
                      <div class="simpleLens-container">
                        <div class="simpleLens-big-image-container" ><a data-lens-image="{{asset('storage/media/'.$product[0]->image)}}" class="simpleLens-lens-image"><img src="{{asset('storage/media/'.$product[0]->image)}}" class="simpleLens-big-image"></a></div>
                      </div>
                      <div class="simpleLens-thumbnails-container">

                        @if(isset($multiple_product_images[$product[0]->id][0]))
                          @foreach($multiple_product_images[$product[0]->id] as $imglist)
                          <a data-big-image="{{asset('storage/media/'.$imglist->images)}}" data-lens-image="{{asset('storage/media/'.$imglist->images)}}" class="simpleLens-thumbnail-wrapper" href="javascript:void(0)">
                            <img src="{{asset('storage/media/'.$imglist->images)}}" width="70px" height="60px">
                          </a>
                          @endforeach
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Product detail name price ,rp model -->
                <div class="col-md-7 col-sm-7 col-xs-12">
                  <div class="aa-product-view-content">
                    <h3>{{$product[0]->name}}</h3>
                    <div class="aa-price-block">
                      <span class="aa-product-view-price">Rs {{$product_attr[$product[0]->id][0]->price}}</span>
                      <span class="aa-product-view-price text-danger"><del>Rs {{$product_attr[$product[0]->id][0]->mrp}}</del></span>
                      <p class="aa-product-avilability">Avilability: <span>In stock</span></p>
                      @if($product[0]->lead_time!='')
                      <p class="lead_time">{{$product[0]->lead_time}}</p>
                      @endif
                    </div>

                    <!-- Getting Product Size -->
                    <p>{!!$product[0]->short_desc!!}</p>

                    <!-- Getting Product Size -->
                    @if($product_attr[$product[0]->id][0]->size_id>0)
                    <h4>Size</h4>
                    <div class="aa-prod-view-size">
                      @php
                      $arrSize=[];
                        foreach($product_attr[$product[0]->id] as $attr){
                          $arrSize[]=$attr->size;
                        }
                        $arrSize=array_unique($arrSize);
                      @endphp

                      @foreach($arrSize as $attr)
                          @if($attr)
                            <a href="javascript:void(0)" onclick="showColor('{{$attr}}')" id="size_{{$attr}}" class="size_link">{{$attr}}</a>
                          @endif
                      @endforeach
                    </div>
                    @endif

                    <!-- Getting Product Color -->
                    @if($product_attr[$product[0]->id][0]->color_id>0)
                    <h4>Color</h4>
                    <div class="aa-color-tag">
                      @if($product_attr[$product[0]->id][0])
                      @foreach($product_attr[$product[0]->id] as $color)
                      
                      <a href="javascript:void(0)" class="aa-color-{{strtolower($color->color)}} product_color size_{{$color->size}}" onclick='change_product_color_image("{{asset('storage/media/'.$color->attr_image)}}","{{$color->color}}")'></a>
                      @endforeach
                      @endif                 
                    </div>
                    @endif
                    <div class="aa-prod-quantity">
                      <form action="">
                        <select id="qty" name="qty">
                          @for($i=1; $i<11; $i++)
                            <option value="{{$i}}">{{$i}}</option>
                          @endfor
                        </select>
                      </form>
                      <p class="aa-prod-category">
                        Model: <a href="#">{{$product[0]->model}}</a>
                      </p>
                    </div>
                    <div class="aa-prod-view-bottom">
                      <a class="aa-add-to-cart-btn" href="javascript:void(0)" onclick="add_to_cart('{{$product[0]->id}}','{{$product_attr[$product[0]->id][0]->size_id}}','{{$product_attr[$product[0]->id][0]->color_id}}')">Add To Cart</a>
                      <!-- <a class="aa-add-to-cart-btn" href="#">Wishlist</a>
                      <a class="aa-add-to-cart-btn" href="#">Compare</a>
                    </div> -->
                    <div id="add_to_cart_msg" style="margin-top: 10px;"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="aa-product-details-bottom">
              <ul class="nav nav-tabs" id="myTab2">
                <li><a href="#description" data-toggle="tab">Description</a></li>
                <li><a href="#technical_specification" data-toggle="tab">Technical Specification</a></li>
                <li><a href="#uses" data-toggle="tab">Uses</a></li>
                <li><a href="#warrenty" data-toggle="tab">Warrenty</a></li>
                <li><a href="#review" data-toggle="tab">Reviews</a></li>                
              </ul>

              <!-- product description -->
              <div class="tab-content">
                <div class="tab-pane fade in active" id="description">
                  {!!$product[0]->desc!!}
                </div>
                <!-- product technical_specification -->
                <div class="tab-pane fade" id="technical_specification">
                  {!!$product[0]->technical_specification!!}
                </div>
                <!-- product uses -->
                <div class="tab-pane fade" id="uses">
                  {!!$product[0]->uses!!}
                </div>
                <!-- product warrenty -->
                <div class="tab-pane fade" id="warrenty">
                  {!!$product[0]->warrenty!!}
                </div>
                <div class="tab-pane fade " id="review">
                 <div class="aa-product-review-area">
                  @if(isset($product_review[0]))
                   <h4>
                    @php echo count($product_review); @endphp  
                    Review(s) for - {{$product[0]->name}}
                    </h4> 
                   <ul class="aa-review-nav">
                    @foreach($product_review as $list)
                     <li>
                        <div class="media">
                          <div class="media-body">
                            <h4 class="media-heading"><strong>{{$list->name}}</strong> - <span>{{getCustomDate($list->added_on)}}</span></h4>
                            <div class="aa-product-rating">
                              <span class="">{{$list->rating}}</span>
                            </div>
                            <p>{{$list->review}}</p>
                          </div>
                        </div>
                      </li>
                    @endforeach
                   </ul>
                   @else
                   <h2>Review Not Found</h2>
                   @endif
                   <form id="frmProductReview" class="aa-review-form">
                     <h4>Add a review</h4>
                     <div class="aa-your-rating">
                       <p>Your Rating</p>
                       <select class="form-control" name="rating" required>
                         <option value="">Select Rating</option>
                         <option>Worst</option>
                         <option>Bad</option>
                         <option>Good</option>
                         <option>Very Good</option>
                         <option>Fantastic</option>
                       </select>
                     </div>                   
                      <div class="form-group">
                        <label for="message">Your Review</label>
                        <textarea class="form-control" rows="3" name="review" required></textarea>
                      </div>
                      <button type="submit" name="submit" class="btn btn-default aa-review-submit">Submit</button>
                      <input type="hidden" name="product_id" value="{{$product[0]->id}}">
                      @csrf
                   </form>
                   <div class="errorfiled"></div>
                 </div>
                </div>            
              </div>
            </div>

            <!-- Related product -->
            <div class="aa-product-related-item">
              <h3>Related Products</h3>
              <ul class="aa-product-catg aa-related-item-slider">
                @if(isset($related_product[0]))
                        @foreach($related_product as $productArr)
                        <li>
                          <figure>
                            <a class="aa-product-img" href="{{url('product/'.$productArr->slug)}}"><img src="{{asset('storage/media/'.$productArr->image)}}" alt="{{$productArr->name}}"></a>
                            <a class="aa-add-card-btn"href="{{url('product/'.$productArr->slug)}}"><span class="fa fa-shopping-cart"></span>Add To Cart</a>
                              <figcaption>
                              <h4 class="aa-product-title"><a href="{{url('product/'.$productArr->slug)}}">{{$productArr->name}}</a></h4>
                              <span class="aa-product-price">Rs {{$related_product_attr[$productArr->id][0]->price}}</span><span class="aa-product-price"><del>Rs {{$related_product_attr[$productArr->id][0]->mrp}}</del></span>
                            </figcaption>
                          </figure>
                        </li>
                        @endforeach 
                        @else
                        <li>
                          <figure>
                            <h3 class="taxt-warning text-success taxt-capitalize">Data Not found</h3>
                          </figure>
                        </li>
                        @endif         
              </ul>  
            </div>  
          </div>
        </div>
      </div>
    </div>
  </section>

  <form id="frmAddToCart">
    @csrf
    <input type="hidden" name="size_id" id="size_id">
    <input type="hidden" name="color_id" id="color_id">
    <input type="hidden" name="pqty" id="pqty">
    <input type="hidden" name="product_id" id="product_id">
  </form>

@endsection