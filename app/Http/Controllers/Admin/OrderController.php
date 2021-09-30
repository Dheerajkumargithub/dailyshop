<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
   
   public function index()
    {
        $result['orders'] = DB::table('orders')
                  ->select('orders.*','orders_status.orders_status') /*for order id and status name*/
                  ->leftjoin('orders_status','orders_status.id','=','orders.order_status')
                  ->get();
                  /*prx($result);*/
        return view('admin.order',$result);
    }

    function order_detail(Request $request, $id)
    {
        echo $id;
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
                  ->get();

        $result['orders_status'] = DB::table('orders_status')->get();
/*prx($result['orders_status']);*/
        $result['payment_status']=['Pending','Success','Fail'];
        return view('admin.order_detail',$result);

        /*if (!isset($result['orders_details'][0])) {
            return redirect('/admin');
        }else{
            return view('admin.order_detail',$result); 
        }*/
    }

/* Payment status*/
    public function update_payment_status(Request $request, $status, $id)
    {
        DB::table('orders')
            ->where(['id'=>$id])
            ->update(['payment_status'=>$status]);
        return redirect('/admin/order_detail/'.$id);
    }

/* Order Status*/
    public function update_order_status(Request $request, $status_id, $id)
    {
        DB::table('orders')
            ->where(['id'=>$id])
            ->update(['order_status'=>$status_id]);
        return redirect('/admin/order_detail/'.$id);
    }

/* Update Track Details*/
    public function update_track_details(Request $request, $id)
    {
        DB::table('orders')
            ->where(['id'=>$id])
            ->update(['track_details'=>$request->track_details]);
        return redirect('/admin/order_detail/'.$id);
    }
 
}
