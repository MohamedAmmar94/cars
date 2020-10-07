 <?php $__env->startSection('content'); ?>
<?php if(session()->has('not_permitted')): ?>
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('not_permitted')); ?></div> 
<?php endif; ?>
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><?php echo e(trans('file.Updatecar')); ?></h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small><?php echo e(trans('file.The field labels marked with * are required input fields')); ?>.</small></p>
                        <?php echo Form::open(['route' => ['cars.update',$lims_customercar_data->id], 'method' => 'put', 'files' => true]); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo e(trans('file.Choose Customer')); ?> <strong>* </strong> </label>
                                    <select required class="form-control selectpicker" data-live-search="true"  name="customer_id">
                                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($customer->id); ?>" <?php echo e(($lims_customercar_data->customer->id==$customer->id) ? 'selected' : ''); ?>><?php echo e($customer->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo e(trans('file.chassis')); ?> <strong>* </strong> </label>
                                    <input type="text" name="chassis" required value="<?php echo e($lims_customercar_data->chassis); ?>" class="form-control">
                                    <?php if($errors->has('chassis')): ?>
                                        <span>
                                            <strong><?php echo e($errors->first('chassis')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo e(trans('file.model')); ?>  <strong>* </strong></label>
                                    <input type="text" name="model" value="<?php echo e($lims_customercar_data->model); ?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo e(trans('file.mileage')); ?> <strong>* </strong></label>
                                    <input type="text" name="mileage" value="<?php echo e($lims_customercar_data->mileage); ?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo e(trans('file.plate')); ?> <strong>* </strong></label>
                                    <input type="text" name="plate" required value="<?php echo e($lims_customercar_data->plate); ?>" class="form-control">
                                    <?php if($errors->has('plate')): ?>
                                        <span>
                                       <strong><?php echo e($errors->first('plate')); ?></strong>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="pos" value="0">
                            <input type="submit" value="<?php echo e(trans('file.submit')); ?>" class="btn btn-primary">
                        </div>
                        <?php echo Form::close(); ?>

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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>