<?php

namespace App\Http\Controllers;

use App\Customer;
use App\CustomerCar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Auth;
use Spatie\Permission\Models\Permission;
use App\Mail\UserNotification;

class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id=0)
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('cars-index')){
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if(empty($all_permission))
                $all_permission[] = 'dummy text';
            if($id==0)
            {
                $cars=CustomerCar::all();
        } else {
                $cars=CustomerCar::where('customer_id',$id)->get();
            }

            return view('cars.index', compact('cars', 'all_permission'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function show($id=0)
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('cars-index')){
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if(empty($all_permission))
                $all_permission[] = 'dummy text';
            if($id==0)
            {
                $cars=CustomerCar::all();
            } else {
                $cars=CustomerCar::where('customer_id',$id)->get();
            }

            return view('cars.index', compact('cars', 'all_permission'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function create()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('cars-add')){
            $customers = Customer::where('is_active',true)->get();
            return view('cars.create', compact('customers'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'customer_id' => 'required',
            'model' => 'required',
            'mileage' => 'required',
            'chassis' => 'required|unique:customer_cars',
            'plate' => 'required|unique:customer_cars'
        ]);
        $lims_customerCar_data = $request->all();
        $message = 'Car created successfully';

        CustomerCar::create($lims_customerCar_data);

            return redirect('cars')->with('create_message', $message);
    }

    public function edit($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('cars-edit')){
            $lims_customercar_data = CustomerCar::find($id);
            $customers = Customer::where('is_active',true)->get();
            return view('cars.edit', compact('lims_customercar_data','customers'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'customer_id' => 'required',
            'model' => 'required',
            'mileage' => 'required',
            'chassis' => 'required|unique:customer_cars,chassis,'.$id,
            'plate' => 'required|unique:customer_cars,plate,'.$id
        ]);

        $input = $request->all();
        $lims_customer_data = CustomerCar::find($id);
        $lims_customer_data->update($input);
        return redirect('cars')->with('edit_message', 'Data updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function deleteBySelection(Request $request)
    {
        $customer_id = $request['customerIdArray'];
        foreach ($customer_id as $id) {
            $lims_customer_data = CustomerCar::find($id)->delete();
        }
        return 'Cars deleted successfully!';
    }

    public function destroy($id)
    {
        $lims_customer_data = CustomerCar::find($id)->delete();
        return redirect('cars')->with('not_permitted','Data deleted Successfully');
    }
}
