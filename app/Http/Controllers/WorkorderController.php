<?php

namespace App\Http\Controllers;

use App\CustomerCar;
use App\Product_Sale;
use App\Sale;
use Illuminate\Http\Request;
use App\Customer;
use App\CustomerGroup;
use App\Supplier;
use App\Warehouse;
use App\Biller;
use App\Product;
use App\Unit;
use App\Tax;
use App\Quotation;
use App\Delivery;
use App\PosSetting;
use App\ProductQuotation;
use App\Product_Warehouse;
use App\ProductVariant;
use App\Backlog;
use App\Variant;
use DB;
use NumberToWords\NumberToWords;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Mail\UserNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class WorkorderController extends Controller
{

    public function index()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('workorder-index')){
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if(empty($all_permission))
                $all_permission[] = 'dummy text';
            $lims_quotation_all = Sale::with('biller', 'customer', 'user',"CustomerCar")->orderBy('id', 'desc')->get();
            return view('workorder.index', compact('lims_quotation_all', 'all_permission'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function create()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('workorder-add')){
            $lims_biller_list = Biller::where('is_active', true)->get();
            $lims_warehouse_list = Warehouse::where('is_active', true)->get();
            $lims_customer_list = Customer::where('is_active', true)->get();
            $lims_supplier_list = Supplier::where('is_active', true)->get();
            $lims_tax_list = Tax::where('is_active', true)->get();

            return view('workorder.create', compact('lims_biller_list', 'lims_warehouse_list', 'lims_customer_list', 'lims_supplier_list', 'lims_tax_list'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function store(Request $request)
    {
		
        $data = $request->except(['document','backlog']);
        //$data = $request->except('document');
        //return dd($data);
		
        $data['user_id'] = Auth::id();
        $data['sale_status']=0;
        $data['payment_status']=1;
		$last_serial=sale::where("reference_no",'LIKE',"#WO-%")->orderBy("reference_no","desc")->first();
		if(!empty($last_serial)){
				$arr=explode("#WO-",$last_serial->reference_no);
				//dd($arr);
				$new_serial=(int)$arr[1] +1;
				$new_serial=str_pad($new_serial, 5, '0', STR_PAD_LEFT);
				
				$new_arr=["#WO-",$new_serial];
				$new_serial=implode($new_arr);
			}else{
				$new_serial="#WO-00000";
			}
        $data['reference_no'] = $new_serial;
		//dd($data);
        $lims_quotation_data = sale::create($data);
		if(count($request->backlog)>0){
			$backlog=Backlog::where("car_id",$request->car_id)->where("status",0)->delete();
			foreach($request->backlog as $b){
				if(!empty($b) && $b != ""){
					$backlog=new Backlog();
					$backlog->title=$b;
					$backlog->status=0;
					$backlog->car_id=$request->car_id;
					$backlog->save();
				}
			}
			
		}

        $message = 'Work Order created successfully';

        return redirect('workorder')->with('message', $message);
    }


    public function getCustomerGroup($id)
    {
         $lims_customer_data = Customer::find($id);
         $lims_customer_group_data = CustomerGroup::find($lims_customer_data->customer_group_id);
         return $lims_customer_group_data->percentage;
    }

    public function getCustomerCars($id)
    {
        $lims_customer_data = CustomerCar::where('customer_id',$id)->get();
        return $lims_customer_data;
    }

    public function getProduct($id)
    {
        $product_code = [];
        $product_name = [];
        $product_qty = [];
        $product_data = [];
        //retrieve data of product without variant
        $lims_product_warehouse_data = Product_Warehouse::where('warehouse_id', $id)->whereNull('variant_id')->get();
        foreach ($lims_product_warehouse_data as $product_warehouse) 
        {
            $product_qty[] = $product_warehouse->qty;
            $lims_product_data = Product::find($product_warehouse->product_id);
            $product_code[] =  $lims_product_data->code;
            $product_name[] = $lims_product_data->name;
            $product_type[] = $lims_product_data->type;
            $product_id[] = $lims_product_data->id;
            $product_list[] = null;
            $qty_list[] = null;
        }
        //retrieve data of product with variant
        $lims_product_warehouse_data = Product_Warehouse::where('warehouse_id', $id)->whereNotNull('variant_id')->get();
        foreach ($lims_product_warehouse_data as $product_warehouse)
        {
            $product_qty[] = $product_warehouse->qty;
            $lims_product_data = Product::find($product_warehouse->product_id);
            $lims_product_variant_data = ProductVariant::select('item_code')->FindExactProduct($product_warehouse->product_id, $product_warehouse->variant_id)->first();
            $product_code[] =  $lims_product_variant_data->item_code;
            $product_name[] = $lims_product_data->name;
            $product_type[] = $lims_product_data->type;
            $product_id[] = $lims_product_data->id;
            $product_list[] = null;
            $qty_list[] = null;
        }
        //retrieve product data of digital and combo
        $lims_product_data = Product::whereNotIn('type', ['standard'])->where('is_active', true)->get();
        foreach ($lims_product_data as $product) 
        {
            $product_qty[] = $product->qty;
            $lims_product_data = $product->id;
            $product_code[] =  $product->code;
            $product_name[] = $product->name;
            $product_type[] = $product->type;
            $product_id[] = $product->id;
            $product_list[] = $product->product_list;
            $qty_list[] = $product->qty_list;
        }
        $product_data = [$product_code, $product_name, $product_qty, $product_type, $product_id, $product_list, $qty_list];
        return $product_data;
    }
	public function getrow($id){
		$wo=Sale::where("id",$id)->first();
		return $wo;
	}
    public function limsProductSearch(Request $request)
    {
        $todayDate = date('Y-m-d');
        $product_code = explode(" ",$request['data']);
        $product_variant_id = null;
        $lims_product_data = Product::where('code', $product_code[0])->first();
        if(!$lims_product_data) {
            $lims_product_data = Product::join('product_variants', 'products.id', 'product_variants.product_id')
                ->select('products.*', 'product_variants.id as product_variant_id', 'product_variants.item_code', 'product_variants.additional_price')
                ->where('product_variants.item_code', $product_code)
                ->first();
            $product_variant_id = $lims_product_data->product_variant_id;
            $lims_product_data->code = $lims_product_data->item_code;
            $lims_product_data->price += $lims_product_data->additional_price;
        }
        $product[] = $lims_product_data->name;
        $product[] = $lims_product_data->code;
        if($lims_product_data->promotion && $todayDate <= $lims_product_data->last_date){
            $product[] = $lims_product_data->promotion_price;
        }
        else
            $product[] = $lims_product_data->price;

        if($lims_product_data->tax_id) {
            $lims_tax_data = Tax::find($lims_product_data->tax_id);
            $product[] = $lims_tax_data->rate;
            $product[] = $lims_tax_data->name;
        }
        else{
            $product[] = 0;
            $product[] = 'No Tax';
        }
        $product[] = $lims_product_data->tax_method;
        if($lims_product_data->type == 'standard'){
            $units = Unit::where("base_unit", $lims_product_data->unit_id)
                        ->orWhere('id', $lims_product_data->unit_id)
                        ->get();
            $unit_name = array();
            $unit_operator = array();
            $unit_operation_value = array();
            foreach ($units as $unit) {
                if($lims_product_data->sale_unit_id == $unit->id) {
                    array_unshift($unit_name, $unit->unit_name);
                    array_unshift($unit_operator, $unit->operator);
                    array_unshift($unit_operation_value, $unit->operation_value);
                }
                else {
                    $unit_name[]  = $unit->unit_name;
                    $unit_operator[] = $unit->operator;
                    $unit_operation_value[] = $unit->operation_value;
                }
            }
            
            $product[] = implode(",",$unit_name) . ',';
            $product[] = implode(",",$unit_operator) . ',';
            $product[] = implode(",",$unit_operation_value) . ',';
        }
        else {
            $product[] = 'n/a'. ',';
            $product[] = 'n/a'. ',';
            $product[] = 'n/a'. ',';
        }
        $product[] = $lims_product_data->id;
        $product[] = $product_variant_id;
        return $product;
    }

    public function productQuotationData($id)
    {
        $product_quotation=array();
        $lims_product_quotation_data = Product_Sale::where('sale_id', $id)->get();
		$sale=Sale::where("id",$id)->first();
		//$total=0;
        foreach ($lims_product_quotation_data as $key => $product_quotation_data) {
            $product = Product::where("id",$product_quotation_data->product_id)->first();
            if(!empty($product)){
                if($product_quotation_data->variant_id) {
                    $lims_product_variant_data = ProductVariant::select('item_code')->FindExactProduct($product_quotation_data->product_id, $product_quotation_data->variant_id)->first();
                    $product->code = $lims_product_variant_data->item_code;
                }
                if($product_quotation_data->sale_unit_id){
                    $unit_data = Unit::find($product_quotation_data->sale_unit_id);
                    $unit = $unit_data->unit_code;
                }
                else
                    $unit = '';

                $product_quotation[0][$key] = $product->name . ' [' . $product->code . ']';
                $product_quotation[1][$key] = $product_quotation_data->qty;
                $product_quotation[2][$key] = $unit;
                $product_quotation[3][$key] = $product_quotation_data->tax;
                $product_quotation[4][$key] = $product_quotation_data->tax_rate;
                $product_quotation[5][$key] = $product_quotation_data->discount;
                $product_quotation[6][$key] = $product_quotation_data->total;
				//$total= $total + $product_quotation_data->total;
            }
        }
		$product_quotation["order_discount"]=$sale->order_discount;
		$product_quotation["total_price"]=$sale->total_price;
		$product_quotation["grand_total"]=$sale->grand_total;
		$product_quotation["consumables"]=$sale->consumables;
		
		//$product_quotation["total"]=$total;
		//dd($product_quotation);
        return $product_quotation;
    }

    public function edit($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('workorder-edit')){
            $lims_customer_list = Customer::where('is_active', true)->get();
            $lims_warehouse_list = Warehouse::where('is_active', true)->get();
            $lims_biller_list = Biller::where('is_active', true)->get();
            $lims_supplier_list = Supplier::where('is_active', true)->get();
            $lims_tax_list = Tax::where('is_active', true)->get();
            $lims_quotation_data = Sale::with(["CustomerCar","CustomerCar.backlog"=>function($q){
				$q->where("status",0);
			}])->find($id);
			//dd($lims_quotation_data);
            $lims_product_quotation_data = Product_Sale::where('sale_id', $id)->get();
			$cm=Sale::where("car_id",$lims_quotation_data->car_id)->where("type","CM")->orderBy("id","desc")->first();
			$pm=Sale::where("car_id",$lims_quotation_data->car_id)->where("type","PM")->orderBy("id","desc")->first();
            return view('workorder.edit',compact('lims_customer_list', 'lims_warehouse_list', 'lims_biller_list', 'lims_tax_list', 'lims_quotation_data','lims_product_quotation_data', 'lims_supplier_list',"cm","pm"));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }
	public function getbacklog($id){
		$backlogs=Backlog::where("car_id",$id)->where("status",0)->get();
		$cm=Sale::where("car_id",$id)->where("type","CM")->orderBy("id","desc")->first();
		$pm=Sale::where("car_id",$id)->where("type","PM")->orderBy("id","desc")->first();
		$arr=['backlogs'=>$backlogs,"cm"=>$cm,"pm"=>$pm];
		return $arr;
	}
	public function getallbacklog($id){
		$backlogs=Backlog::where("car_id",$id)->get();
		$arr=["backlogs"=>$backlogs,'car_id'=>$id];
		return $arr;
	}
	public function setbacklog($id,$car_id){
		$backlog="";
		if($id!="new"){
			$backlog=Backlog::where("id",$id)->first();
		}
		$view = view("cars.backlog",compact("backlog","car_id"))->render();
			return response()->json(['html'=>$view]);
	}
	public function backlog_form(Request $request){
		if($request->id !="new"){
			$backlog=Backlog::where("id",$request->id)->first();
			$backlog->title=$request->title;
			$backlog->save();
			return "updated successfule";
		}else{
			$backlog=new Backlog();
			$backlog->title=$request->title;
			$backlog->car_id=$request->car_id;
			$backlog->status=0;
			$backlog->save();
			return "Saved successfule";
		}
		return "operation field";
	}
	public function delbacklog($id){
		$backlog=Backlog::where("id",$id)->first();
		$car_id="";
		if(!empty($backlog)){
			$car_id=$backlog->car_id;
			$backlog->delete();
			$arr=["msg"=>"success","car_id"=>$car_id] ;
			return $arr;
		}
		$arr=["msg"=>"failed","car_id"=>$car_id] ;
		
		return $arr;
	}
    public function update(Request $request, $id)
    { //dd($request);
        $data = $request->except('document');

        $lims_quotation_data = Sale::find($id);
        $lims_quotation_data->update($data);

		if(count($request->backlog)>0){
			$backlog_arr=Backlog::where("car_id",$request->car_id)->where("status",0)->pluck("id")->toArray();
			
			foreach($request->backlog as $key=>$b){
				if($request->id[$key]=="new"){
					if(!empty($b) && $b != ""){
						$backlog=new Backlog();
						$backlog->title=$b;
						$backlog->status=0;
						$backlog->car_id=$request->car_id;
						$backlog->save();
					}
				}else{
					$backlog=Backlog::where("id",$request->id[$key])->first();
					if(!empty($backlog)){
						$backlog->title=$b;
						$backlog->save();
						if (($key = array_search($backlog->id, $backlog_arr)) !== false) {
							unset($backlog_arr[$key]);
						}	
						
					}
				}
			}
			
		}
		if(count($backlog_arr) > 0){
			$backlog=Backlog::whereIn('id', $backlog_arr)->delete();
		}
		
        $message = 'Work Order updated successfully';

        return redirect('workorder')->with('message', $message);
    }

    public function createSale($id)
    {
        $lims_customer_list = Customer::where('is_active', true)->get();
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();
        $lims_biller_list = Biller::where('is_active', true)->get();
        $lims_tax_list = Tax::where('is_active', true)->get();
        $lims_quotation_data = Quotation::find($id);
        $lims_product_quotation_data = ProductQuotation::where('quotation_id', $id)->get();
        $lims_pos_setting_data = PosSetting::latest()->first();
        return view('quotation.create_sale',compact('lims_customer_list', 'lims_warehouse_list', 'lims_biller_list', 'lims_tax_list', 'lims_quotation_data','lims_product_quotation_data', 'lims_pos_setting_data'));
    }

    public function createPurchase($id)
    {
        $lims_supplier_list = Supplier::where('is_active', true)->get();
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();
        $lims_tax_list = Tax::where('is_active', true)->get();
        $lims_quotation_data = Quotation::find($id);
        $lims_product_quotation_data = ProductQuotation::where('quotation_id', $id)->get();
        $lims_product_list_without_variant = $this->productWithoutVariant();
        $lims_product_list_with_variant = $this->productWithVariant();

        return view('quotation.create_purchase',compact('lims_product_list_without_variant', 'lims_product_list_with_variant', 'lims_supplier_list', 'lims_warehouse_list', 'lims_tax_list', 'lims_quotation_data','lims_product_quotation_data'));
    }

    public function productWithoutVariant()
    {
        return Product::ActiveStandard()->select('id', 'name', 'code')
                ->whereNull('is_variant')->get();
    }

    public function productWithVariant()
    {
        return Product::join('product_variants', 'products.id', 'product_variants.product_id')
                ->ActiveStandard()
                ->whereNotNull('is_variant')
                ->select('products.id', 'products.name', 'product_variants.item_code')
                ->orderBy('position')->get();
    }

    public function deleteBySelection(Request $request)
    {
        $quotation_id = $request['quotationIdArray'];
        foreach ($quotation_id as $id) {
            $lims_quotation_data = Quotation::find($id);
            $lims_product_quotation_data = ProductQuotation::where('quotation_id', $id)->get();
            foreach ($lims_product_quotation_data as $product_quotation_data) {
                $product_quotation_data->delete();
            }
            $lims_quotation_data->delete();
        }
        return 'Work Orders deleted successfully!';
    }

    public function destroy($id)
    {
        $lims_quotation_data = Sale::find($id);
		$products=Product_Sale::where("sale_id",$id)->get();
			
			//dd($products);
			foreach($products as $p){
				
				if($p->is_dispatched == 1){
					
					$product=Product::where("id",$p->product_id)->first();
					//dd($product);
					//dd($product->qty + $p->qty);
					if($product->type=="standard"){
						$product->qty=$product->qty + $p->qty;
						$product->save();
					}
				}
				//dd($p);
				$p->delete();
			}
        $lims_quotation_data->delete();
        return redirect('workorder')->with('not_permitted', 'Work Order deleted successfully');
    }


    public function deliverInvoice(Request $request)
    {
        $sale = Sale::find($request->id);

        $sale->is_invoice_deliver = $request->is_invoice_deliver;
        $sale->invoice_deliver_date = $request->invoice_deliver_date;
        $sale->save();

        return redirect()->back();
    }


	public function workorder_cancel($id){
		$wo=Sale::where("id",$id)->first();
		
		$msg ="Work Orders canceled failed!";
		if(!empty($wo)){
			$products=Product_Sale::where("sale_id",$wo->id)->get();
			
			//dd($products);
			foreach($products as $p){
				
				if($p->is_dispatched == 1){
					
					$product=Product::where("id",$p->product_id)->first();
					//dd($product);
					//dd($product->qty + $p->qty);
					if($product->type=="standard"){
						$product->qty=$product->qty + $p->qty;
						$product->save();
					}
				}
				//dd($p);
				$p->delete();
			}
			$wo->workorder_status=4;
			$wo->sale_status=0;
			$wo->save();
			$msg ="Work Orders canceled successfully!";
		}
		return redirect('/workorder')->with("message",$msg);
	}
	public function workorder_closed($id){
		$wo=Sale::where("id",$id)->first();
		$msg ="Work Orders closed failed!";
		if(!empty($wo)){
			
			$wo->workorder_status=3;
			
			$wo->save();
			$msg ="Work Orders closed successfully!";
		}
		return redirect('/workorder')->with("message",$msg);
	}
	public function workorder_completed($id,Request $request){
		if(isset($request->backlog) && count($request->backlog)>0){
			foreach($request->backlog as $b){
				$backlog=Backlog::where("id",$b)->first();
				if(!empty($backlog)){
					$backlog->status=1;
					$backlog->save();
				}
				
			}
		}
		$wo=Sale::where("id",$id)->first();
		//dd($id ."  == ".$request->backlog);
		$msg ="Work Orders completed failed!";
		if(!empty($wo)){
            $wo->workorder_status=5;
			$wo->completed_at=date('Y-m-d H:i:s');
			
			$wo->save();
			$msg ="Work Orders completed successfully!";
		}
		return redirect('/workorder')->with("message",$msg);
	}


    public function invoiceIndex(){
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('workorder-index')){
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if(empty($all_permission))
                $all_permission[] = 'dummy text';
            $lims_quotation_all = Sale::with('biller', 'customer', 'user')->orderBy('id', 'desc')->get();
            return view('workorder.invoices-index', compact('lims_quotation_all', 'all_permission'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }
}
