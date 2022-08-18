@extends('layout.main') @section('content')
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}</div>
    @endif
    @if(session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif
    <div class="row">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="brand-text float-left mt-4">
                    <h3>{{trans('file.invoices')}} </h3>
                </div>
            </div>
        </div>
    </div>
    <section>

        <div class="table-responsive">
            <table id="quotation-table" class="table quotation-list">
                <thead>
                <tr>
                    <th class="not-exported"></th>
                    <th>{{trans('file.Date')}}</th>
                    <th>{{trans('file.reference')}}</th>
                    <th>{{trans('file.customer')}}</th>
                    <th>{{trans('file.customercar')}}</th>
                    <th>{{trans('file.dispatch_status')}}</th>
                    <th>{{trans('file.workorder_status')}}</th>
                    <th>{{trans('file.due_date')}}</th>
                    <th>{{trans('file.deliver_invoice')}}</th>
                    <th>{{trans('file.invoice_deliver_date')}}</th>
                    <th class="not-exported">{{trans('file.action')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lims_quotation_all as $key=>$quotation)
                    <?php
                    if($quotation->sale_status == 0)
                        $status = trans('file.null_order');
                    if($quotation->sale_status == 1)
                        $status = trans('file.Completed');
                    if($quotation->sale_status == 2)
                        $status = trans('file.waittingstock');
                    $workorder_status="null";
                    if($quotation->workorder_status == 1)
                        $workorder_status = trans('file.opened_order');
                    if($quotation->workorder_status == 2)
                        $workorder_status = trans('file.waittingstock');
                    if($quotation->workorder_status == 3)
                        $workorder_status = trans('file.closed');
                    if($quotation->workorder_status == 4)
                        $workorder_status = trans('file.cancel');
                    if($quotation->workorder_status == 5)
                        $workorder_status = trans('file.Completed');

                   ;

                    $due_status='invoice not delivered';
                    $due_status_class='badge badge-info';
                  if($quotation->invoice_deliver_date)
                   {
                       $due_date= new \Carbon\Carbon($quotation->invoice_deliver_date.' 00:00:00');
                       $due_date=$due_date->addDays( $quotation->customer->due_period);
                       $now=\Carbon\Carbon::now();

                       if($due_date < $now){
                           $due_status_class='badge badge-warning';
                           $due_status =  'out-standing';

                       }
                    else if($due_date > $now)
{
    $due_status_class='badge badge-primary';
    $due_status =  'early';

}
                    else
{
    $due_status_class='badge badge-success';
    $due_status ='due';

}
                    }
//dd($due_date,$now,$due_status);
                    ?>
                    <tr class="quotation-link" data-quotation='["{{date($general_setting->date_format, strtotime($quotation->created_at->toDateString()))}}", "{{$quotation->reference_no}}", "{{$status}}", "{{$quotation->biller->name ?? ""}}", "{{$quotation->biller->company_name ?? ""}}","{{$quotation->biller->email ??""}}", "{{$quotation->biller->phone_number ??""}}", "{{$quotation->biller->address ??""}}", "{{$quotation->biller->city ??""}}", "{{$quotation->customer->name}}", "{{$quotation->customer->phone_number}}", "{{$quotation->customer->address}}", "{{$quotation->customer->city}}", "{{$quotation->id}}", "{{$quotation->total_tax}}", "{{$quotation->total_discount}}", "{{$quotation->total_price}}", "{{$quotation->order_tax}}", "{{$quotation->order_tax_rate}}", "{{$quotation->order_discount}}", "{{$quotation->shipping_cost}}", "{{$quotation->grand_total}}", "{{$quotation->note}}", "{{$quotation->user->name}}", "{{$quotation->user->email}}", "{{$quotation->completed_at}}", "{{$quotation->type}}"]'>
                        <td>{{$key}}</td>
                        <td>{{ date($general_setting->date_format, strtotime($quotation->created_at->toDateString())) . ' '. $quotation->created_at->toTimeString() }}</td>
                        <td>{{ $quotation->reference_no }}</td>
                        <td>{{ $quotation->customer->name  }}</td>
                        <td>{{ $quotation->customercar->chassis }}</td>

                        @if($quotation->sale_status == 0)
                            <td><div class="badge badge-primary">{{$status}}</div></td>
                        @endif
                        @if($quotation->sale_status == 1)
                            <td><div class="badge badge-success">{{$status}}</div></td>
                        @endif
                        @if($quotation->sale_status == 2)
                            <td><div class="badge badge-warning">{{$status}}</div></td>
                        @endif

                        @if($quotation->workorder_status == 1)
                            <td><div class="badge badge-primary">{{ $workorder_status }}</div></td>
                        @endif
                        @if($quotation->workorder_status == 2)
                            <td><div class="badge badge-warning">{{ $workorder_status }}</div></td>
                        @endif
                        @if($quotation->workorder_status == 3)
                            <td><div class="badge badge-success">{{ $workorder_status }}</div></td>
                        @endif
                        @if($quotation->workorder_status == 4)
                            <td><div class="badge badge-danger">{{ $workorder_status }}</div></td>
                        @endif
                        @if($quotation->workorder_status == 5)
                            <td><div class="badge badge-success">{{ $workorder_status }}</div></td>
                        @endif

                        <td><div class="{{$due_status_class}}">{{ $due_status }}</div></td>
                        <td>{{ $quotation->is_invoice_deliver? 'yes':'no' }}</td>
                        <td>{{ $quotation->invoice_deliver_date }}</td>


                        <td>

                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans('file.action')}}
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                    <li>
                                        <button type="button"
                                                data-toggle="modal" data-target=".deliver_invoice_modal"
                                                data-id="{{$quotation->id}}"
                                                data-is_invoice_deliver="{{$quotation->is_invoice_deliver}}"
                                                data-invoice_deliver_date="{{$quotation->invoice_deliver_date}}"
                                                class="btn btn-link deliver_invoice"><i class="fa fa-eye"></i>  {{trans('file.deliver_invoice')}}</button>
                                    </li>
                                    <li>
                                        <button type="button" class="btn btn-link view"><i class="fa fa-eye"></i>  {{trans('file.View')}}</button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </section>

    <div id="quotation-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="container mt-3 pb-2 border-bottom">
                    <div class="row">
                        <div class="col-md-3">
                            <button id="print-btn" type="button" class="btn btn-default btn-sm d-print-none"><i class="dripicons-print"></i> {{trans('file.Print')}}</button>

                        </div>
                        <div class="col-md-6">
                            <h3 id="exampleModalLabel" class="modal-title text-center container-fluid">{{$general_setting->site_title}}</h3>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close" class="close d-print-none"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                        </div>
                        <div class="col-md-12 text-center">
                            <i style="font-size: 15px;">{{trans('file.workorder_details')}}</i>
                        </div>
                    </div>
                </div>
                <div id="quotation-content" class="modal-body">
                </div>
                <br>
                <table class="table table-bordered product-quotation-list">
                    <thead>
                    <th>#</th>
                    <th>{{trans('file.product')}}</th>
                    <th>Qty</th>
                    <th>{{trans('file.Unit Price')}}</th>
                    <th>{{trans('file.Tax')}}</th>
                    <th>{{trans('file.Discount')}}</th>
                    <th>{{trans('file.Subtotal')}}</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div id="quotation-footer" class="modal-body"></div>
            </div>
        </div>
    </div>
    <div class="modal fade deliver_invoice_modal" id="deliver_invoice_modal" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{route('workorder.deliver-invoice')}}" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">{{trans('file.deliver_invoice')}} <span class="model_type"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-1"></div>

                            <div class="col-10">

                                <div class="form-group">
                                    <label>{{trans('file.id')}}</label>
                                    <input type="text" readonly class="form-control" name="id">
                                </div>
                                <div class="row" >
                                    <label class="form-check-label col-md-3" >{{trans('file.is_invoice_deliver')}}</label>
                                    <input type="checkbox" class="form-control  col-md-4" name="is_invoice_deliver" value="1">
                                </div>
                                <br>

                                <div class=" form-group">
                                    <label>{{trans('file.invoice_deliver_date')}}</label>
                                    <input type="date" class="form-control" name="invoice_deliver_date">
                                </div>


                            </div>

                            <div class="col-1"></div>


                        </div>


                    </div>
                    <div class="modal-footer">
                        <div class="col-12 pull-left">
                            <button type="submit" class="btn btn-brand btn-elevate btn-icon-sm">{{trans('file.submit')}}</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script type="text/javascript">

        $("ul#sale").siblings('a').attr('aria-expanded','true');
        $("ul#sale").addClass("show");
        $("ul#sale #invoices-list-menu").addClass("active");
        var all_permission = <?php echo json_encode($all_permission) ?>;
        var quotation_id = [];
        var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function confirmDelete() {
            if (confirm("Are you sure want to delete?")) {
                return true;
            }
            return false;
        }

        $("tr.quotation-link td:not(:first-child, :last-child)").on("click", function(){
            var quotation = $(this).parent().data('quotation');
            quotationDetails(quotation);
        });

        $(".view").on("click", function(){
            var quotation = $(this).parent().parent().parent().parent().parent().data('quotation');
            console.log(quotation)
            quotationDetails(quotation);
        });
        $('#deliver_invoice_modal').on('show.bs.modal', function (e) {


            var Id = $(e.relatedTarget).data('id');
            var is_invoice_deliver = $(e.relatedTarget).data('is_invoice_deliver');
            var invoice_deliver_date = $(e.relatedTarget).data('invoice_deliver_date');
            $(e.currentTarget).find('input[name="id"]').val(Id);
            if(is_invoice_deliver)
                $(e.currentTarget).find('input[name="is_invoice_deliver"]').attr('checked','true');
            $(e.currentTarget).find('input[name="invoice_deliver_date"]').val(invoice_deliver_date);
        });

        $("#print-btn").on("click", function(){
            var divToPrint=document.getElementById('quotation-details');
            var newWin=window.open('','Print-Window');
            newWin.document.open();
            newWin.document.write('<link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
            newWin.document.close();
            setTimeout(function(){newWin.close();},10);
        });

        $('#quotation-table').DataTable( {
            "order": [],
            'language': {
                'lengthMenu': '_MENU_ {{trans("file.records per page")}}',
                "info":      '<small>{{trans("file.Showing")}} _START_ - _END_ (_TOTAL_)</small>',
                "search":  '{{trans("file.Search")}}',
                'paginate': {
                    'previous': '<i class="dripicons-chevron-left"></i>',
                    'next': '<i class="dripicons-chevron-right"></i>'
                }
            },
            'columnDefs': [
                {
                    "orderable": false
                },
                {
                    'render': function(data, type, row, meta){
                        if(type === 'display'){
                            data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
                        }

                        return data;
                    },
                    'checkboxes': {
                        'selectRow': true,
                        'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'
                    },
                    'targets': [0]
                }
            ],
            'select': { style: 'multi',  selector: 'td:first-child'},
            'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
            dom: '<"row"lfB>rtip',
            buttons: [
                    {{--{--}}
                    {{--    extend: 'pdf',--}}
                    {{--    text: '{{trans("file.PDF")}}',--}}
                    {{--    exportOptions: {--}}
                    {{--        columns: ':visible:Not(.not-exported)',--}}
                    {{--        rows: ':visible'--}}
                    {{--    },--}}
                    {{--    action: function(e, dt, button, config) {--}}
                    {{--        datatable_sum(dt, true);--}}
                    {{--        $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);--}}
                    {{--        datatable_sum(dt, false);--}}
                    {{--    },--}}
                    {{--    footer:true--}}
                    {{--},--}}
                {
                    extend: 'csv',
                    text: '{{trans("file.CSV")}}',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported)',
                        rows: ':visible'
                    },
                    action: function(e, dt, button, config) {
                        datatable_sum(dt, true);
                        $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                        datatable_sum(dt, false);
                    },
                    footer:true
                },
                {
                    extend: 'print',
                    text: '{{trans("file.Print")}}',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported)',
                        rows: ':visible'
                    },
                    action: function(e, dt, button, config) {
                        datatable_sum(dt, true);
                        $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
                        datatable_sum(dt, false);
                    },
                    footer:true
                },
                {
                    text: '{{trans("file.delete")}}',
                    className: 'buttons-delete',
                    action: function ( e, dt, node, config ) {
                        if(user_verified == '1') {
                            quotation_id.length = 0;
                            $(':checkbox:checked').each(function(i){
                                if(i){
                                    var quotation = $(this).closest('tr').data('quotation');
                                    quotation_id[i-1] = quotation[13];
                                }
                            });
                            if(quotation_id.length && confirm("Are you sure want to delete?")) {
                                $.ajax({
                                    type:'POST',
                                    url:'workorder/deletebyselection',
                                    data:{
                                        quotationIdArray: quotation_id
                                    },
                                    success:function(data){
                                        alert(data);
                                    }
                                });
                                dt.rows({ page: 'current', selected: true }).remove().draw(false);
                            }
                            else if(!quotation_id.length)
                                alert('Nothing is selected!');
                        }
                        else
                            alert('This feature is disable for demo!');
                    }
                },
                {
                    extend: 'colvis',
                    text: '{{trans("file.Column visibility")}}',
                    columns: ':gt(0)'
                },
            ],
            drawCallback: function () {
                var api = this.api();
                datatable_sum(api, false);
            }
        } );

        function datatable_sum(dt_selector, is_calling_first) {
            if (dt_selector.rows( '.selected' ).any() && is_calling_first) {
                var rows = dt_selector.rows( '.selected' ).indexes();

                $( dt_selector.column( 7 ).footer() ).html(dt_selector.cells( rows, 7, { page: 'current' } ).data().sum().toFixed(2));
            }
            else {
                $( dt_selector.column( 7 ).footer() ).html(dt_selector.cells( rows, 7, { page: 'current' } ).data().sum().toFixed(2));
            }
        }

        if(all_permission.indexOf("quotes-delete") == -1)
            $('.buttons-delete').addClass('d-none');

        function quotationDetails(quotation){
            $('input[name="quotation_id"]').val(quotation[13]);
            var htmltext = '<strong>{{trans("file.Date")}}: </strong>'+quotation[0]+'<br><strong>{{trans("file.reference")}}: </strong>'+quotation[1]+'<br><strong>{{trans("file.Status")}}: </strong>'+quotation[2]+'<br><strong>{{trans("file.completed_at")}}: </strong>'+quotation[25]+'<br><strong>Worh Order Type: </strong>'+quotation[26]+'<br><div class="row"><div class="col-md-6"><strong>{{trans("file.From")}}:</strong><br>'+quotation[3]+'<br>'+quotation[4]+'<br>'+quotation[5]+'<br>'+quotation[6]+'<br>'+quotation[7]+'<br>'+quotation[8]+'</div><div class="col-md-6"><div class="float-right"><strong>{{trans("file.To")}}:</strong><br>'+quotation[9]+'<br>'+quotation[10]+'<br>'+quotation[11]+'<br>'+quotation[12]+'</div></div></div>';
            $.get('workorder/product_quotation/' + quotation[13], function(data){
                $(".product-quotation-list tbody").remove();
                var name_code = data[0];
                var qty = data[1];
                var unit_code = data[2];
                var tax = data[3];
                var tax_rate = data[4];
                var discount = data[5];
                var subtotal = data[6];
                var newBody = $("<tbody>");
                $.each(name_code, function(index){
                    var newRow = $("<tr>");
                    var cols = '';
                    cols += '<td><strong>' + (index+1) + '</strong></td>';
                    cols += '<td>' + name_code[index] + '</td>';
                    cols += '<td>' + qty[index] + ' ' + unit_code[index] + '</td>';
                    cols += '<td>' + parseFloat(subtotal[index] / qty[index]).toFixed(2) + '</td>';
                    cols += '<td>' + tax[index] + '(' + tax_rate[index] + '%)' + '</td>';
                    cols += '<td>' + discount[index] + '</td>';
                    cols += '<td>' + subtotal[index] + '</td>';
                    newRow.append(cols);
                    newBody.append(newRow);
                });

                var newRow = $("<tr>");
                cols = '';
                cols += '<td colspan=4><strong>{{trans("file.Total")}}:</strong></td>';
                cols += '<td>' + quotation[14] + '</td>';
                cols += '<td>' + quotation[15] + '</td>';
                cols += '<td>' + quotation[16] + '</td>';
                newRow.append(cols);
                newBody.append(newRow);

                var newRow = $("<tr>");
                cols = '';
                cols += '<td colspan=6><strong>{{trans("file.Order Tax")}}:</strong></td>';
                cols += '<td>' + quotation[17] + '(' + quotation[18] + '%)' + '</td>';
                newRow.append(cols);
                newBody.append(newRow);

                var newRow = $("<tr>");
                cols = '';
                cols += '<td colspan=6><strong>{{trans("file.Order Discount")}}:</strong></td>';
                cols += '<td>' + quotation[19] + '</td>';
                newRow.append(cols);
                newBody.append(newRow);

                {{--var newRow = $("<tr>");--}}
                {{--cols = '';--}}
                {{--cols += '<td colspan=6><strong>{{trans("file.Shipping Cost")}}:</strong></td>';--}}
                {{--cols += '<td>' + quotation[20] + '</td>';--}}
                {{--newRow.append(cols);--}}
                {{--newBody.append(newRow);--}}

                var newRow = $("<tr>");
                cols = '';
                cols += '<td colspan=6><strong>{{trans("file.grand total")}}:</strong></td>';
                cols += '<td>' + quotation[21] + '</td>';
                newRow.append(cols);
                newBody.append(newRow);

                $("table.product-quotation-list").append(newBody);
            });
            var htmlfooter = '<p><strong>{{trans("file.Note")}}:</strong> '+quotation[22]+'</p><strong>{{trans("file.Created By")}}:</strong><br>'+quotation[23]+'<br>'+quotation[24];
            $('#quotation-content').html(htmltext);
            $('#quotation-footer').html(htmlfooter);
            $('#quotation-details').modal('show');
        }
    </script>
@endsection