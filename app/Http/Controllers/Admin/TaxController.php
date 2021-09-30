<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
   
   public function index()
    {
        $results['data'] = Tax::all();
        return view('admin/tax', $results);
    }

    public function manage_tax(Request $request ,$id ='')
    {
        if ($id>0) {
            $arr = Tax::where(['id'=>$id])->get();

            $result['tax_desc'] = $arr['0']->tax_desc;
            $result['tax_value'] = $arr['0']->tax_value;
            $result['status'] = $arr['0']->status;
            $result['id'] = $arr['0']->id;
            
        }else{
            $result['tax_desc'] = '';
            $result['tax_value'] = '';
            $result['status']='';
            $result['id'] =0;
        }
        return view('admin/manage_tax', $result);
    }

    public function manage_tax_process(Request $request)
    {

        $request->validate([
            'tax_value'=>'required | unique:taxes,tax_value,'.$request->post('id'),
        ]);
        //return $request->post();

        $model = new Tax;
        if ($request->post('id')>0) {
            $model =Tax::find($request->post('id'));
            $msg = "Tax data has been Updated";
         }else{
            $model = new Tax;
            $msg = "Tax data has been inserted";
         } 

        $model->tax_value = $request->post('tax_value');
        $model->tax_desc = $request->post('tax_desc');
        $model->status = 1;
        $model->save();
        $request->session()->flash('message',$msg);
        return redirect('admin/tax');
    }

    public function delete(Request $request, $id)
    {
        $model = Tax::find($id);
        $model->delete();
        $request->session()->flash('message','Tax data has been Deleted');
        return redirect('admin/tax');
    }

    public function status(Request $request, $status, $id)
    {
        $model = Tax::find($id);
        $model->status=$status;
        $model->save();
        $request->session()->flash('message','Tax status has been Updated');
        return redirect('admin/tax');
    }  
}
