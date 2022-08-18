@extends('layout.main')

@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('file.add_product')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        <form id="product-form">
                            <div class="row">
                                <div class="col-md-4" hidden>
                                    <div class="form-group">
                                        <label>{{trans('file.Product Type')}} *</strong> </label>
                                        <div class="input-group">
                                            <select name="type" required class="form-control selectpicker" id="type">
                                                <option selected value="standard">Standard</option>
                                                <option value="combo">Combo</option>
                                                <option value="digital">Digital</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('file.Product Name')}} * </label>
                                        <input type="text" name="name" class="form-control" id="name" aria-describedby="name" required>
                                        <span class="validation-msg" id="name-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('file.Product Code')}} * </label>
                                        <div class="input-group">
                                            <input type="text" name="code" class="form-control" id="code" readonly aria-describedby="code" required>
                                            <div class="input-group-append">
                                                <button id="genbutton" type="button" class="btn btn-sm btn-default" title="{{trans('file.Generate')}}"><i class="fa fa-refresh"></i></button>
                                            </div>
                                        </div>
                                        <span class="validation-msg" id="code-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('file.part_number')}} * </label>
                                        <div class="input-group">
                                            <input type="text" name="part_number" class="form-control" id="part_number"  aria-describedby="part_number" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4" hidden>
                                    <div class="form-group">
                                        <label>{{trans('file.Barcode Symbology')}} *</strong> </label>
                                        <div class="input-group">
                                            <select name="barcode_symbology" required class="form-control selectpicker">
                                                <option value="C128" selected>Code 128</option>
                                                <option value="C39">Code 39</option>
                                                <option value="UPCA">UPC-A</option>
                                                <option value="UPCE">UPC-E</option>
                                                <option value="EAN8">EAN-8</option>
                                                <option value="EAN13">EAN-13</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="digital" class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('file.Attach File')}} *</strong> </label>
                                        <div class="input-group">
                                            <input type="file" name="file" class="form-control">
                                        </div>
                                        <span class="validation-msg"></span>
                                    </div>                                
                                </div>
                                <div id="combo" class="col-md-9 mb-1">
                                    <label>{{trans('file.add_product')}}</label>
                                    <div class="search-box input-group mb-3">
                                        <button class="btn btn-secondary"><i class="fa fa-barcode"></i></button>
                                        <input type="text" name="product_code_name" id="lims_productcodeSearch" placeholder="Please type product code and select..." class="form-control" />
                                    </div>
                                    <label>{{trans('file.Combo Products')}}</label>
                                    <div class="table-responsive">
                                        <table id="myTable" class="table table-hover order-list">
                                            <thead>
                                                <tr>
                                                    <th>{{trans('file.product')}}</th>
                                                    <th>{{trans('file.Quantity')}}</th>
                                                    <th>{{trans('file.Unit Price')}}</th>
                                                    <th><i class="dripicons-trash"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('file.Vehicles')}} * </label>
                                        <div class="input-group">
                                            <select name="category_id" id="category_id" required class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="اختر التصنيف ...">
                                                @foreach($lims_category_list as $category)
                                                    <option  data-code="{{$category->code}}" value="{{$category->id}}">{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="validation-msg"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('file.systems')}}</label>
                                        <div class="input-group">
                                          <select name="product_system_id" id="product_system_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="اختر النظام">
                                            @foreach($productSystems as $productSystem)
                                                <option  data-code="{{$productSystem->code}}" value="{{$productSystem->id}}">{{$productSystem->title}}</option>
                                            @endforeach
                                          </select>
                                      </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('file.Supplier')}} </label>
                                        <div class="input-group">
                                          <select name="supplier_id" id="supplier_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="اختر المورد">
                                            @foreach($suppliers as $supplier)
                                                <option  data-code="{{$supplier->code}}" value="{{$supplier->id}}">{{$supplier->name}}</option>
                                            @endforeach
                                          </select>
                                      </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('file.Brand')}} </label>
                                        <div class="input-group">
                                          <select name="brand_id"  id="brand_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="اختر العلامة التجارية">
                                            @foreach($lims_brand_list as $brand)
                                                <option  data-code="{{$brand->code}}" value="{{$brand->id}}">{{$brand->title}}</option>
                                            @endforeach
                                          </select>
                                      </div>
                                    </div>
                                </div>

                                <div id="unit" class="col-md-12">
                                    <div class="row ">
                                        <div class="col-md-4 form-group">
                                                <label>{{trans('file.Product Unit')}} *</strong> </label>
                                                <div class="input-group">
                                                  <select required class="form-control selectpicker" name="unit_id">
                                                    <option value="" disabled selected>اختر وحدة المنتج ...</option>
                                                    @foreach($lims_unit_list as $unit)
                                                        @if($unit->base_unit==null)
                                                            <option value="{{$unit->id}}">{{$unit->unit_name}}</option>
                                                        @endif
                                                    @endforeach
                                                  </select>
                                              </div>
                                              <span class="validation-msg"></span>
                                        </div>
                                        <div class="col-md-4">
                                                <label>{{trans('file.Sale Unit')}}</strong> </label>
                                                <div class="input-group">
                                                  <select class="form-control selectpicker" name="sale_unit_id"> 
                                                  </select>
                                              </div>
                                        </div>
                                        <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{trans('file.Purchase Unit')}}</strong> </label>
                                                    <div class="input-group">
                                                      <select class="form-control selectpicker" name="purchase_unit_id"> 
                                                      </select>
                                                  </div>
                                                </div>
                                        </div>                                
                                    </div>                                
                                </div>
                                <div id="cost" class="col-md-4">
                                     <div class="form-group">
                                        <label>{{trans('file.Product Cost')}} *</strong> </label>
                                        <input type="number" name="cost" required class="form-control" step="any">
                                        <span class="validation-msg"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('file.Product Price')}} *</strong> </label>
                                        <input type="number" name="price" required class="form-control" step="any">
                                        <span class="validation-msg"></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="qty" value="0.00">
                                    </div>
                                </div>
                                <div id="alert-qty" class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('file.Alert Quantity')}}</strong> </label>
                                        <input type="number" name="alert_quantity" class="form-control" step="any">
                                    </div>
                                </div>
                                <div class="col-md-4" hidden>
                                    <div class="form-group">
                                        <label>{{trans('file.Product Tax')}}</strong> </label>
                                        <select name="tax_id" class="form-control selectpicker">
                                            <option value="">No Tax</option>
                                            @foreach($lims_tax_list as $tax)
                                                <option value="{{$tax->id}}">{{$tax->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4" hidden>
                                    <div class="form-group">
                                        <label>{{trans('file.Tax Method')}}</strong> </label> <i class="dripicons-question" data-toggle="tooltip" title="{{trans('file.Exclusive: Poduct price = Actual product price + Tax. Inclusive: Actual product price = Product price - Tax')}}"></i>
                                        <select name="tax_method" class="form-control selectpicker">
                                            <option value="1">{{trans('file.Exclusive')}}</option>
                                            <option value="2">{{trans('file.Inclusive')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4" hidden>
                                    <div class="form-group mt-3">
                                        <input type="checkbox" name="featured" value="1">&nbsp;
                                        <label>{{trans('file.Featured')}}</label>
                                        <p class="italic">{{trans('file.Featured product will be displayed in POS')}}</p>
                                    </div> 
                                </div>                             
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{trans('file.Product Image')}}</strong> </label> <i class="dripicons-question" data-toggle="tooltip" title="{{trans('file.You can upload multiple image. Only .jpeg, .jpg, .png, .gif file can be uploaded. First image will be base image.')}}"></i>
                                        <div id="imageUpload" class="dropzone"></div>
                                        <span class="validation-msg" id="image-error"></span>
                                    </div>
                                </div>                            
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{trans('file.Product Details')}}</label>
                                        <textarea name="product_details" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12" id="variant-option" hidden>
                                    <h5><input name="is_variant" type="checkbox" id="is-variant" value="1">&nbsp; {{trans('file.This product has variant')}}</h5>
                                </div>
                                <div class="col-md-12" id="variant-section" hidden>
                                    <div class="col-md-6 form-group mt-2">
                                        <input type="text" name="variant" class="form-control" placeholder="{{trans('file.Enter variant seperated by comma')}}">
                                    </div>
                                    <div class="table-responsive ml-2">
                                        <table id="variant-table" class="table table-hover variant-list">
                                            <thead>
                                                <tr>
                                                    <th><i class="dripicons-view-apps"></i></th>
                                                    <th>{{trans('file.name')}}</th>
                                                    <th>{{trans('file.Item Code')}}</th>
                                                    <th>{{trans('file.Additional Price')}}</th>
                                                    <th><i class="dripicons-trash"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-3" hidden>
                                    <input name="promotion" type="checkbox" id="promotion" value="1">&nbsp;
                                    <label><h5> {{trans('file.Add Promotional Price')}}</h5></label>
                                </div>
                                <div class="col-md-12" hidden>
                                    <div class="row">
                                        <div class="col-md-4" id="promotion_price">
                                            <label>{{trans('file.Promotional Price')}}</label>
                                            <input type="number" name="promotion_price" class="form-control" step="any" />
                                        </div>
                                        <div class="col-md-4" id="start_date">
                                            <div class="form-group">
                                                <label>{{trans('file.Promotion Starts')}}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="dripicons-calendar"></i></div>
                                                    </div>
                                                    <input type="text" name="starting_date" id="starting_date" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4" id="last_date">
                                            <div class="form-group">
                                                <label>{{trans('file.Promotion Ends')}}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="dripicons-calendar"></i></div>
                                                    </div>
                                                    <input type="text" name="last_date" id="ending_date" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                                </div> 
                            </div>
                            <div class="form-group">
                                <input type="button" value="{{trans('file.submit')}}" id="submit-btn" class="btn btn-lg btn-primary pull-left">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('file.Products')}}</h4>
                    </div>
                    <div class="card-body">
<ul id="product-list">

</ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    var barcode_category='';
    var barcode_brand='';
    var barcode_supplier='';
    var barcode_product_system='';

    function makeBarcode(){
        var result = barcode_category+barcode_product_system +barcode_supplier +barcode_brand
       $('input[name=code]').val(result)

        if(barcode_category!='' && barcode_brand!='' && barcode_supplier!='' && barcode_product_system!='' ){
            $.ajax({
                type: 'GET',
                url: '{{route('products.custom.code')}}',
                data: {
                    category_id: $('#category_id ').val(),
                    brand_id: $('#brand_id ').val(),
                    supplier_id: $('#supplier_id ').val(),
                    product_system_id: $('#product_system_id ').val()
                },
                success: function(data) {
                    result+='-'+data;
                    $('input[name=code]').val(result)

                }
            });

        }
        searchAttriputes()
    }
    function searchAttriputes(){
   $('#product-list').html();
        $.ajax({
                type: 'GET',
                url: '{{route('products.custom.search')}}',
                data: {
                    category_id: $('#category_id ').val(),
                    brand_id: $('#brand_id ').val(),
                    supplier_id: $('#supplier_id ').val(),
                    product_system_id: $('#product_system_id ').val()
                },
                success: function(data) {
var res=''
                    $.each(data, function(key, value) {
                        res +='<li>'+value.name + ' - '+value.code +'</li>'
                     });

                    $('#product-list').html(res);

                }
            });

    }

    $('document').ready(function (){

        $('#category_id ').change(function (){
            barcode_category=$('#category_id').find('option:selected').data('code') +'-' ;
            makeBarcode()
        })
        $('#brand_id ').change(function (){
            barcode_brand =$('#brand_id').find('option:selected').data('code') +'-';
            makeBarcode()
        })
        $('#supplier_id ').change(function (){
            barcode_supplier=$('#supplier_id').find('option:selected').data('code')  +'-' ;
            makeBarcode()
        })
        $('#product_system_id').change(function (){
            barcode_product_system=$('#product_system_id').find('option:selected').data('code') +'-';
            makeBarcode()
        })
    });


    $("ul#product").siblings('a').attr('aria-expanded','true');
    $("ul#product").addClass("show");
    $("ul#product #product-create-menu").addClass("active");

    $("#digital").hide();
    $("#combo").hide();
    $("#variant-section").hide();
    $("#promotion_price").hide();
    $("#start_date").hide();
    $("#last_date").hide();

    $('[data-toggle="tooltip"]').tooltip(); 

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#genbutton').on("click", function(){
      $.get('gencode', function(data){
        $("input[name='code']").val(data);
      });
    });

    

    tinymce.init({
      selector: 'textarea',
      height: 130,
      plugins: [
        'advlist autolink lists link image charmap print preview anchor textcolor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste code wordcount'
      ],
      toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
      branding:false
    });

    $('select[name="type"]').on('change', function() {
        if($(this).val() == 'combo'){
            $("input[name='cost']").prop('required',false);
            $("select[name='unit_id']").prop('required',false);
            hide();
            $("#combo").show(300);
            $("input[name='price']").prop('disabled',true);
            $("#is-variant").prop("checked", false);
            $("#variant-section, #variant-option").hide(300);
        }
        else if($(this).val() == 'digital'){
            $("input[name='cost']").prop('required',false);
            $("select[name='unit_id']").prop('required',false);
            $("input[name='file']").prop('required',true);
            hide();
            $("#digital").show(300);
            $("#combo").hide(300);
            $("input[name='price']").prop('disabled',false);
            $("#is-variant").prop("checked", false);
            $("#variant-section, #variant-option").hide(300);
        }
        else if($(this).val() == 'standard'){
            $("input[name='cost']").prop('required',true);
            $("select[name='unit_id']").prop('required',true);
            $("input[name='file']").prop('required',false);
            $("#cost").show(300);
            $("#unit").show(300);
            $("#alert-qty").show(300);
            $("#variant-option").show(300);
            $("#digital").hide(300);
            $("#combo").hide(300);
            $("input[name='price']").prop('disabled',false);
        }
    });

    $('select[name="unit_id"]').on('change', function() {
        
        unitID = $(this).val();
        if(unitID) {
            populate_category(unitID);
        }else{    
            $('select[name="sale_unit_id"]').empty();
            $('select[name="purchase_unit_id"]').empty();
        }                        
    });
    <?php $productArray = []; ?>
    var lims_product_code = [ @foreach($lims_product_list as $product)
        <?php
            $productArray[] = $product->code . ' [ ' . $product->name . ' ]';
        ?>
         @endforeach
            <?php
                echo  '"'.implode('","', $productArray).'"';
            ?> ];

    var lims_productcodeSearch = $('#lims_productcodeSearch');

    lims_productcodeSearch.autocomplete({
        source: function(request, response) {
            var matcher = new RegExp(".?" + $.ui.autocomplete.escapeRegex(request.term), "i");
            response($.grep(lims_product_code, function(item) {
                return matcher.test(item);
            }));
        },
        select: function(event, ui) {
            var data = ui.item.value;
            $.ajax({
                type: 'GET',
                url: 'search',
                data: {
                    data: data
                },
                success: function(data) {
                    var flag = 1;
                    $(".product-id").each(function() {
                        if ($(this).val() == data[4]) {
                            alert('Duplicate input is not allowed!')
                            flag = 0;
                        }
                    });
                    $("input[name='product_code_name']").val('');
                    if(flag){
                        var newRow = $("<tr>");
                        var cols = '';
                        cols += '<td>' + data[0] +' [' + data[1] + ']</td>';
                        cols += '<td><input type="number" class="form-control qty" name="product_qty[]" value="1" step="any"/></td>';
                        cols += '<td><input type="number" class="form-control unit_price" name="unit_price[]" value="' + data[3] + '" step="any"/></td>';
                        cols += '<td><button type="button" class="ibtnDel btn btn-sm btn-danger">X</button></td>';
                        cols += '<input type="hidden" class="product-id" name="product_id[]" value="' + data[4] + '"/>';

                        newRow.append(cols);
                        $("table.order-list tbody").append(newRow);
                        calculate_price();
                    }
                }
            });
        }
    });

    //Change quantity or unit price
    $("#myTable").on('input', '.qty , .unit_price', function() {
        calculate_price();
    });

    //Delete product
    $("table.order-list tbody").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();
        calculate_price();
    });

    function hide() {
        $("#cost").hide(300);
        $("#unit").hide(300);
        $("#alert-qty").hide(300);
    }

    function calculate_price() {
        var price = 0;
        $(".qty").each(function() {
            rowindex = $(this).closest('tr').index();
            quantity =  $(this).val();
            unit_price = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .unit_price').val();
            price += quantity * unit_price;
        });
        $('input[name="price"]').val(price);
    }

    function populate_category(unitID){
        $.ajax({
            url: 'saleunit/'+unitID,
            type: "GET",
            dataType: "json",
            success:function(data) {
                  $('select[name="sale_unit_id"]').empty();
                  $('select[name="purchase_unit_id"]').empty();
                  $.each(data, function(key, value) {
                      $('select[name="sale_unit_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                      $('select[name="purchase_unit_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                  });
                  $('.selectpicker').selectpicker('refresh');
            },
        });
    }

    $("input[name='is_variant']").on("change", function () {
        if ($(this).is(':checked')) {
            $("#variant-section").show(300);
        }
        else
            $("#variant-section").hide(300);
    });

    $("input[name='variant']").on("input", function () {
        if($("#code").val() == ''){
            $("input[name='variant']").val('');
            alert('Please fillup above information first.');
        }
        else if($(this).val().indexOf(',') > -1) {
            var variant_name = $(this).val().slice(0, -1);
            var item_code = variant_name+'-'+$("#code").val();
            var newRow = $("<tr>");
            var cols = '';
            cols += '<td style="cursor:grab"><i class="dripicons-view-apps"></i></td>';
            cols += '<td><input type="text" class="form-control" name="variant_name[]" value="' + variant_name + '" /></td>';
            cols += '<td><input type="text" class="form-control" name="item_code[]" value="'+item_code+'" /></td>';
            cols += '<td><input type="number" class="form-control" name="additional_price[]" value="" step="any" /></td>';
            cols += '<td><button type="button" class="vbtnDel btn btn-sm btn-danger">X</button></td>';

            $("input[name='variant']").val('');
            newRow.append(cols);
            $("table.variant-list tbody").append(newRow);
        }
    });

    //Delete variant
    $("table#variant-table tbody").on("click", ".vbtnDel", function(event) {
        $(this).closest("tr").remove();
    });

    $( "#promotion" ).on( "change", function() {
        if ($(this).is(':checked')) {
            $("#starting_date").val($.datepicker.formatDate('dd-mm-yy', new Date()));
            $("#promotion_price").show(300);
            $("#start_date").show(300);
            $("#last_date").show(300);
        } 
        else {
            $("#promotion_price").hide(300);
            $("#start_date").hide(300);
            $("#last_date").hide(300);
        }
    });

    var starting_date = $('#starting_date');
    starting_date.datepicker({
     format: "dd-mm-yyyy",
     startDate: "<?php echo date('d-m-Y'); ?>",
     autoclose: true,
     todayHighlight: true
     });

    var ending_date = $('#ending_date');
    ending_date.datepicker({
     format: "dd-mm-yyyy",
     startDate: "<?php echo date('d-m-Y'); ?>",
     autoclose: true,
     todayHighlight: true
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
    //dropzone portion
    Dropzone.autoDiscover = false;

    jQuery.validator.setDefaults({
        errorPlacement: function (error, element) {
            if(error.html() == 'Select Category...')
                error.html('This field is required.');
            $(element).closest('div.form-group').find('.validation-msg').html(error.html());
        },
        highlight: function (element) {
            $(element).closest('div.form-group').removeClass('has-success').addClass('has-error');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).closest('div.form-group').removeClass('has-error').addClass('has-success');
            $(element).closest('div.form-group').find('.validation-msg').html('');
        }
    });

    function validate() {
        var product_code = $("input[name='code']").val();
        var barcode_symbology = $('select[name="barcode_symbology"]').val();
        var exp = /^\d+$/;

        if(!(product_code.match(exp)) && (barcode_symbology == 'UPCA' || barcode_symbology == 'UPCE' || barcode_symbology == 'EAN8' || barcode_symbology == 'EAN13') ) {
            alert('Product code must be numeric.');
            return false;
        }
        else if(product_code.match(exp)) {
            if(barcode_symbology == 'UPCA' && product_code.length > 11){
                alert('Product code length must be less than 12');
                return false;
            }
            else if(barcode_symbology == 'EAN8' && product_code.length > 7){
                alert('Product code length must be less than 8');
                return false;
            }
            else if(barcode_symbology == 'EAN13' && product_code.length > 12){
                alert('Product code length must be less than 13');
                return false;
            }
        }

        if( $("#type").val() == 'combo' ) {
            var rownumber = $('table.order-list tbody tr:last').index();
            if (rownumber < 0) {
                alert("Please insert product to table!")
                return false;
            }
        }
        if($("#is-variant").is(":checked")) {
            rowindex = $("table#variant-table tbody tr:last").index();
            if (rowindex < 0) {
                alert('This product has variant. Please insert variant to table');
                return false;
            }
        }
        $("input[name='price']").prop('disabled',false);
        return true;
    }

    $("table#variant-table tbody").sortable({
        items: 'tr',
        cursor: 'grab',
        opacity: 0.5,
    });

    $(".dropzone").sortable({
        items:'.dz-preview',
        cursor: 'grab',
        opacity: 0.5,
        containment: '.dropzone',
        distance: 20,
        tolerance: 'pointer',
        stop: function () {
          var queue = myDropzone.getAcceptedFiles();
          newQueue = [];
          $('#imageUpload .dz-preview .dz-filename [data-dz-name]').each(function (count, el) {           
                var name = el.innerHTML;
                queue.forEach(function(file) {
                    if (file.name === name) {
                        newQueue.push(file);
                    }
                });
          });
          myDropzone.files = newQueue;
        }
    });

    myDropzone = new Dropzone('div#imageUpload', {
        addRemoveLinks: true,
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 100,
        maxFilesize: 12,
        paramName: 'image',
        clickable: true,
        method: 'POST',
        url: '{{route('products.store')}}',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        renameFile: function(file) {
            var dt = new Date();
            var time = dt.getTime();
            return time + file.name;
        },
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        init: function () {
            var myDropzone = this;
            $('#submit-btn').on("click", function (e) {
                e.preventDefault();
                if ( $("#product-form").valid() && validate() ) {
                    tinyMCE.triggerSave();
                    if(myDropzone.getAcceptedFiles().length) {
                        myDropzone.processQueue();
                    }
                    else {
                        $.ajax({
                            type:'POST',
                            url:'{{route('products.store')}}',
                            data: $("#product-form").serialize(),
                            success:function(response){
                                //console.log(response);
                                location.href = '../products';
                            },
                            error:function(response) {
                              if(response.responseJSON.errors.name) {
                                  $("#name-error").text(response.responseJSON.errors.name);
                              }
                              else if(response.responseJSON.errors.code) {
                                  $("#code-error").text(response.responseJSON.errors.code);
                              }
                            },
                        });
                    }
                }
            });

            this.on('sending', function (file, xhr, formData) {
                // Append all form inputs to the formData Dropzone will POST
                var data = $("#product-form").serializeArray();
                $.each(data, function (key, el) {
                    formData.append(el.name, el.value);
                });
            });
        },
        error: function (file, response) {
            console.log(response);
            if(response.errors.name) {
              $("#name-error").text(response.errors.name);
              this.removeAllFiles(true);
            }
            else if(response.errors.code) {
              $("#code-error").text(response.errors.code);
              this.removeAllFiles(true);
            }
            else {
              try {
                  var res = JSON.parse(response);
                  if (typeof res.message !== 'undefined' && !$modal.hasClass('in')) {
                      $("#success-icon").attr("class", "fas fa-thumbs-down");
                      $("#success-text").html(res.message);
                      $modal.modal("show");
                  } else {
                      if ($.type(response) === "string")
                          var message = response; //dropzone sends it's own error messages in string
                      else
                          var message = response.message;
                      file.previewElement.classList.add("dz-error");
                      _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
                      _results = [];
                      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                          node = _ref[_i];
                          _results.push(node.textContent = message);
                      }
                      return _results;
                  }
              } catch (error) {
                  console.log(error);
              }
            }
        },
        successmultiple: function (file, response) {
            location.href = '../products';
            //console.log(file, response);
        },
        completemultiple: function (file, response) {
            console.log(file, response, "completemultiple");
        },
        reset: function () {
            console.log("resetFiles");
            this.removeAllFiles(true);
        }
    });

</script>
@endsection
