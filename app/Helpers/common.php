<?php 

use Illuminate\Support\Facades\DB;

function prx($arr){
	echo "<pre>";
	print_r($arr);
	die();
}

function getTopNavCat(){
	$result = DB::table('categories')
                ->where(['status'=>1])
                ->get();
    $arr=[];
	foreach ($result as $row) {
    $arr[$row->id]['category_name']=$row->category_name;
	  $arr[$row->id]['category_slug']=$row->category_slug;
	  $arr[$row->id]['parent_id']=$row->parent_category_id;
	}
	$str = buildTreeView($arr,0);
	return $str;
}

$html = '';
function buildTreeView($arr, $parent, $level=0, $prelevel=-1){
  global $html;
  foreach ($arr as $id => $data) {
    if ($parent==$data['parent_id']) {
      if ($level>$prelevel) {
        if ($html=='') {
          $html .= "<ul class='nav navbar-nav'>";
        }else{
          $html .= "<ul class='dropdown-menu'>";
        }
      }
      if ($level==$prelevel) {
        $html .= "</li>";
      }
      $html .= '<li><a href="category/'.$data['category_slug'].'">'.$data['category_name'].'<span class="caret"></span></a>';
      if ($level>$prelevel) {
        $prelevel=$level;
      }
      $level++;
      buildTreeView($arr, $id, $level, $prelevel);
      $level--;
    }
  }
  if ($level==$prelevel) {
    $html .= "</li></ul>";
  }
  return $html;
}


/*set the value in front controller add_to_cart */
function getUserTempId(){
  if (!session()->has('USER_TEMP_ID')) {
     $rand = rand('111111111','999999999');
     session()->put('USER_TEMP_ID',$rand);
     return $rand;
  }else{
     return session()->get('USER_TEMP_ID');
  }
}


function getAddToCartTotalItem(){
  if (session()->has('FRONT_USER_LOGIN')) {
           $uid       = session()->get('FRONT_USER_ID');
           $user_type = "Reg";
      }else{
            /* setting the value from helper common file */
           $uid       = getUserTempId();
           $user_type = "Not-Reg"; 
      }
  $result = DB::table('carts')
                  ->leftjoin('products','products.id','=','carts.product_id')
                  ->leftjoin('product_attrs','product_attrs.id','=','carts.product_attr_id')
                  ->leftjoin('sizes','sizes.id','=','product_attrs.size_id')
                  ->leftjoin('colors','colors.id','=','product_attrs.color_id')
                  ->where(['user_id'=>$uid])
                  ->where(['user_type'=>$user_type])
                  ->select('carts.qty','products.id as pid','products.name','products.slug','products.image','sizes.size','colors.color','product_attrs.price','product_attrs.id as attrid')
                  ->get();

      return $result;
}


/* code apply coupon code*/
function apply_coupon_code($coupon_code)
{
  $totalPrice=0;
  $result = DB::table('coupons')
          ->where(['code'=>$coupon_code])
          ->get();
           
  if (isset($result[0])) {
    $value= $result[0]->value;
    $type= $result[0]->type;
    if ($result[0]->status==1) {
      if ($result[0]->is_one_time==1) {
        $status = 'error';
        $msg    = 'Coupon Code already used';
      }else{
        $min_order_amt=$result[0]->min_order_amt;
        if ($min_order_amt>0) {
          $getAddToCartTotalItem=getAddToCartTotalItem();
          $totalPrice=0;
          foreach ($getAddToCartTotalItem as $list) {
            $totalPrice=$totalPrice+($list->qty*$list->price);
          }
          if ($min_order_amt<$totalPrice) {
            $status = "success";
            $msg    = "Coupon Code applied";
          }else{
            $status = "error";
            $msg    = "Cart amount must be greater then $min_order_amt";
          }
        }else{
          $status = "error";
          $msg    = "Coupon Code not supported for this value"; 
        }
      }
    }else{
      $status = "error";
      $msg    = "Coupon Code deactivated";
   }            
  }else{
    $status = "error";;
    $msg    = "Please enter valid Coupon Code";
  }
      /* Apply Coupon */
      $coupon_code_value = 0;
  if ($status=="success") {
    if ($type=="Value") {
      $coupon_code_value = $value;
      $totalPrice = $totalPrice-$value;
    }
    if ($type=="Per") {
      $newPrice = ($value/100)*$totalPrice;
      $totalPrice = round($totalPrice-$newPrice);
      $coupon_code_value = $newPrice;
    }
  }
  return json_encode(['status'=>$status,'msg'=>$msg,'totalPrice'=>$totalPrice,'coupon_code_value'=>$coupon_code_value]);
}


/* getting date formatted */
function getCustomDate($date){
  if ($date!='') {
    $date = strtotime($date);
    return date('d-M Y',$date);
  }
}


function getAvailableQty($product_id, $attr_id){
  $result = 
      DB::table('orders_details')
          ->leftjoin('orders','orders.id','=','orders_details.orders_id')
          ->leftjoin('product_attrs','product_attrs.id','=','orders_details.product_attrs_id')
          ->where(['orders_details.product_id'=>$product_id])
          ->where(['orders_details.product_attrs_id'=>$attr_id])
          ->select('orders_details.qty','product_attrs.qty as pqty')
          ->get();

  return $result;
}



 ?>