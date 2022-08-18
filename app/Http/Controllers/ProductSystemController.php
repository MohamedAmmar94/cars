<?php

namespace App\Http\Controllers;

use App\ProductSystem;
use Illuminate\Http\Request;
use App\Brand;
use Illuminate\Validation\Rule;

class ProductSystemController extends Controller
{

    public function index()
    {
        $productSystems = ProductSystem::where('is_active',true)->get();
        return view('product-system.create', compact('productSystems'));
    }

    public function store(Request $request)
    {
        $request->title = preg_replace('/\s+/', ' ', $request->title);
        $this->validate($request, [
            'title' => [
                'max:255',
                    Rule::unique('product_systems')->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],

            'image' => 'image|mimes:jpg,jpeg,png,gif|max:100000',
        ]);

        $input = $request->except('image');
        $input['is_active'] = true;
        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = preg_replace('/[^a-zA-Z0-9]/', '', $input['title']);
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/product_system', $imageName);
            $input['image'] = $imageName;
        }
        ProductSystem::create($input);
        return redirect()->back();
    }

    public function edit($id)
    {
        $product_system = ProductSystem::findOrFail($id);
        return $product_system;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => [
                'max:255',
                    Rule::unique('product_systems')->ignore($request->product_system_id)->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],

            'image' => 'image|mimes:jpg,jpeg,png,gif|max:100000',
        ]);
        $product_system = ProductSystem::findOrFail($request->product_system_id);
        $product_system->title = $request->title;
        $product_system->code = $request->code;
        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = preg_replace('/[^a-zA-Z0-9]/', '', $request->title);
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/product_system', $imageName);
            $product_system->image = $imageName;
        }
        $product_system->save();
        return redirect()->back();
    }

    public function importProductSystem(Request $request)
    {
        //get file
        $upload=$request->file('file');
        $ext = pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION);
        if($ext != 'csv')
            return redirect()->back()->with('not_permitted', 'Please upload a CSV file');
        $filename =  $upload->getClientOriginalName();
        $filePath=$upload->getRealPath();
        //open and read
        $file=fopen($filePath, 'r');
        $header= fgetcsv($file);
        $escapedHeader=[];
        //validate
        foreach ($header as $key => $value) {
            $lheader=strtolower($value);
            $escapedItem=preg_replace('/[^a-z]/', '', $lheader);
            array_push($escapedHeader, $escapedItem);
        }
        //looping through othe columns
        while($columns=fgetcsv($file))
        {
            if($columns[0]=="")
                continue;
            foreach ($columns as $key => $value) {
                $value=preg_replace('/\D/','',$value);
            }
           $data= array_combine($escapedHeader, $columns);

           $productSystem = ProductSystem::firstOrNew([ 'title'=>$data['title'], 'is_active'=>true ]);
            $productSystem->title = $data['title'];
            $productSystem->image = $data['image'];
            $productSystem->is_active = true;
            $productSystem->save();
        }
        return redirect()->back()->with('message', 'Product System imported successfully');
    }

    public function deleteBySelection(Request $request)
    {
        $product_system_id = $request['productSystemIdArray'];
        foreach ($product_system_id as $id) {
            $product_system = ProductSystem::findOrFail($id);
            $product_system->is_active = false;
            $product_system->save();
        }
        return 'Product System deleted successfully!';
    }

    public function destroy($id)
    {
        $product_system = ProductSystem::findOrFail($id);
        $product_system->is_active = false;
        $product_system->save();
        return redirect()->back()->with('not_permitted', 'Product System deleted successfully!');
    }

    public function exportProductSystem(Request $request)
    {
        $product_systems = $request['productSystemArray'];
        $csvData=array('productSystem Title, Image');
        foreach ($product_systems as $product_system) {
            if($product_system > 0) {
                $data = ProductSystem::where('id', $product_system)->first();
                $csvData[]=$data->title.','.$data->image;
            }   
        }        
        $filename=date('Y-m-d').".csv";
        $file_path=public_path().'/downloads/'.$filename;
        $file_url=url('/').'/downloads/'.$filename;   
        $file = fopen($file_path,"w+");
        foreach ($csvData as $exp_data){
          fputcsv($file,explode(',',$exp_data));
        }   
        fclose($file);
        return $file_url;
    }

}
