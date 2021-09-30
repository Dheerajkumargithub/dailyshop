<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class CategoryController extends Controller
{
    public function index()
    {
    	$results['data'] = Category::all();
        return view('admin.category', $results);
    }

    public function manage_category(Request $request ,$id ='')
    {
    	if ($id>0) {
    		$arr = Category::where(['id'=>$id])->get();

    		$result['category_name'] = $arr['0']->category_name;
            $result['category_slug'] = $arr['0']->category_slug;
            $result['parent_category_id'] = $arr['0']->parent_category_id;
            $result['category_image'] = $arr['0']->category_image;

    		$result['is_home'] = $arr['0']->is_home;
            $result['is_home_selected']="";
            if ($arr['0']->is_home==1) {
                $result['is_home_selected']="Checked";
            }

    		$result['id'] = $arr['0']->id;

            $result['category']=DB::table('categories')->where('id','!=',$id)->where(['status'=>1])->get();

    	}else{
    		$result['category_name']='';
    		$result['category_slug']='';
            $result['parent_category_id'] ='';
            $result['category_image'] ='';
            $result['is_home'] ='';
            $result['is_home_selected']="";
    		$result['id'] =0;

            $result['category']=DB::table('categories')->where(['status'=>1])->get();
    	}

    	return view('admin.manage_category', $result);
    }

    public function manage_category_process(Request $request)
    {
    	//return $request->post();
        $request->validate([
        	'category_name'=>'required',
            'category_image'=>'mimes:jpeg,jpg,png',
        	'category_slug'=>'required | unique:categories,category_slug,'.$request->post('id'),
        ]);

        $model = new Category;
        if ($request->post('id')>0) {
         	$model =Category::find($request->post('id'));
         	$msg = "Category data has been Updated";
         }else{
         	$model = new Category;
         	$msg = "Category data has been inserted";
         } 

        if ($request->hasfile('category_image')) {

            if ($request->post('id')>0) {
                $category_image = DB::table('categories')->where(['id'=>$request->post('id')])->get();
                if(Storage::exists('public/media/category/'.$category_image[0]->category_image)) {
                    Storage::delete('public/media/category/'.$category_image[0]->category_image);
                }
            }

            $rand = rand("111111111","999999999");
            $image = $request->file('category_image');
            $ext = $image->extension();
            $image_name = $rand.".".$ext;
            $image->storeAs('/public/media/category',$image_name);
            $model->category_image = $image_name;
        }
         
        $model->category_name = $request->post('category_name');
        $model->category_slug = $request->post('category_slug');
        $model->parent_category_id = $request->post('parent_category_id');
        $model->is_home=0;
        if ($request->post('is_home')!==null) {
            $model->is_home=1;
        }
        $model->status = 1;
        $model->save();
        $request->session()->flash('message',$msg);
        return redirect('admin/category');
    }

    public function delete(Request $request, $id)
    {
        $model = Category::find($id);
        $model->delete();
        $request->session()->flash('message','Category data has been Deleted');
        return redirect('admin/category');
    }

    public function status(Request $request, $status, $id)
    {
        $model = Category::find($id);
        $model->status=$status;
        $model->save();
        $request->session()->flash('message','Category status has been Updated');
        return redirect('admin/category');
    }

}
