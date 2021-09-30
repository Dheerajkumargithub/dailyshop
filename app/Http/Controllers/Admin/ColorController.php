<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $results['data'] = Color::all();
        return view('admin/color', $results);
    }

    public function manage_color(Request $request ,$id ='')
    {
        if ($id>0) {
            $arr = Color::where(['id'=>$id])->get();

            $result['color'] = $arr['0']->color;
            $result['id'] = $arr['0']->id;

        }else{
            $result['color']='';
            $result['id'] =0;
        }
        return view('admin/manage_color', $result);
    }

    public function manage_color_process(Request $request)
    {
        //return $request->post();
        $request->validate([
            'color'=>'required | unique:colors,color,'.$request->post('id'),
        ]);

        $model = new Color;
        if ($request->post('id')>0) {
            $model =Color::find($request->post('id'));
            $msg = "Color data has been Updated";
         }else{
            $model = new Color;
            $msg = "Color data has been inserted";
         } 
        $model->color = $request->post('color');
        $model->status = 1;
        $model->save();
        $request->session()->flash('message',$msg);
        return redirect('admin/color');
    }

    public function delete(Request $request, $id)
    {
        $model = Color::find($id);
        $model->delete();
        $request->session()->flash('message','Color data has been Deleted');
        return redirect('admin/color');
    }

    public function status(Request $request, $status, $id)
    {
        $model = Color::find($id);
        $model->status=$status;
        $model->save();
        $request->session()->flash('message','Color status has been Updated');
        return redirect('admin/color');
    }
}
