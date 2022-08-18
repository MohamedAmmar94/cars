@extends('layout.main')

@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('file.Add Service Product')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        <form id="product-form">
                            <div class="row">
                                <div class="col-md-4" hidden >
                                    <div class="form-group">
                                        <label>{{trans('file.Product Type')}} * </label>
                                        <div class="input-group">
                                            <select name="type" required class="form-control selectpicker" id="type">
                                                <option  value="standard">Standard</option>
                                                <option value="combo">Combo</option>
                                                <option selected value="digital">Digital</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('file.service_name')}} * </label>
                                        <input type="text" name="name" class="form-control" id="name" aria-describedby="name" required>
                                        <span class="validation-msg" id="name-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('file.tst')}} * </label>
                                        <input type="time" name="tst" class="form-control" id="tst" aria-describedby="tst" required>
                                        <span class="validation-msg" id="name-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('file.service_code')}} * </label>
                                        <div class="input-group">
                                            <input type="text" name="code" class="form-control" value="{{$new_serial ?? ""}}" id="code" aria-describedby="code" required>
                                            
                                        </div>
                                        <span class="validation-msg" id="code-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-4" hidden>
                                    <div class="form-group">
                                        <label>{{trans('file.Barcode Symbology')}} * </label>
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
<input type="hidden" name="category_id" value="0">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('file.service_price')}} * </label>
                                        <input type="number" name="price" required class="form-control" step="any">
                                        <span class="validation-msg"></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="qty" value="0.00">
                                    </div>
                                </div>



                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{trans('file.service_details')}}</label>
                                        <textarea name="product_details" class="form-control" rows="3"></textarea>
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
        </div>
    </div>
</section>

<script type="text/javascript">

    $("ul#services").siblings('a').attr('aria-expanded','true');
    $("ul#services").addClass("show");
    $("ul#services #services-create-menu").addClass("active");

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

        $("input[name='cost']").prop('required',false);
        $("select[name='unit_id']").prop('required',false);
        $("input[name='file']").prop('required',false);

        $("#digital").show(300);
        $("#combo").hide(300);
        $("input[name='price']").prop('disabled',false);
        $("#is-variant").prop("checked", false);
        $("#variant-section, #variant-option").hide(300);



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

    jQuery.validator.setDefaults({
        errorPlacement: function (error, element) {
            if(error.html() == 'Select Category...')
                error.html('هذا الحقل مطلوب .');
            $(element).closest('div.form-group').find('.validation-msg').html(error.html('هذا الحقل مطلوب .'));
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



        $("input[name='price']").prop('disabled',false);
        return true;
    }

    $("table#variant-table tbody").sortable({
        items: 'tr',
        cursor: 'grab',
        opacity: 0.5,
    });
    $('#submit-btn').on("click", function (e) {
        e.preventDefault();
        if ( $("#product-form").valid() && validate() ) {
            tinyMCE.triggerSave();
                $.ajax({
                    type:'POST',
                    url:'{{route('services.store')}}',
                    data: $("#product-form").serialize(),
                    success:function(response){
                        //console.log(response);
                        location.href = '../services';
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
    });


</script>
@endsection
