<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Crypt;
use Mail;

class FrontController extends Controller
{     
    
/* Getting Categories    */    
    public function index(Request $request)
    {
      
      $result['home_categories'] = DB::table('categories')
                              ->where(['status'=>1])
                              ->where(['is_home'=>1])
                              ->get();

/* Getting category, products, product attrs    */
      foreach ($result['home_categories'] as $list) {
         $result['home_categories_product'][$list->id]=
                  DB::table('products')
                  ->where(['status'=>1])
                  ->where(['category_id'=>$list->id])
                  ->get();

         foreach ($result['home_categories_product'][$list->id] as $list1) {
            
               $result['home_product_attr'][$list1->id]=
                  DB::table('product_attrs')
                  ->leftjoin('sizes','sizes.id','=','product_attrs.size_id')
                  ->leftjoin('colors','colors.id','=','product_attrs.color_id')
                  ->where(['product_attrs.products_id'=>$list1->id])
                  ->get();
         }
      }

/* Getting Brands    */
      $result['home_brands'] = DB::table('brands')
                              ->where(['status'=>1])
                              ->where(['is_home'=>1])
                              ->get();

/* Getting featured Product    */
      $result['home_featured_product'][$list->id]=
                  DB::table('products')
                  ->where(['status'=>1])
                  ->where(['is_featured'=>1])
                  ->get();
      foreach ($result['home_featured_product'][$list->id] as $list1) {
            $result['home_featured_product_attr'][$list1->id]=
                  DB::table('product_attrs')
                  ->leftjoin('sizes','sizes.id','=','product_attrs.size_id')
                  ->leftjoin('colors','colors.id','=','product_attrs.color_id')
                  ->where(['product_attrs.products_id'=>$list1->id])
                  ->get();
      }                              
  
/* Getting tranding Product    */
      $result['home_tranding_product'][$list->id]=
                  DB::table('products')
                  ->where(['status'=>1])
                  ->where(['is_tranding'=>1])
                  ->get();
      foreach ($result['home_tranding_product'][$list->id] as $list1) {
            $result['home_tranding_product_attr'][$list1->id]=
                  DB::table('product_attrs')
                  ->leftjoin('sizes','sizes.id','=','product_attrs.size_id')
                  ->leftjoin('colors','colors.id','=','product_attrs.color_id')
                  ->where(['product_attrs.products_id'=>$list1->id])
                  ->get();
      }

/* Getting discount Product    */
      $result['home_discount_product'][$list->id]=
                  DB::table('products')
                  ->where(['status'=>1])
                  ->where(['is_discount'=>1])
                  ->get();
      foreach ($result['home_discount_product'][$list->id] as $list1) {
            $result['home_discount_product_attr'][$list1->id]=
                  DB::table('product_attrs')
                  ->leftjoin('sizes','sizes.id','=','product_attrs.size_id')
                  ->leftjoin('colors','colors.id','=','product_attrs.color_id')
                  ->where(['product_attrs.products_id'=>$list1->id])
                  ->get();
      }


      /* Home Banner Section */
      $result['home_banner'] = DB::table('home_banners')
                              ->where(['status'=>1])
                              ->get();
       return view('front.index', $result);       
    }


/*  Category Section*/
      public function category(Request $request, $slug)
      {
      /*  Value with category dropdown form filter */
            $sort               = "";
            $sort_txt           = "";
            $filter_price_start = "";
            $filter_price_end   = "";
            $color_filter       = "";
            $colorFilterArr     = [];

            if ($request->get('sort')!==null) {
                  $sort=$request->get('sort');
            }

            $query = DB::table('products');
            $query=$query->leftjoin('categories','categories.id','=','products.category_id');
            $query=$query->leftjoin('product_attrs','product_attrs.products_id','=','products.id');
            $query=$query->where(['products.status'=>1]);
            $query=$query->where(['categories.category_slug'=>$slug]);

      /*    Dropdown value    */
            if ($sort=='name') {
               $query=$query->orderBy('products.name','asc');
               $sort_txt = "Product Name";
            }
            if ($sort=='date') {
               $query=$query->orderBy('products.id','desc');
               $sort_txt = "Date";
            }
            if ($sort=='price_desc') {
                $query=$query->orderBy('product_attrs.price','desc');
                $sort_txt = "Price - DESC";
            }
            if ($sort=='price_asc') {
                $query=$query->orderBy('product_attrs.price','asc');
                $sort_txt = "Price - ASC";
            }
            
            /*    Price between filter    */
            if ($request->get('filter_price_start')!==null && $request->get('filter_price_end')!==null) {

                  $filter_price_start=$request->get('filter_price_start');
                  $filter_price_end=$request->get('filter_price_end');

                  if ($filter_price_start>0 && $filter_price_end>0) {
                        $query=$query->whereBetween('product_attrs.price',[$filter_price_start,$filter_price_end]);
                  }
            }

            /*    Color filter    */
            if ($request->get('color_filter')!==null) {

                  $color_filter   = $request->get('color_filter');
                  $colorFilterArr = explode(":",$color_filter);/*convert array form*/
                  $colorFilterArr = array_filter($colorFilterArr);/*unique color*/
                  
                  $query=$query->where(['product_attrs.color_id'=>$request->get('color_filter')]);
            }

            $query=$query->distinct()->select('products.*');
            $query=$query->get();
            $result['product'] = $query;

            foreach ($result['product'] as $list1) {
                  $query1 = DB::table('product_attrs');
                  $query1=$query1->leftjoin('sizes','sizes.id','=','product_attrs.size_id');
                  $query1=$query1->leftjoin('colors','colors.id','=','product_attrs.color_id');
                  $query1=$query1->where(['product_attrs.products_id'=>$list1->id]);
                  $query1=$query1->get();

                  $result['product_attr'][$list1->id] = $query1;    
            }

            /* Getting all  color*/
            $result['color']=DB::table('colors')
                              ->where(['status'=>1])
                              ->get();

            /* Getting all category */
            $result['category_left'] = DB::table('categories')
                              ->where(['status'=>1])
                              ->get();

            $result['sort']=$sort;
            $result['slug']=$slug;
            $result['sort_txt']=$sort_txt;
            $result['filter_price_start']=$filter_price_start;
            $result['filter_price_end']=$filter_price_end;
            $result['color_filter']=$color_filter;            
            $result['colorFilterArr']=$colorFilterArr;            

      return view('front.category',$result);
      }


/*    Product section       */
    public function product(Request $request, $slug)
    {
      /*    Product detail section       */
      $result['product']=
                  DB::table('products')
                  ->where(['status'=>1])
                  ->where(['slug'=>$slug])
                  ->get();
      foreach ($result['product'] as $list1) {
            $result['product_attr'][$list1->id]=
                  DB::table('product_attrs')
                  ->leftjoin('sizes','sizes.id','=','product_attrs.size_id')
                  ->leftjoin('colors','colors.id','=','product_attrs.color_id')
                  ->where(['product_attrs.products_id'=>$list1->id])
                  ->get();
      }

      /*    Multiple product Images section       */
      foreach ($result['product'] as $list1) {
            $result['multiple_product_images'][$list1->id]=
                  DB::table('product_images')
                  ->where(['product_images.products_id'=>$list1->id])
                  ->get();
      }

      /*    Related product section       */
      $result['related_product']=
                  DB::table('products')
                  ->where(['status'=>1])
                  ->where('slug','!=',$slug)
                  ->where(['category_id'=>$result['product'][0]->category_id])
                  ->get();

      foreach ($result['related_product'] as $list1) {
            $result['related_product_attr'][$list1->id]=
                  DB::table('product_attrs')
                  ->leftjoin('sizes','sizes.id','=','product_attrs.size_id')
                  ->leftjoin('colors','colors.id','=','product_attrs.color_id')
                  ->where(['product_attrs.products_id'=>$list1->id])
                  ->get();
      }
      
      /* getting  Review And rating */
      
      $result['product_review'] = 
            DB::table('product_review')
                  ->leftjoin('customers','customers.id','=','product_review.customer_id')
                  ->where(['product_review.product_id'=>$result['product'][0]->id])
                  ->where(['product_review.status'=>1])
                  ->orderBy('product_review.added_on','desc')
                  ->select('product_review.rating','product_review.review','product_review.added_on','customers.name')
                  ->get();

      return view('front.product', $result);
    }


    public function add_to_cart(Request $request){
      if ($request->session()->has('FRONT_USER_LOGIN')) {
           $uid       = $request->session()->get('FRONT_USER_ID');
           $user_type = "Reg";
      }else{
            /* setting the value from helper common file */
           $uid       = getUserTempId();
           $user_type = "Not-Reg"; 
      }
      $size_id    = $request->post('size_id');
      $color_id   = $request->post('color_id');
      $pqty       = $request->post('pqty');
      $product_id = $request->post('product_id');

      $result = DB::table('product_attrs')
                  ->select('product_attrs.id')
                  ->leftjoin('sizes','sizes.id','=','product_attrs.size_id')
                  ->leftjoin('colors','colors.id','=','product_attrs.color_id')
                  ->where(['products_id'=>$product_id])
                  ->where(['sizes.size'=>$size_id])
                  ->where(['colors.color'=>$color_id])
                  ->get();
      $product_attr_id = $result[0]->id;

/* checking qty for add to cart */
      /*$getAvailableQty = getAvailableQty($product_id,$product_attr_id);
      $finalAvailableQty = $getAvailableQty[0]->pqty-$getAvailableQty[0]->qty;
      if ($pqty>$finalAvailableQty) {
            return response()->json(['msg'=>"not_available",'data'=>" Only $finalAvailableQty left"]);
      }*/
      $check = DB::table('carts')
                  ->where(['user_id'=>$uid])
                  ->where(['user_type'=>$user_type])
                  ->where(['product_id'=>$product_id])
                  ->where(['product_attr_id'=>$product_attr_id])
                  ->get();

      if (isset($check[0])) {
            $update_id = $check[0]->id;
            if ($pqty==0) {
                  DB::table('carts')
                  ->where(['id'=>$update_id])
                  ->delete();
            $msg = "Data deleted";
            }else{
                DB::table('carts')
                  ->where(['id'=>$update_id])
                  ->update(['qty'=>$pqty]);
                  $msg = "Data upadted";  
            }
                 
      }else{
           $id = DB::table('carts')->insertGetId([
                  'user_id'=>$uid,
                  'user_type'=>$user_type,
                  'product_id'=>$product_id,
                  'product_attr_id'=>$product_attr_id,
                  'qty'=>$pqty,
                  'added_on'=>date('Y-m-d h:i:s')
           ]); 
           $msg = "Data Added";
      }

/*     Sending for cart Notification      */
      $result = DB::table('carts')
                  ->leftjoin('products','products.id','=','carts.product_id')
                  ->leftjoin('product_attrs','product_attrs.id','=','carts.product_attr_id')
                  ->leftjoin('sizes','sizes.id','=','product_attrs.size_id')
                  ->leftjoin('colors','colors.id','=','product_attrs.color_id')
                  ->where(['user_id'=>$uid])
                  ->where(['user_type'=>$user_type])
                  ->select('carts.qty','products.id as pid','products.name','products.slug','products.image','sizes.size','colors.color','product_attrs.price','product_attrs.id as attrid')
                  ->get();
                                                /*Define in custom js file result*/
      return response()->json(['msg'=>$msg,'data'=>$result,'totalItem'=>count($result)]);
    }


    public function cart(Request $request)
    {
      if ($request->session()->has('FRONT_USER_LOGIN')) {
           $uid       = $request->session()->get('FRONT_USER_ID');
           $user_type = "Reg";
      }else{
            /* setting the value from helper common file */
           $uid       = getUserTempId();
           $user_type = "Not-Reg"; 
      }
      $result['list'] = DB::table('carts')
                  ->leftjoin('products','products.id','=','carts.product_id')
                  ->leftjoin('product_attrs','product_attrs.id','=','carts.product_attr_id')
                  ->leftjoin('sizes','sizes.id','=','product_attrs.size_id')
                  ->leftjoin('colors','colors.id','=','product_attrs.color_id')
                  ->where(['user_id'=>$uid])
                  ->where(['user_type'=>$user_type])
                  ->select('carts.qty','products.id as pid','products.name','products.slug','products.image','sizes.size','colors.color','product_attrs.price','product_attrs.id as attrid')
                  ->get();
      /*prx($result);*/
      return view('front.cart',$result);
    }


    /*      Search with form  */
    public function search(Request $request, $str)
    {
      /*    Product detail section       */
      $result['product']=
      $query = DB::table('products');
            $query=$query->leftjoin('categories','categories.id','=','products.category_id');
            $query=$query->leftjoin('product_attrs','product_attrs.products_id','=','products.id');
            $query=$query->where(['products.status'=>1]);
      
            $query=$query->where('name','like',"%$str%");
            $query=$query->orwhere('model','like',"%$str%");
            $query=$query->orwhere('short_desc','like',"%$str%");
            $query=$query->orwhere('desc','like',"%$str%");
            $query=$query->orwhere('keywords','like',"%$str%");
            $query=$query->orwhere('technical_specification','like',"%$str%");
            $query=$query->distinct()->select('products.*');
            $query=$query->get();
            $result['product'] = $query;

            foreach ($result['product'] as $list1) {
                  $query1 = DB::table('product_attrs');
                  $query1=$query1->leftjoin('sizes','sizes.id','=','product_attrs.size_id');
                  $query1=$query1->leftjoin('colors','colors.id','=','product_attrs.color_id');
                  $query1=$query1->where(['product_attrs.products_id'=>$list1->id]);
                  $query1=$query1->get();

                  $result['product_attr'][$list1->id] = $query1;    
            }
      return view('front.search',$result);
    }


    /* Registration */
    public function registration(Request $request){
      if ($request->session()->has('FRONT_USER_LOGIN')!=null) {
            return redirect('/');
      }
      return view('front.registration');
    }

    /* Registration form */
    public function registration_process(Request $request)
    {
      $valid = Validator::make($request->all(),[
            "name"     =>'required',
            "email"    =>'required | email | unique:customers,email',
            "password" =>'required',
            "mobile"   =>'required | numeric | digits:10'
      ]);
      if (!$valid->passes()) {
            return response()->json(['status'=>'error','error'=>$valid->errors()->toArray()]);
      }else{
            $rand_id = rand(111111111,999999999);
            $arr = [
                  "name"        => $request->name,
                  "email"       => $request->email,
                  "password"    => Crypt::encrypt($request->password),
                  "mobile"      => $request->mobile,
                  "status"      => 1,
                  "is_verify"   => 0,
                  "rand_id"      => $rand_id,
                  "created_at"  => date('Y-m-d h:i:s'),
                  "updated_at"  => date('Y-m-d h:i:s')
            ];
            $query = DB::table('customers')->insert($arr);
            if ($query) {

      /* Send email for verification      */            
                  $data = ['name'=>$request->name, 'rand_id'=>$rand_id];
                  $user['to']=$request->email;
                  Mail::send('front/mail_varification', $data, function($messages) use ($user){
                        $messages->to($user['to']);
                        $messages->subject('Email id Verification');
                  });

                  return response()->json(['status'=>'success','msg'=>"Register Successfully. Please check your Mail id for Verification..."]);
            }
      }
    }


    /*  Login form */
    public function login_process(Request $request)
    {

      $result = DB::table('customers')
                  ->where(['email'=>$request->str_login_email])
                  ->get();
            if (isset($result[0])) {
                  $db_pwd    = Crypt::decrypt($result[0]->password);
                  $status    = $result[0]->status;
                  $is_verify = $result[0]->is_verify;

                  if ($is_verify==0) {
                      return response()->json(['status'=>'error','msg'=>'Please Verify Your Email Id..']);  
                  }

                  if ($status==0) {
                      return response()->json(['status'=>'error','msg'=>'Your Account has Deactivated...']);  
                  }

                  if ($db_pwd == $request->str_login_password) {
                        if ($request->rememberme===null) {
                              setcookie('login_email','',100);
                              setcookie('login_pwd','',100);
                        }else{
                              setcookie('login_email',$request->str_login_email,time()+60*60*60*360);
                              setcookie('login_pwd',$request->str_login_password,time()+60*60*60*360);
                        }
                        $request->session()->put('FRONT_USER_LOGIN',true);
                        $request->session()->put('FRONT_USER_ID',$result[0]->id);
                        $request->session()->put('FRONT_USER_NAME', $result[0]->name);
                        $status = "success";
                        $msg    = "Login Successfully";

                  $getUserTempId = getUserTempId();
                        DB::table('carts')
                              ->where(['user_id'=>$getUserTempId])
                              ->where(['user_type'=>'Not-Reg'])
                              ->update(['user_id'=>$result[0]->id,'user_type'=>'Reg']);
                  }else{
                        $status = "error";
                        $msg    = "Please enter valid Password";      
                  }
            }else{
                  $status = "error";
                  $msg    = "Please enter valid Email Id";
            }
      return response()->json(['status'=>$status,'msg'=>$msg]);
    }

/*Code for Email verification Update table */
    public function email_varification(Request $request,$id)
    {
      $result = DB::table('customers')
                  ->where(['rand_id'=>$id])
                  ->where(['is_verify'=>0])
                  ->get();
      if (isset($result[0])) {
            DB::table('customers')
                  ->where(['id'=>$result[0]->id])
                  ->update(['is_verify'=>1,'rand_id'=>'']);
      return view('front.verification');
      }else{
            return redirect('/');
      }
    }


    /*  Forgot password email checking */
    public function forgot_password(Request $request)
    {
      $result = DB::table('customers')
                  ->where(['email'=>$request->str_forgot_email])
                  ->get();

             $rand_id = rand(111111111,999999999);     
      if (isset($result[0])) {
            DB::table('customers')
                  ->where(['email'=>$request->str_forgot_email])
                  ->update(['is_forgot_password'=>1,'rand_id'=>$rand_id]);
            /* send mail from forgot_email blade*/
            $data = ['name'=>$result[0]->name, 'rand_id'=>$rand_id];
                  $user['to']=$request->str_forgot_email;
                  Mail::send('front/forgot_email', $data, function($messages) use ($user){
                        $messages->to($user['to']);
                        $messages->subject('Forgot password');
                  });

            return response()->json(['status'=>'success','msg'=>"Please check your Email id.."]);
      }else{
            return response()->json(['status'=>'error','msg'=>'Email not register']);
      }
    }

    public function forgot_password_change(Request $request,$id)
    {
      $result = DB::table('customers')
                  ->where(['rand_id'=>$id])
                  ->where(['is_forgot_password'=>1])
                  ->get();
      if (isset($result[0])) {
            $request->session()->put('FORGOT_PASSWORD_USER_ID',$result[0]->id);
            return view('front.forgot_password_change');
      }else{
            return redirect('/');
      }
    }

    /* update password*/
    public function forgot_password_change_process(Request $request)
    {
      DB::table('customers')
                  ->where(['id'=>$request->session()->get('FORGOT_PASSWORD_USER_ID')])
                  ->update([
                        'is_forgot_password'=>0,
                        'password'=>Crypt::encrypt($request->password),
                        'rand_id'=>''
                  ]);
      return response()->json(['status'=>'success','msg'=>'Password has chnaged']);
    }

    /* Checkout page */
    public function checkout(Request $request)
    {
      $result['cart_data'] = getAddToCartTotalItem();
      if (isset($result['cart_data'][0])) {
            if ($request->session()->has('FRONT_USER_LOGIN')) {
                 $uid       = $request->session()->get('FRONT_USER_ID');
                 $customer_info = DB::table('customers')
                        ->where(['id'=>$uid])
                        ->get();
                  $result['customers']['name']    = $customer_info[0]->name;
                  $result['customers']['email']   = $customer_info[0]->email;
                  $result['customers']['mobile']  = $customer_info[0]->mobile;
                  $result['customers']['address'] = $customer_info[0]->address;
                  $result['customers']['city']    = $customer_info[0]->city;
                  $result['customers']['state']   = $customer_info[0]->state;
                  $result['customers']['zip']     = $customer_info[0]->zip;
            }else{
                  $result['customers']['name']    = '';
                  $result['customers']['email']   = '';
                  $result['customers']['mobile']  = '';
                  $result['customers']['address'] = '';
                  $result['customers']['city']    = '';
                  $result['customers']['state']   = '';
                  $result['customers']['zip']     = '';
            }
            return view('front.checkout',$result);
      }else{
            return redirect('/');
      }
    }

    /* Coupon code apply */
    public function apply_coupon_code(Request $request)
    {
      /* Getting the code from common(helper).php */
      $arr = apply_coupon_code($request->coupon_code);
      $arr = json_decode($arr, true);
      return response()->json(['status'=>$arr['status'],'msg'=>$arr['msg'],'totalPrice'=>$arr['totalPrice']]);
    }

    public function remove_coupon_code(Request $request)
    {
      $totalPrice=0;
      $result = DB::table('coupons')
                  ->where(['code'=>$request->coupon_code])
                  ->get();
      $getAddToCartTotalItem=getAddToCartTotalItem();
      foreach ($getAddToCartTotalItem as $list) {
            $totalPrice=$totalPrice+($list->qty*$list->price);
      }
      
      return response()->json(['status'=>"success",'msg'=>"Coupon Code removed",'totalPrice'=>$totalPrice]);
    }

    public function place_order(Request $request)
    {
      $payment_url = '';
      $rand_id = rand(111111111,999999999);
      /*    guest checkout */
      if ($request->session()->has('FRONT_USER_LOGIN')) {
            //
      }else{
            $valid = Validator::make($request->all(),[
            "email"    =>'required | email | unique:customers,email'
            ]);
            if (!$valid->passes()) {
                  return response()->json(['status'=>'error','msg'=>'The Email id has been already taken']);
            }else{
                  
                  $arr = [
                        "name"        => $request->name,
                        "email"       => $request->email,
                        "address"     => $request->address,
                        "city"        => $request->city,
                        "state"       => $request->state,
                        "zip"         => $request->zip,
                        "password"    => Crypt::encrypt($rand_id),
                        "mobile"      => $request->mobile,
                        "status"      => 1,
                        "is_verify"   => 1,
                        "rand_id"     => $rand_id,
                        "created_at"  => date('Y-m-d h:i:s'),
                        "updated_at"  => date('Y-m-d h:i:s'),
                        "is_forgot_password"=>0
                  ];
                  $user_id = DB::table('customers')->insertGetId($arr);
                  $request->session()->put('FRONT_USER_LOGIN',true);
                  $request->session()->put('FRONT_USER_ID',$user_id);
                  $request->session()->put('FRONT_USER_NAME', $request->name);

            /* Send email for verification      */            
                  $data = ['name'=>$request->name, 'password'=>$rand_id];
                  $user['to']=$request->email;
                  Mail::send('front/password_send', $data, function($messages) use ($user){
                        $messages->to($user['to']);
                        $messages->subject('Sending a Password from Dailyshop');
                  });

                  $getUserTempId = getUserTempId();
                  DB::table('carts')
                        ->where(['user_id'=>$getUserTempId])
                        ->where(['user_type'=>'Not-Reg'])
                        ->update(['user_id'=>$user_id,'user_type'=>'Reg']);
            }
      }
            /* function from common file */
            $coupon_value = 0;
            if ($request->coupon_code!='') {
                  $arr = apply_coupon_code($request->coupon_code);
                  $arr = json_decode($arr, true);
                  if ($arr['status']=='success') {
                        $coupon_value = $arr['coupon_code_value']; 
                  }else{
                        return response()->json(['status'=>'false','msg'=>$arr['msg']]);
                  }
            }
            
           $uid       = $request->session()->get('FRONT_USER_ID');
           $totalPrice=0;
            $getAddToCartTotalItem=getAddToCartTotalItem();

            foreach ($getAddToCartTotalItem as $list) {
                  $totalPrice=$totalPrice+($list->qty*$list->price);
            }
           $arr = [
                  "customers_id"  => $uid,
                  "name"          => $request->name,
                  "email"         => $request->email,
                  "mobile"        => $request->mobile,
                  "address"       => $request->address,
                  "city"          => $request->city,
                  "state"         => $request->state,
                  "pincode"       => $request->zip,
                  "coupon_code"   => $request->coupon_code,
                  "coupon_value"  => $coupon_value,
                  "order_status"  => 1,
                  "payment_type"  => $request->payment_type,
                  "payment_status"=> "Pending",
                  "total_amt"     => $totalPrice,
                  "created_at"  => date('Y-m-d h:i:s')
            ];
            $order_id = DB::table('orders')->insertGetId($arr);
            if ($order_id>0) { 
                  foreach ($getAddToCartTotalItem as $list) {
                        $productDetailArr['orders_id'] = $order_id;
                        $productDetailArr['product_id'] = $list->pid;
                        $productDetailArr['product_attrs_id'] = $list->attrid;
                        $productDetailArr['price'] = $list->price;
                        $productDetailArr['qty'] = $list->qty;
                        DB::table('orders_details')->insert($productDetailArr);
                  }

                  /* Instamojo payment gateway  */
                  if ($request->payment_type=='Gateway') {
                        $final_amt = $totalPrice-$coupon_value;
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'https://test.instamojo.com/api/1.1/payment-requests/');
                        curl_setopt($ch, CURLOPT_HEADER, FALSE);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); //use for ignore for https:
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                        curl_setopt($ch, CURLOPT_HTTPHEADER,
                              array("X-Api-Key:test_1d62af7a44af115912e8d23211f",
                                            "X-Auth-Token:test_5c3af735d852410e42d49ad853d"));
                        $payload = Array(
                             'purpose' => 'Payment Testing with Laravel',
                             'amount' => $final_amt,
                             'phone' => $request->mobile,
                             'buyer_name' => $request->name,
                             'redirect_url' => 'http://127.0.0.1:8000/instamojo_payment_redirect/',
                             'send_email' => true,
                             'send_sms' => true,
                             'email' => $request->email,
                             'allow_repeated_payments' => false
                        );
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
                        $response = curl_exec($ch);
                        curl_close($ch); 
                        $response = json_decode($response);
                        if (isset($response->payment_request->id)) {
                              $trans_id    = $response->payment_request->id;
                              DB::table('orders')
                                    ->where(['id'=>$order_id])
                                    ->update(['trans_id'=>$trans_id]);
                              $payment_url = $response->payment_request->longurl;
                        }else{
                              $msg = '';
                              foreach ($response->message as $key => $value) {
                                   $msg.= strtoupper($key)." : ".$value[0].'<br/>'; 
                              }
                              return response()->json(['status'=>'error','msg'=>$msg,'payment_url'=>'']);
                        }                       
                        
                  }

                  DB::table('carts')->where(['user_id'=>$uid,'user_type'=>'Reg'])->delete();
                  $request->session()->put('ORDER_ID',$order_id);

                  $status = "success";
                  $msg    = "Order Placed";
            }else{
                  $status = "false";
                  $msg    = "Please try after Sometime";
            }
      return response()->json(['status'=>$status,'msg'=>$msg,'payment_url'=>$payment_url]);
    }


    /* Order Placed */
    public function order_placed(Request $request)
    {
       if ($request->session()->has('ORDER_ID')) {
            return view('front.order_placed');
       }else{
            return redirect('/');
       }
    }


       /* Order Placed */
      public function order_failed(Request $request)
      {
            if ($request->session()->has('ORDER_ID')) {
                  return view('front.order_failed');
            }else{
                  return redirect('/');
             }
      }

    /* after payment */
    function instamojo_payment_redirect(Request $request)
    {
      if($request->get('payment_id')!==null && $request->get('payment_status')!==null && $request->get('payment_request_id')!==null){
            if ($request->get('payment_status')=='Credit') {
                  $status = "Success";
                  $redirect_url = "/order_placed";
            }else{
                  $status = "Fail";
                  $redirect_url = "/order_failed";
            }
            $request->session()->put('ORDER_STATUS',$status);
            DB::table('orders')
                  ->where(['trans_id'=>$request->get('payment_request_id')])
                  ->update(['payment_id'=>$request->get('payment_id'),'payment_status'=>$status]);
                  return redirect($redirect_url);
      }else{
            die('Something went wrong');
      }
    }

    /* My Orders */
    public function order(Request $request)
    {
      $result['orders'] = DB::table('orders')
                  ->select('orders.*','orders_status.orders_status') /*for order id and status name*/
                  ->leftjoin('orders_status','orders_status.id','=','orders.order_status')
                  ->where(['orders.customers_id'=>$request->session()->get('FRONT_USER_ID')])
                  ->get();
                  /*prx($result['orders']);*/
      return view('front.order',$result);
    }

    /* Order Detail page*/
    public function order_detail(Request $request, $id)
    {
      $result['orders_details']=
                  DB::table('orders_details')
                  ->select('orders.*','orders_details.price','orders_details.qty','products.name as pname','product_attrs.attr_image','sizes.size','colors.color','orders_status.orders_status')
                  ->leftjoin('orders','orders.id','=','orders_details.orders_id')
                  ->leftjoin('product_attrs','product_attrs.id','=','orders_details.product_attrs_id')
                  ->leftjoin('products','products.id','=','product_attrs.products_id')
                  ->leftjoin('sizes','sizes.id','=','product_attrs.size_id')
                  ->leftjoin('colors','colors.id','=','product_attrs.color_id')
                  ->leftjoin('orders_status','orders_status.id','=','orders.order_status')
                  ->where(['orders.id'=>$id])
                  ->where(['orders.customers_id'=>$request->session()->get('FRONT_USER_ID')])
                  ->get();
            if (!isset($result['orders_details'][0])) {
                  return redirect('/');
            }else{
                 return view('front.order_detail',$result); 
            }
    }

/*   User Review & rating     */
    public function product_review_process(Request $request)
    {
      if ($request->session()->has('FRONT_USER_LOGIN')) {
            $uid       = $request->session()->get('FRONT_USER_ID');
            $arr = [
                  "rating"      => $request->rating,
                  "review"      => $request->review,
                  "product_id"  => $request->product_id,
                  "rating"      => $request->rating,
                  "customer_id" => $uid,
                  "status"      => 0,
                  "added_on"    => date('Y-m-d h:i:s')
            ];
            $status = 'Success';
            $msg    = "Thank you for providing your review";

            $query = DB::table('product_review')->insert($arr);
      }else{
            $status = 'error';
            $msg    = "Please login to submit your Review"; 
      }
      return response()->json(['status'=>$status,'msg'=>$msg]);
    }
}
