<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\HomeBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class HomeBannerController extends Controller
{
    
    public function index()
    {
        $results['data'] = HomeBanner::all();
        return view('admin.home_banner', $results);
    }

    public function manage_home_banner(Request $request ,$id ='')
    {
        if ($id>0) {
            $arr = HomeBanner::where(['id'=>$id])->get();

            $result['image'] = $arr['0']->image;
            $result['btn_txt'] = $arr['0']->btn_txt;
            $result['btn_link'] = $arr['0']->btn_link;
            $result['title'] = $arr['0']->title;
            $result['description'] = $arr['0']->description;
            $result['status'] = $arr['0']->status;
            $result['id'] = $arr['0']->id;

        }else{
            $result['image']='';
            $result['btn_txt']='';
            $result['btn_link'] ='';
            $result['title'] ='';
            $result['description'] ='';
            $result['status']="";
            $result['id'] =0;
        }

        return view('admin.manage_home_banner', $result);
    }

    public function manage_home_banner_process(Request $request)
    {
        //return $request->post();
        $request->validate([
            'image'=>'required | mimes:jpeg,jpg,png',
        ]);

        $model = new HomeBanner;
        if ($request->post('id')>0) {
            $model =HomeBanner::find($request->post('id'));
            $msg = "Home Banner data has been Updated";
         }else{
            $model = new HomeBanner;
            $msg = "Home Banner data has been inserted";
         } 

        if ($request->hasfile('image')) {

            if ($request->post('id')>0) {
                $image = DB::table('home_banners')->where(['id'=>$request->post('id')])->get();
                if(Storage::exists('public/media/banner/'.$image[0]->image)) {
                    Storage::delete('public/media/banner/'.$image[0]->image);
                }
            }

            $rand = rand("111111111","999999999");
            $image = $request->file('image');
            $ext = $image->extension();
            $image_name = $rand.".".$ext;
            $image->storeAs('/public/media/banner',$image_name);
            $model->image = $image_name;
        }
         
        $model->btn_txt = $request->post('btn_txt');
        $model->btn_link = $request->post('btn_link');
        $model->title = $request->post('title');
        $model->description = $request->post('description');
        $model->status = 1;
        $model->save();
        $request->session()->flash('message',$msg);
        return redirect('admin/home_banner');
    }

    public function delete(Request $request, $id)
    {
        $model = HomeBanner::find($id);
        $model->delete();
        $request->session()->flash('message','Home Banner data has been Deleted');
        return redirect('admin/home_banner');
    }

    public function status(Request $request, $status, $id)
    {
        $model = HomeBanner::find($id);
        $model->status=$status;
        $model->save();
        $request->session()->flash('message','Category status has been Updated');
        return redirect('admin/home_banner');
    }
}
