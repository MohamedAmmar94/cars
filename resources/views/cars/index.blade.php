@extends('layout.main') @section('content')
<style>
.backlog-body{
	padding: 20px 60px;
}
.backlog-body .row{
	margin: 10px 0;
}
.backlog-body .row input[type=checkbox]{
	    margin: 7px 10px;
}
.submit-backlog{
	display:none;
}
</style>
@if(session()->has('create_message'))
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('create_message') !!}</div> 
@endif
@if(session()->has('edit_message'))
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('edit_message') }}</div> 
@endif
@if(session()->has('import_message'))
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('import_message') !!}</div> 
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif

<section>
    <div class="container-fluid">
        @if(in_array("cars-add", $all_permission))
            <a href="{{route('cars.create')}}" class="btn btn-info"><i class="dripicons-plus"></i> {{trans('file.add_car')}}</a>&nbsp;
        @endif
    </div>
    <div class="table-responsive">
        <table id="customer-table" class="table">
            <thead>
                <tr>
                    <th class="not-exported"></th>
                    <th>{{trans('file.customer')}}</th>
                    <th>{{trans('file.chassis')}}</th>
                    <th>{{trans('file.model')}}</th>
                    <th>{{trans('file.mileage')}}</th>
                    <th>{{trans('file.plate')}}</th>
                    <th class="not-exported">{{trans('file.action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cars as $key=>$car)
                <tr data-id="{{$car->id}}">
                    <td>{{$key}}</td>
                    <td>

                        {{  $car->customer->name }}
                    </td>
                    <td>{{ $car->chassis }}</td>
                    <td>{{ $car->model}}</td>
                    <td>{{ $car->mileage}}</td>
                    <td>{{ $car->plate}}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans('file.action')}}
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                @if(in_array("cars-edit", $all_permission))
                                <li> 
                                    <a href="{{ route('cars.edit', ['id' => $car->id]) }}" class="btn btn-link"><i class="dripicons-document-edit"></i> {{trans('file.edit')}}</a>
                                </li>
								<li> 
                                    <a href="#" data-toggle="modal" data-target="#backlog-model" class="btn btn-link"onclick="getbacklog({{$car->id}})"><i class="fa fa-eye"></i> Backlog</a>
                                </li>
                                @endif

                                <li class="divider"></li>
                                @if(in_array("cars-delete", $all_permission))
                                {{ Form::open(['route' => ['cars.destroy', $car->id], 'method' => 'DELETE'] ) }}
                                <li>
                                    <button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="dripicons-trash"></i> {{trans('file.delete')}}</button>
                                </li>
                                {{ Form::close() }}
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
<div class="modal fade" id="backlog-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="   background-color: #fff7f7;">
	
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">select fixed BackLog </h5>
		
			
        
		<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding:0;margin:0;">
				<span aria-hidden="true">&times;</span>
			</button>
		
      </div>
	  <div class="modal-msg"></div>
      <div class="modal-body " >
        <div class="backlog-title" style="display:block;width:100%">
			
		</div>
		<div class="backlog-body"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
        
      </div>
	  
    </div>
  </div>
</div>
<div class="modal backlog-modal fade "  id="backlog-edit-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" >
	
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">BackLog </h5>
		
			
        
		<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding:0;margin:0;">
				<span aria-hidden="true">&times;</span>
			</button>
		
      </div>
	  <form id="backlog-form">
      <div class="modal-body backlog-edit-body" >
        
      </div>
      <div class="modal-footer">
       <button type="submit" class="btn btn-primary submit-backlog"    >Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
        
      </div>
	  </form>
	  
    </div>
  </div>
</div>
<script type="text/javascript">

$(function(){
			setTimeout(function() {
				  $(".alert").fadeOut().empty();
				}, 5000);
    $("#backlog-form").on("submit", function(e){
		 e.preventDefault();
		var carid=$('#backlog-carid').val();
		//console.log(data);
		$('.backlog-modal').modal('hide');
         $.ajax({
          url: '/workorder/setbacklog/form',
          type: 'get',
          data: $('#backlog-form').serialize(),
          
         }).done(function(data){
			 console.log(data);
			 if(data=="operation field"){
				 $('.modal-msg').
						append('<div class="alert alert-danger" role="alert">'+data+'</div>');
			 }else{
				 $('.modal-msg').
						append('<div class="alert alert-success" role="alert">'+data+'</div>');
						
			 }
			 getbacklog(carid);
			 $('.backlog-modal').modal('hide');
			 setTimeout(function() {
				  $(".alert").fadeOut().empty();
				}, 5000);
		 }); 
        
    });
});
function setbacklog(id , carid){
	
	$.ajax({
			url: 'workorder/setbacklog/'+id+'/'+carid,
			type: "GET",
			datatype: 'html',
			
		}).done(
			function(data){
				$('.submit-backlog').show();
				$('.backlog-edit-body').html(data.html);
			});
}
function delbacklog(id){
	$.ajax({
			url: 'workorder/delbacklog/'+id,
			type: "GET",
			datatype: 'html',
			
		}).done(
			function(data){
				
				if(data.msg=="success"){
					$('.modal-msg').
						 append('<div class="alert alert-success" role="alert">'+data.msg+'</div>');
					getbacklog(data.car_id);
				}else{
					 $('.modal-msg').
						append('<div class="alert alert-danger" role="alert">'+data.msg+'</div>');
				}
				
				
			});
}
function getbacklog(id){
	
	
	$.ajax({
			url: 'workorder/getallbacklog/'+id,
			type: "GET",
			datatype: 'html',
			
		}).done( 

			function(data) 

			{
				
				$('.backlog-title').html('<button type="button" class="btn btn-primary" onclick="setbacklog(\'new\', '+data.car_id+')" style="float:right" data-toggle	="modal" data-target="#backlog-edit-model"><i class="fa fa-plus-circle"></i></button>');
				
				$('.backlog-body').html("");
				 if(data.backlogs.length >0){
					$.each(data.backlogs, function(index) {
						
						var component='<div class="badge badge-warning"> pendding </div>';
						if(data.backlogs[index].status==1){
							checked="completed";
							component='<div class="badge badge-success"> completed </div>';
						}
						$('.backlog-body').
						append('<div class="row"><div class="col-md-4"><p>'+data.backlogs[index].title+'</p></div><div class="col-md-2">'+component+'</div><div class="col-md-3"><p>'+data.backlogs[index].created_at+'</p></div><div class="col-md-3"><button type="button" class="btn btn-info" data-toggle="modal" data-target="#backlog-edit-model" onclick="setbacklog('+data.backlogs[index].id+','+data.backlogs[index].car_id+')"><i class="dripicons-document-edit" ></i></button><button style="margin:0 10px" type="button" class="btn btn-danger" onclick="delbacklog('+data.backlogs[index].id+')"><i class="fa fa-minus-circle"></i></button></div></div>');
					});
				}else{
					$('.backlog-body').html("<h6>No Backlog For this Car</h6>");
				} 
				
			});
}
    $("ul#people").siblings('a').attr('aria-expanded','true');
    $("ul#people").addClass("show");
    $("ul#people #cars-list-menu").addClass("active");

    function confirmDelete() {
      if (confirm("Are you sure want to delete?")) {
          return true;
      }
      return false;
    }

    var customer_id = [];
    var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;
    var all_permission = <?php echo json_encode($all_permission) ?>;
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

  $(".deposit").on("click", function() {
        var id = $(this).data('id').toString();
        $("#depositModal input[name='customer_id']").val(id);
  });

  $(".getDeposit").on("click", function() {
        var id = $(this).data('id').toString();
        $.get('customer/getDeposit/' + id, function(data) {
            $(".deposit-list tbody").remove();
            var newBody = $("<tbody>");
            $.each(data[0], function(index){
                var newRow = $("<tr>");
                var cols = '';

                cols += '<td>' + data[1][index] + '</td>';
                cols += '<td>' + data[2][index] + '</td>';
                if(data[3][index])
                    cols += '<td>' + data[3][index] + '</td>';
                else
                    cols += '<td>N/A</td>';
                cols += '<td>' + data[4][index] + '<br>' + data[5][index] + '</td>';
                cols += '<td><div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans("file.action")}}<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu"><li><button type="button" class="btn btn-link edit-btn" data-id="' + data[0][index] +'" data-toggle="modal" data-target="#edit-deposit"><i class="dripicons-document-edit"></i> {{trans("file.edit")}}</button></li><li class="divider"></li>{{ Form::open(['route' => 'customer.deleteDeposit', 'method' => 'post'] ) }}<li><input type="hidden" name="id" value="' + data[0][index] + '" /> <button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="dripicons-trash"></i> {{trans("file.delete")}}</button></li>{{ Form::close() }}</ul></div></td>'
                newRow.append(cols);
                newBody.append(newRow);
                $("table.deposit-list").append(newBody);
            });
            $("#view-deposit").modal('show');
        });
  });

  $("table.deposit-list").on("click", ".edit-btn", function(event) {
        var id = $(this).data('id');
        var rowindex = $(this).closest('tr').index();
        var amount = $('table.deposit-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)').text();
        var note = $('table.deposit-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(3)').text();
        if(note == 'N/A')
            note = '';
        
        $('#edit-deposit input[name="deposit_id"]').val(id);
        $('#edit-deposit input[name="amount"]').val(amount);
        $('#edit-deposit textarea[name="note"]').val(note);
        $('#view-deposit').modal('hide');
    });

    var table = $('#customer-table').DataTable( {
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
                "orderable": false,
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
            {
                extend: 'pdf',
                text: '{{trans("file.PDF")}}',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                extend: 'csv',
                text: '{{trans("file.CSV")}}',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                extend: 'print',
                text: '{{trans("file.Print")}}',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                text: '{{trans("file.delete")}}',
                className: 'buttons-delete',
                action: function ( e, dt, node, config ) {
                    if(user_verified == '1') {
                        customer_id.length = 0;
                        $(':checkbox:checked').each(function(i){
                            if(i){
                                customer_id[i-1] = $(this).closest('tr').data('id');
                            }
                        });
                        if(customer_id.length && confirm("Are you sure want to delete?")) {
                            $.ajax({
                                type:'POST',
                                url:'cars/deletebyselection',
                                data:{
                                    customerIdArray: customer_id
                                },
                                success:function(data){
                                    alert(data);
                                }
                            });
                            dt.rows({ page: 'current', selected: true }).remove().draw(false);
                        }
                        else if(!customer_id.length)
                            alert('No customer is selected!');
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
    } );

  $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

  if(all_permission.indexOf("customers-delete") == -1)
        $('.buttons-delete').addClass('d-none');

    $("#export").on("click", function(e){
        e.preventDefault();
        var customer = [];
        $(':checkbox:checked').each(function(i){
          customer[i] = $(this).val();
        });
        $.ajax({
           type:'POST',
           url:'/exportcustomer',
           data:{
                customerArray: customer
            },
           success:function(data){
             alert('Exported to CSV file successfully! Click Ok to download file');
             window.location.href = data;
           }
        });
    });
</script>
@endsection