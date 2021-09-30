<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $results['data'] = Size::all();
        return view('admin/size', $results);
    }

    public function manage_size(Request $request ,$id ='')
    {
        if ($id>0) {
            $arr = Size::where(['id'=>$id])->get();

            $result['size'] = $arr['0']->size;
            $result['status'] = $arr['0']->status;
            $result['id'] = $arr['0']->id;

        }else{
            $result['size']='';
            $result['status']='';
            $result['id'] =0;
        }
        return view('admin/manage_size', $result);
    }

    public function manage_size_process(Request $request)
    {
        //return $request->post();
        $request->validate([
            'size'=>'required | unique:sizes,size,'.$request->post('id'),
        ]);

        $model = new Size;
        if ($request->post('id')>0) {
            $model =Size::find($request->post('id'));
            $msg = "Size data has been Updated";
         }else{
            $model = new Size;
            $msg = "Size data has been inserted";
         } 
        $model->size = $request->post('size');
        $model->status = 1;
        $model->save();
        $request->session()->flash('message',$msg);
        return redirect('admin/size');
    }

    public function delete(Request $request, $id)
    {
        $model = Size::find($id);
        $model->delete();
        $request->session()->flash('message','Size data has been Deleted');
        return redirect('admin/size');
    }

    public function status(Request $request, $status, $id)
    {
        $model = Size::find($id);
        $model->status=$status;
        $model->save();
        $request->session()->flash('message','Size status has been Updated');
        return redirect('admin/size');
    }
}
