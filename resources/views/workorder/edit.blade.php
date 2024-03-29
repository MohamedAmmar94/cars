@extends('layout.main') @section('content')
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('file.update_workorder')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        {!! Form::open(['route' => ['workorder.update', $lims_quotation_data->id], 'method' => 'put', 'files' => true, 'id' => 'payment-form']) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{trans('file.customer')}} *</label>
                                            <select id="customer_id" name="customer_id" required class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="{{ trans('file.choose')." ".trans('file.customer')}} ..">
                                                @foreach($lims_customer_list as $customer)
                                                    <option value="{{$customer->id}}" {{ ($lims_quotation_data->customer_id==$customer->id) ? 'selected' : '' }}>{{$customer->name . ' (' . $customer->phone_number . ')'}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{trans('file.customercar')}} *</label>
                                            <select id="car_id" name="car_id" required  class="selectpicker form-control" data-live-search="true"  data-live-search-style="begins" title=" {{ trans('file.choose')." ".trans('file.customercar')}} ..">
                                                @foreach($lims_quotation_data->customer->cars as $car)
                                                    <option value="{{$car->id}}" {{ ($lims_quotation_data->car_id==$car->id) ? 'selected' : '' }}>{{$car->chassis}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
										<div class="form-group">
											<label>{{trans('file.workorder_type')}}*</label>
											<select required name="type" class="selectpicker form-control" data-live-search="true" id="type" data-live-search-style="begins" title=" Work Oreder Type"}} ..">
												<option value="PM" {{ ($lims_quotation_data->type =="pm") ? 'selected' : ""}} >PM</option>
												<option value="CM"  {{ ($lims_quotation_data->type =="CM") ? 'selected' : ""}}>CM</option>
												<option value="RF" {{ ($lims_quotation_data->type =="RF") ? 'selected' : ""}}>RF</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>{{trans('file.car_millage')}} *</label>
											<input type="number" class=" form-control" name="mill_age" value="{{$lims_quotation_data->mill_age ?? ""}}" required>
										</div>
									</div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" hidden>
                                            <label>{{trans('file.Supplier')}}</label>
                                            <select name="supplier_id" class="selectpicker form-control" data-live-search="true" id="supplier-id" data-live-search-style="begins" title="Select Supplier...">
                                                @foreach($lims_supplier_list as $supplier)
                                                    <option value="{{$supplier->id}}">{{$supplier->name . ' (' . $supplier->company_name . ')'}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6" hidden>
                                        <div class="form-group">
                                            <label>{{trans('file.Warehouse')}} *</label>
                                            <select id="warehouse_id" name="warehouse_id" required class=" form-control" data-live-search="true" data-live-search-style="begins" title="Select warehouse...">
                                                @foreach($lims_warehouse_list as $warehouse)
                                                    <option {{ ($warehouse->id==1) ? 'selected':'' }} value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>




                                <!-- <?php /* ?>
                                <div class="row">
                                	<div class="col-md-12">
                                		<div class="form-group">
                                			<label>{{trans('file.Note')}}</label>
                                			<textarea rows="5" name="sale_note" class="form-control"></textarea>
                                		</div>
                                	</div>
                                </div><?php */ ?>-->
								<div class="row">
                                	<div class="col-md-6">
                                		<div class="form-group">
                                			<label>{{trans('file.backlog')}}</label>
											 
												  <div class="customer_records" style="margin: 10px 0;">
												  <input name="id[]" value="new" type="hidden">
													<input name="backlog[]" class="form-control" 
														style="width: 92%;display:inline-flex;" placeholder="add backlog" type="text" >
													<a class="extra-fields-customer btn btn-primary" href="javascript:void(0);">
														<i class="fa fa-plus-circle"></i>
														</a>
												  </div>

												  <div class="customer_records_dynamic">
												  @if(isset($lims_quotation_data->CustomerCar->backlog) && count($lims_quotation_data->CustomerCar->backlog)>0)
														@foreach($lims_quotation_data->CustomerCar->backlog as $backlog)
															<div class="remove" style="margin: 10px 0;">
																<input name="id[]" value="{{$backlog->id}}" type="hidden">
																<input name="backlog[]" class="form-control" style="width: 92%;display:inline-flex;" placeholder="add backlog" type="text" value="{{$backlog->title}}">
																<a href="javascript:void(0);" class="btn btn-danger remove-field btn-remove-customer">
																	<i class="fa fa-minus-circle"></i>
																</a>
															</div>
														@endforeach
												  @endif
												  </div>
										</div>
                                	</div>
									<div class="col-md-6 perivous-km">
									@if(isset($pm) && !empty($pm))
										<div class="row">
											<div class="col-md-2"> PM :</div>
											<div class="col-md-3"> {{$pm->mill_age}}</div>
											<div class="col-md-6"> {{$pm->created_at}}</div>
										</div>
									@endif
									@if(isset($cm) && !empty($cm))
										<div class="row">
											<div class="col-md-2"> CM :</div>
											<div class="col-md-3"> {{$cm->mill_age}}</div>
											<div class="col-md-6"> {{$cm->created_at}}</div>
										</div>
									@endif
									</div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary" id="submit-button">
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

   </section>

<script type="text/javascript">

$('#car_id').on('change', function() {
   var id=this.value; 
   
  // console.log(id);
   $.get('/workorder/getbacklog/' + id, function(data) {
	  
	  
	   $('.customer_records_dynamic').html("");
	   $('.perivous-km').html("");
	 //  console.log(data);
	   if(data.backlogs.length >0){
			$.each(data.backlogs, function(index) {   
		
				$('.customer_records_dynamic').append('<div class="remove" style="margin: 10px 0;"><input name="id[]" value="'+data.backlogs[index].id+'" type="hidden"><input name="backlog[]" class="form-control" style="width: 92%;display:inline-flex;" placeholder="add backlog" type="text"value='+data.backlogs[index].title+'><a href="javascript:void(0);" class="btn btn-danger remove-field btn-remove-customer"><i class="fa fa-minus-circle"></i></a></div>');
				
			});
	   }
	   if(data.pm !=null){
		   $('.perivous-km').append('<div class="row"><div class="col-md-2"> PM :</div><div class="col-md-3"> '+data.pm.mill_age+'</div><div class="col-md-6"> '+data.cm.created_at+'</div></div>');
	   }
	   if(data.cm !=null){
		   $('.perivous-km').append('<div class="row"><div class="col-md-2"> CM :</div><div class="col-md-3"> '+data.cm.mill_age+'</div><div class="col-md-6"> '+data.cm.created_at+'</div></div>');
	   }
   });
   
});
$('.extra-fields-customer').click(function() {
		  $('.customer_records').clone().appendTo('.customer_records_dynamic');
		  $('.customer_records_dynamic .customer_records').addClass('single remove');
		  $('.single .extra-fields-customer').remove();
		  $('.single').append('<a href="javascript:void(0);" class="btn btn-danger remove-field btn-remove-customer"><i class="fa fa-minus-circle"></i></a>');
		  $('.customer_records_dynamic > .single').attr("class", "remove");

		  $('.customer_records_dynamic input').each(function() {
			var count = 0;
			var fieldname = $(this).attr("name");
			$(this).attr('name', fieldname);
			count++;
		  });

		});

		$(document).on('click', '.remove-field', function(e) {
		  $(this).parent('.remove').remove();
		  e.preventDefault();
		});
    $("ul#workorder").siblings('a').attr('aria-expanded','true');
    $("ul#workorder").addClass("show");
    
// array data depend on warehouse
var lims_product_array = [];
var product_code = [];
var product_name = [];
var product_qty = [];
var product_type = [];
var product_id = [];
var product_list = [];
var qty_list = [];

// array data with selection
var product_price = [];
var product_discount = [];
var tax_rate = [];
var tax_name = [];
var tax_method = [];
var unit_name = [];
var unit_operator = [];
var unit_operation_value = [];

// temporary array
var temp_unit_name = [];
var temp_unit_operator = [];
var temp_unit_operation_value = [];

var exist_code = [];
var exist_qty = [];
var rowindex;
var customer_group_rate;
var row_product_price;
var pos;

var rownumber = $('table.order-list tbody tr:last').index();

for(rowindex  =0; rowindex <= rownumber; rowindex++){

    product_price.push(parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.product-price').val()));
    exist_code.push($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)').text());
    var total_discount = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(5)').text());
    var quantity = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val());
    exist_qty.push(quantity);
    product_discount.push((total_discount / quantity).toFixed(2));
    tax_rate.push(parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-rate').val()));
    tax_name.push($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-name').val());
    tax_method.push($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-method').val());
    temp_unit_name = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.sale-unit').val().split(',');
    unit_name.push($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.sale-unit').val());
    unit_operator.push($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.sale-unit-operator').val());
    unit_operation_value.push($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.sale-unit-operation-value').val());
    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.sale-unit').val(temp_unit_name[0]);
}

$('.selectpicker').selectpicker({
    style: 'btn-link',
});
    $('.selectpicker').selectpicker('refresh');
$('[data-toggle="tooltip"]').tooltip();



$('#item').text($('input[name="item"]').val() + '(' + $('input[name="total_qty"]').val() + ')');
$('#subtotal').text(parseFloat($('input[name="total_price"]').val()).toFixed(2));
$('#order_tax').text(parseFloat($('input[name="order_tax"]').val()).toFixed(2));
if(!$('input[name="order_discount"]').val())
    $('input[name="order_discount"]').val('0.00');
$('#order_discount').text(parseFloat($('input[name="order_discount"]').val()).toFixed(2));
if(!$('input[name="shipping_cost"]').val())
    $('input[name="shipping_cost"]').val('0.00');
$('#shipping_cost').text(parseFloat($('input[name="shipping_cost"]').val()).toFixed(2));
$('#grand_total').text(parseFloat($('input[name="grand_total"]').val()).toFixed(2));

var id = $('select[name="customer_id"]').val();
$.get('../getcustomergroup/' + id, function(data) {
    customer_group_rate = (data / 100);
});

var id = $('select[name="warehouse_id"]').val();
$.get('../getproduct/' + id, function(data) {
    lims_product_array = [];
    product_code = data[0];
    product_name = data[1];
    product_qty = data[2];
    product_type = data[3];
    product_id = data[4];
    product_list = data[5];
    qty_list = data[6];

    $.each(product_code, function(index) {        
        lims_product_array.push(product_code[index] + ' (' + product_name[index] + ')');
    });
});

$('select[name="customer_id"]').on('change', function() {
    var id = $(this).val();
    $.get('../getcustomergroup/' + id, function(data) {
        customer_group_rate = (data / 100);
    });
});

    $('select[name="customer_id"]').on('change', function() {
        var id = $(this).val();
        $.get('../getcustomercars/' + id, function(data) {
            $('#car_id').html('');
            $('.selectpicker').selectpicker('refresh');
            var customer_cars = data;
            $.each(customer_cars,function (i,car){
                $('#car_id').append(new Option(car.chassis,car.id));
                $('.selectpicker').selectpicker('refresh');
            });

        });
    });

$('select[name="warehouse_id"]').on('change', function() {
    var id = $(this).val();
    $.get('../getproduct/' + id, function(data) {
        lims_product_array = [];
        product_code = data[0];
        product_name = data[1];
        product_qty = data[2];
        product_type = data[3];
        product_id = data[4];
        product_list = data[5];
        qty_list = data[6];
        $.each(product_code, function(index) {
            lims_product_array.push(product_code[index] + ' (' + product_name[index] + ')');
        });
    });
});

$('#lims_productcodeSearch').on('input', function(){
    var customer_id = $('#customer_id').val();
    var warehouse_id = $('select[name="warehouse_id"]').val();
    temp_data = $('#lims_productcodeSearch').val();
    if(!customer_id){
        $('#lims_productcodeSearch').val(temp_data.substring(0, temp_data.length - 1));
        alert('Please select Customer!');
    }
    else if(!warehouse_id){
        $('#lims_productcodeSearch').val(temp_data.substring(0, temp_data.length - 1));
        alert('Please select Warehouse!');
    }

});

var lims_productcodeSearch = $('#lims_productcodeSearch');

lims_productcodeSearch.autocomplete({
    source: function(request, response) {
        var matcher = new RegExp(".?" + $.ui.autocomplete.escapeRegex(request.term), "i");
        response($.grep(lims_product_array, function(item) {
            return matcher.test(item);
        }));
    },
    response: function(event, ui) {
        if (ui.content.length == 1) {
            var data = ui.content[0].value;
            $(this).autocomplete( "close" );
            productSearch(data);
        };
    },
    select: function(event, ui) {
        var data = ui.item.value;
        productSearch(data);
    }
});

//Change quantity
$("#myTable").on('input', '.qty', function() {
    rowindex = $(this).closest('tr').index();
    if($(this).val() < 1 && $(this).val() != '') {
      $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(1);
      alert("Quantity can't be less than 1");
    }
    checkQuantity($(this).val(), true);
});


//Delete product
$("table.order-list tbody").on("click", ".ibtnDel", function(event) {
    rowindex = $(this).closest('tr').index();
    product_price.splice(rowindex, 1);
    product_discount.splice(rowindex, 1);
    tax_rate.splice(rowindex, 1);
    tax_name.splice(rowindex, 1);
    tax_method.splice(rowindex, 1);
    unit_name.splice(rowindex, 1);
    unit_operator.splice(rowindex, 1);
    unit_operation_value.splice(rowindex, 1);
    $(this).closest("tr").remove();
    calculateTotal();
});

//Edit product
$("table.order-list").on("click", ".edit-product", function() {
    rowindex = $(this).closest('tr').index();
    edit();
});
  //update product
$('button[name="update_btn"]').on("click", function() {
    var edit_discount = $('input[name="edit_discount"]').val();
    var edit_qty = $('input[name="edit_qty"]').val();
    var edit_unit_price = $('input[name="edit_unit_price"]').val();

    if (parseFloat(edit_discount) > parseFloat(edit_unit_price)) {
        alert('Invalid Discount Input!');
        return;
    }

    if(edit_qty < 1) {
        $('input[name="edit_qty"]').val(1);
        edit_qty = 1;
        alert("Quantity can't be less than 1");
    }


});

$(window).keydown(function(e){
    if (e.which == 13) {
        var $targ = $(e.target);
        if (!$targ.is("textarea") && !$targ.is(":button,:submit")) {
            var focusNext = false;
            $(this).find(":input:visible:not([disabled],[readonly]), a").each(function(){
                if (this === e.target) {
                    focusNext = true;
                }
                else if (focusNext){
                    $(this).focus();
                    return false;
                }
            });
            return false;
        }
    }
});



function productSearch(data) {
    $.ajax({
        type: 'GET',
        url: '../lims_product_search',
        data: {
            data: data
        },
        success: function(data) {
            var flag = 1;
            $(".product-code").each(function(i) {
                if ($(this).val() == data[1]) {
                    rowindex = i;
                    var qty = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val()) + 1;
                    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(qty);
                    checkQuantity(String(qty), true);
                    flag = 0;
                }
            });
            $("input[name='product_code_name']").val('');
            if (flag) {
                var newRow = $("<tr>");
                var cols = '';
                temp_unit_name = (data[6]).split(',');
                cols += '<td>' + data[0] + '<button type="button" class="edit-product btn btn-link" data-toggle="modal" data-target="#editModal"> <i class="dripicons-document-edit"></i></button></td>';
                cols += '<td>' + data[1] + '</td>';
                cols += '<td><input type="number" class="form-control qty" name="qty[]" value="1" step="any" required/></td>';
                cols += '<td class="net_unit_price"></td>';
                cols += '<td class="discount">0.00</td>';
                cols += '<td class="tax"></td>';
                cols += '<td class="sub-total"></td>';
                cols += '<td><button type="button" class="ibtnDel btn btn-md btn-danger">{{trans("file.delete")}}</button></td>';
                cols += '<input type="hidden" class="product-code" name="product_code[]" value="' + data[1] + '"/>';
                cols += '<input type="hidden" class="product-id" name="product_id[]" value="' + data[9] + '"/>';
                cols += '<input type="hidden" name="product_variant_id[]" value="' + data[10] + '"/>';
                cols += '<input type="hidden" class="sale-unit" name="sale_unit[]" value="' + temp_unit_name[0] + '"/>';
                cols += '<input type="hidden" class="net_unit_price" name="net_unit_price[]" />';
                cols += '<input type="hidden" class="discount-value" name="discount[]" />';
                cols += '<input type="hidden" class="tax-rate" name="tax_rate[]" value="' + data[3] + '"/>';
                cols += '<input type="hidden" class="tax-value" name="tax[]" />';
                cols += '<input type="hidden" class="subtotal-value" name="subtotal[]" />';

                newRow.append(cols);
                $("table.order-list tbody").append(newRow);

                product_price.push(parseFloat(data[2]) + parseFloat(data[2] * customer_group_rate));

                product_discount.push('0.00');
                tax_rate.push(parseFloat(data[3]));
                tax_name.push(data[4]);
                tax_method.push(data[5]);
                unit_name.push(data[6]);
                unit_operator.push(data[7]);
                unit_operation_value.push(data[8]);
                rowindex = newRow.index();
                checkQuantity(1, true);
            }
        }
    });
}





$('input[name="order_discount"]').on("input", function() {
    calculateGrandTotal();
});

$('input[name="shipping_cost"]').on("input", function() {
    calculateGrandTotal();
});

$('select[name="order_tax_rate"]').on("change", function() {
    calculateGrandTotal();
});

</script>
@endsection