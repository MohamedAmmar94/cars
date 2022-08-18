<div class="container">
	 <div class="row">
		<input type="hidden" name="id" id="backlog-id" value="@if(isset($backlog)&&!empty($backlog)){{$backlog->id}}@else new @endif" >
		<input type="hidden" name="car_id" id="backlog-carid" value="@if(isset($car_id)){{$car_id}} @endif">
		<input type="text" name="title" class="form-control" id="backlog-title" value="@if(isset($backlog)&&!empty($backlog)){{$backlog->title}} @endif" required>
		
	 </div>
	 
</div>
