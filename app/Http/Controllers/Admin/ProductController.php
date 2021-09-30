<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class ProductController extends Controller
{
    public function index()
    {
        $results['data'] = Product::all();
        return view('admin.product', $results);
    }

    public function manage_product(Request $request ,$id ='')
    {
        if ($id>0) {
            $arr = Product::where(['id'=>$id])->get();

            $result['category_id'] = $arr['0']->category_id;
            $result['name'] = $arr['0']->name;
            $result['image'] = $arr['0']->image;
            $result['slug'] = $arr['0']->slug;
            $result['brands'] = $arr['0']->brand;
            $result['model'] = $arr['0']->model;
            $result['short_desc'] = $arr['0']->short_desc;
            $result['desc'] = $arr['0']->desc;
            $result['keywords'] = $arr['0']->keywords;
            $result['technical_specification'] = $arr['0']->technical_specification;
            $result['uses'] = $arr['0']->uses;
            $result['tax_id'] = $arr['0']->tax_id;
            $result['warrenty'] = $arr['0']->warrenty;
            $result['lead_time'] = $arr['0']->lead_time;
            $result['tax_id'] = $arr['0']->tax_id;
            $result['is_promo'] = $arr['0']->is_promo;
            $result['is_featured'] = $arr['0']->is_featured;
            $result['is_tranding'] = $arr['0']->is_tranding;
            $result['is_discount'] = $arr['0']->is_discount;
            $result['status'] = $arr['0']->status;
            $result['id'] = $arr['0']->id;

        $result['productAttrArr']=DB::table('product_attrs')->where(['products_id'=>$id])->get();

    /*  checking multiple images form the table*/
        $productImagesArr=DB::table('product_images')->where(['products_id'=>$id])->get();
        
    /* when table has trancate so we need index which is not found so we need the more thing*/  
        /*print_r($productImagesArr);Illuminate\Support\Collection Object ( [items:protected] => Array ( ) )*/

        /*print_r($productImagesArr);
        Illuminate\Support\Collection Object ( [items:protected] => Array ( [0] => stdClass Object ( [id] => 1 [products_id] => 2 [images] => w [created_at] => [updated_at] => ) ) )*/

        //print_r($productImagesArr[0]);
        /*stdClass Object ( [id] => 1 [products_id] => 2 [images] => w [created_at] => [updated_at] => )*/
        //die();

            if (!isset($productImagesArr[0])) {
                $result['productImagesArr']['0']['id']='';
                $result['productImagesArr']['0']['images']='';
              }else{
                //print_r($productImagesArr[0]);
                $result['productImagesArr']=$productImagesArr;
              }  

        }else{
            $result['category_id']='';
            $result['name']='';
            $result['image']='';
            $result['slug']='';
            $result['brands']='';
            $result['model']='';
            $result['short_desc']='';
            $result['desc']='';
            $result['keywords']='';
            $result['technical_specification']='';
            $result['uses']='';
            $result['warrenty']='';
            $result['lead_time'] ='';
            $result['tax_id']=''; 
            $result['tax_id'] ='';
            $result['is_promo'] ='';
            $result['is_featured'] ='';
            $result['is_tranding'] ='';
            $result['is_discount'] ='';
            $result['status']='';
            $result['id'] =0;

/*Product Attributes */
            $result['productAttrArr'][0]['id'] ='';
            $result['productAttrArr'][0]['products_id'] ='';
            $result['productAttrArr'][0]['sku'] ='';
            $result['productAttrArr'][0]['attr_image'] ='';
            $result['productAttrArr'][0]['mrp'] ='';
            $result['productAttrArr'][0]['price'] ='';
            $result['productAttrArr'][0]['qty'] ='';
            $result['productAttrArr'][0]['size_id'] ='';
            $result['productAttrArr'][0]['color_id'] ='';

            /* Multiple images */
            $result['productImagesArr']['0']['id']='';
            $result['productImagesArr']['0']['images']='';
        }

        $result['category']=DB::table('categories')->where(['status'=>1])->get();      
        $result['size']=DB::table('sizes')->where(['status'=>1])->get();       
        $result['color']=DB::table('colors')->where(['status'=>1])->get();       
        $result['brand']=DB::table('brands')->where(['status'=>1])->get();  
        $result['taxes']=DB::table('taxes')->where(['status'=>1])->get();  
            
        return view('admin.manage_product', $result);
    }

    public function manage_product_process(Request $request)
    {  
        if ($request->post('id')>0) {
            $image_validation = 'mimes:jpeg,jpg,png';
         }else{
            $image_validation = 'required | mimes:jpeg,jpg,png';
         }
        $request->validate([
            'name'=>'required',
            'image'=>$image_validation,
            'slug'=>'required | unique:products,slug,'.$request->post('id'),

            /* product Attribute image validation for array by using .* */
            'attr_image.*'=>'mimes:jpeg,jpg,png',

            /* Multiple product images validation for array by using .* */
            'images.*'=>'mimes:jpeg,jpg,png',
        ]);

        /* Product Attributes Validation */
        $pAttridAttr = $request->post('pAttrid');
        $skuAttr = $request->post('sku');
        $mrpAttr = $request->post('mrp');
        $priceAttr = $request->post('price');
        $qtyAttr = $request->post('qty');
        $size_idAttr = $request->post('size_id');
        $color_idAttr = $request->post('color_id');
        foreach ($skuAttr as $key => $value) {
            $check = DB::table('product_attrs')->where('sku','=',$skuAttr[$key])->where('id','!=',$pAttridAttr[$key])->get();
            if (isset($check[0])) {
                $request->session()->flash('sku_error',$skuAttr[$key].' SKU already used ...!');
                return redirect(request()->headers->get('referer'));
            }

        }

    /* Product Attributes Validation End */

        $model = new Product;
        if ($request->post('id')>0) {
            $model =Product::find($request->post('id'));
            $msg = "Product data has been Updated";
         }else{
            $model = new Product;
            $msg = "Product data has been inserted";
         } 

         /* Product Image Section   */
        if ($request->hasfile('image')) {
            /*  Product Remove Image */
            if ($request->post('id')>0) {
                $productImg = DB::table('products')->where(['id'=>$request->post('id')])->get();
                if(Storage::exists('public/media/'.$productImg[0]->image)) {
                    Storage::delete('public/media/'.$productImg[0]->image);
                }
            }
            
            $image = $request->file('image');
            $ext = $image->extension();
            $image_name = time().".".$ext;
            $image->storeAs('/public/media',$image_name);
            $model->image = $image_name;
        }

        $model->category_id = $request->post('category_id');
        $model->name = $request->post('name');
        $model->slug = $request->post('slug');
        $model->brand = $request->post('brand');
        $model->model = $request->post('model');
        $model->short_desc = $request->post('short_desc');
        $model->desc = $request->post('desc');
        $model->keywords = $request->post('keywords');
        $model->technical_specification = $request->post('technical_specification');
        $model->uses = $request->post('uses');
        $model->warrenty = $request->post('warrenty');

        $model->lead_time = $request->post('lead_time');
        $model->tax_id = $request->post('tax_id');
        $model->is_promo = $request->post('is_promo');
        $model->is_featured = $request->post('is_featured');
        $model->is_tranding = $request->post('is_tranding');
        $model->is_discount = $request->post('is_discount');

        $model->status = 1;
        $model->save();

        $pid = $model->id;
    /* Product Attributes Section Start*/
    foreach ($skuAttr as $key => $value) {
        $productAttrArr=[];
        $productAttrArr['products_id']=$pid;
        $productAttrArr['sku']=$skuAttr[$key];
        $productAttrArr['mrp']=(int)$mrpAttr[$key];
        $productAttrArr['price']=(int)$priceAttr[$key];
        $productAttrArr['qty']=(int)$qtyAttr[$key];
        if ($size_idAttr[$key]=="") {
            $productAttrArr['size_id']=0;
        }else{
            $productAttrArr['size_id']=$size_idAttr[$key];
        }
        if ($color_idAttr[$key]=="") {
            $productAttrArr['color_id']=0;
        }else{
            $productAttrArr['color_id']=$color_idAttr[$key];
        }

        /* Attributes Image Upload */
        if($request->hasFile("attr_image.$key")) {
            
            if ($pAttridAttr[$key] != '') {
                $imagesArr = DB::table('product_attrs')->where(['id'=>$pAttridAttr[$key]])->get();
                if(Storage::exists('public/media/'.$imagesArr[0]->attr_image)) {
                    Storage::delete('public/media/'.$imagesArr[0]->attr_image);
                }
            }

            $rand = rand('111111111','999999999');
            $attr_image = $request->file("attr_image.$key");
            $ext = $attr_image->extension();
            $image_name = $rand.".".$ext;
            $request->file("attr_image.$key")->storeAs('/public/media/',$image_name);
            $productAttrArr['attr_image']=$image_name;
        }
        
        if ($pAttridAttr[$key] != '') {
            DB::table('product_attrs')->where(['id'=>$pAttridAttr[$key]])->update($productAttrArr);
        }else{
            DB::table('product_attrs')->insert($productAttrArr);
        }
        
    }

    /* Product Attributes Section End*/


    /*Multiple product Images*/
        $pImgAttridArr = $request->post('pImgAttrid');
        foreach ($pImgAttridArr as $key => $value) {
            $productImgArr=[];
            $productImgArr['products_id']=$pid;

        /*   Remove Image   */        
                if ($request->hasFile("images.$key")) {
                    if ($pImgAttridArr[$key] != '') {
                        $productImgs = DB::table('product_images')->where(['id'=>$pImgAttridArr[$key]])->get();
                        if(Storage::exists('public/media/'.$productImgs[0]->images)) {
                            Storage::delete('public/media/'.$productImgs[0]->images);
                        }
                    }
        /*   Remove Image End   */

                    $rand = rand('111111111','999999999');
                    $images = $request->file("images.$key");
                    $ext = $images->extension();
                    $image_name = $rand.".".$ext;
                    $request->file("images.$key")->storeAs('/public/media/',$image_name);
                    $productImgArr['images']=$image_name;
                    }

                if ($pImgAttridArr[$key] != '') {
                DB::table('product_images')->where(['id'=>$pImgAttridArr[$key]])->update($productImgArr);
                }else{
                    DB::table('product_images')->insert($productImgArr);
                }
        }
        /*Multiple product Images End*/


        $request->session()->flash('message',$msg);
        return redirect('admin/product');
    }

    public function delete(Request $request, $id)
    {
        $model = Product::find($id);
        $model->delete();
        $request->session()->flash('message','Product data has been Deleted');
        return redirect('admin/product');
    }

    /* Delete Product Attribute section */
    public function product_attr_delete(Request $request, $pAttrid, $id )
    {
        /*  Product Multiple Image Remove Function */
        $imageAttrArr = DB::table('product_attrs')->where(['id'=>$pAttrid])->get();
        if(Storage::exists('public/media/'.$imageAttrArr[0]->attr_image)) {
            Storage::delete('public/media/'.$imageAttrArr[0]->attr_image);
        }
        DB::table('product_attrs')->where(['id'=>$pAttrid])->delete();
        return redirect('admin/product/manage_product/'.$id);
    }
    /* Delete Product Attribute section End */


    /* Delete Multiple Images section */
    public function product_images_delete(Request $request, $pImgAttrid, $id )
    {
        /* delete Attributes Image Remove Function */
        $imageArr = DB::table('product_images')->where(['id'=>$pImgAttrid])->get();
        if(Storage::exists('public/media/'.$imageArr[0]->images)) {
            Storage::delete('public/media/'.$imageArr[0]->images);
        }

        DB::table('product_images')->where(['id'=>$pImgAttrid])->delete();
        return redirect('admin/product/manage_product/'.$id);
    }
    /* Delete Multiple Images section End */


    public function status(Request $request, $status, $id)
    {
        $model = Product::find($id);
        $model->status=$status;
        $model->save();
        $request->session()->flash('message','Product status has been Updated');
        return redirect('admin/product');
    }

    
}
