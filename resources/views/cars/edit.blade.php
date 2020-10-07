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
                        <h4>{{trans('file.Updatecar')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        {!! Form::open(['route' => ['cars.update',$lims_customercar_data->id], 'method' => 'put', 'files' => true]) !!}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{trans('file.Choose Customer')}} <strong>* </strong> </label>
                                    <select required class="form-control selectpicker" data-live-search="true"  name="customer_id">
                                        @foreach($customers as $customer)
                                            <option value="{{$customer->id}}" {{  ($lims_customercar_data->customer->id==$customer->id) ? 'selected' : '' }}>{{$customer->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{trans('file.chassis')}} <strong>* </strong> </label>
                                    <input type="text" name="chassis" required value="{{$lims_customercar_data->chassis}}" class="form-control">
                                    @if($errors->has('chassis'))
                                        <span>
                                            <strong>{{ $errors->first('chassis') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{trans('file.model')}}  <strong>* </strong></label>
                                    <input type="text" name="model" value="{{$lims_customercar_data->model}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{trans('file.mileage')}} <strong>* </strong></label>
                                    <input type="text" name="mileage" value="{{$lims_customercar_data->mileage}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{trans('file.plate')}} <strong>* </strong></label>
                                    <input type="text" name="plate" required value="{{$lims_customercar_data->plate}}" class="form-control">
                                    @if($errors->has('plate'))
                                        <span>
                                       <strong>{{ $errors->first('plate') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="pos" value="0">
                            <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary">
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">

    $("ul#people").siblings('a').attr('aria-expanded','true');
    $("ul#people").addClass("show");
        
    var customer_group = $("input[name='customer_group']").val();
    $('select[name=customer_group_id]').val(customer_group);
</script>
@endsection