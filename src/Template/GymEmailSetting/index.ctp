<script>
$(document).ready(function(){
	$(".hasDatepicker").datepicker();	
});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-plus"></i>
				<?php echo __("Email Setting");?>
				<small><?php echo __("Email Setting");?></small>
			  </h1>			  
			</section>
		</div>
		<hr>
		<div class="box-body">
		<form class="validateForm form-horizontal" method="post" role="form">		
		<div class='form-group'>	
			<label class="control-label col-md-2" for="email"><?php  echo __("Host");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">
				<input type="text" name="host" class="form-control validate[required]" value="<?php echo $email["host"];?>">
			</div>	
		</div>
		<div class='form-group'>	
			<label class="control-label col-md-2" for="email"><?php  echo __("Port");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">				
					<input type="text" name="port" class="form-control validate[required]" value="<?php echo $email["port"];?>">
			</div>
		</div>
		<div class='form-group'>	
			<label class="control-label col-md-2" for="email"><?php  echo __("Username");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">					
					<input type="text" name="username" class="form-control validate[required]" value="<?php echo $email["username"];?>">
			</div>
		</div>
		<div class='form-group'>	
			<label class="control-label col-md-2" for="email"><?php  echo __("Password");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">								
					<input type="password" name="password" class="form-control validate[required]" value="<?php echo $email["password"];?>">
			</div>
		</div>
		<div class="col-md-offset-2 col-md-2">
			<input type="submit" value="<?php echo __("Save");?>" name="save_mail" class="btn btn-flat btn-success">
		</div>
		</form>
		<div class="clearfix"></div>
		<hr/>
		<form class="validateForm form-horizontal" method="post" role="form">
		<input type="hidden" name="host" value="<?php echo $email["host"];?>">
		<input type="hidden" name="port" value="<?php echo $email["port"];?>">
		<input type="hidden" name="username" value="<?php echo $email["username"];?>">
		<input type="hidden" name="password" value="<?php echo $email["password"];?>">
			<div class='form-group'>	
				<label class="control-label col-md-2" for="email"><?php  echo __("To");?><span class="text-danger"> *</span></label>
				<div class="col-md-3">					
						<input type="text" name="to" class="form-control validate[required]">
				</div>
			</div>
			<div class="col-md-2 col-md-offset-2">
				<input type="submit" value="<?php echo __("Send Test Mail");?>" name="send_test" class="btn btn-flat btn-success">
			</div>
		</form>
		</div>
	</div>
</section>