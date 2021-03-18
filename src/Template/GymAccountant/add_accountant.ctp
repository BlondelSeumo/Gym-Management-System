<?php 
echo $this->Html->css('bootstrap-multiselect');
echo $this->Html->script('bootstrap-multiselect');
?>
<script type="text/javascript">
$(document).ready(function() {
	$('#specialization').multiselect({
		includeSelectAllOption: true	
	});
	$(".date").datepicker( "option", "dateFormat", "<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>" );
	<?php
	if($edit)
	{
		$date = $data['birth_date'];
		$timestamp = $date->getTimestamp();
		$date->setTimestamp($timestamp);
		$birthday = $date->format($this->Gym->getSettings("date_format"));	
	?>
	$( ".date" ).datepicker( "setDate", new Date("<?php echo $birthday; ?>" ));
	<?php } ?>
});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-user"></i>
				<?php echo $title;?>
				<small><?php echo __("Accountant");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymAccountant","accountantList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Accountant List");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">			
			<?php				
			echo $this->Form->create("addaccountant",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form"]);
			echo "<fieldset><legend>". __('Personal Information')."</legend>";
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("First Name").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"first_name","class"=>"form-control validate[required,custom[onlyLetterSp],maxSize[30]]","value"=>(($edit)?$data['first_name']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Middle Name").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"middle_name","class"=>"form-control validate[custom[onlyLetterSp],maxSize[30]]","value"=>(($edit)?$data['middle_name']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Last Name").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"last_name","class"=>"form-control validate[required,custom[onlyLetterSp],maxSize[30]]","value"=>(($edit)?$data['last_name']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Gender").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6 checkbox">';
			$radio = [
						['value' => 'male', 'text' => __('Male'),"class"=>"gender"],
						['value' => 'female', 'text' => __('Female'),"class"=>"gender"]
					];
			echo $this->Form->radio("gender",$radio,['default'=>($edit)?$data["gender"]:'male']);			
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Date of birth").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"birth_date","class"=>"form-control dob date validate[required]","value"=>(($edit)?date($this->Gym->getSettings("date_format"),strtotime($data['birth_date'])):'')]);
			echo "</div>";	
			echo "</div>";				
			
			echo "<fieldset><legend>". __('Contact Information')."</legend>";
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Home Town Address").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"address","class"=>"form-control validate[required,maxSize[150]]","value"=>(($edit)?$data['address']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("City").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"city","class"=>"form-control validate[required,custom[onlyLetterSp],maxSize[20]]","value"=>(($edit)?$data['city']:'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Mobile Number").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo '<div class="input-group">';
			echo '<div class="input-group-addon">+'.$this->Gym->getCountryCode($this->Gym->getSettings("country")).'</div>';
			echo $this->Form->input("",["label"=>false,"name"=>"mobile","class"=>"form-control validate[required,custom[onlyNumberSp],maxSize[15]]","value"=>(($edit)?$data['mobile']:''),"style"=>"z-index:1"]);
			echo "</div>";	
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Phone").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"phone","class"=>"form-control validate[custom[onlyNumberSp],maxSize[15]]","value"=>(($edit)?$data['phone']:'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Email").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"email","class"=>"form-control validate[required,custom[email]]","value"=>(($edit)?$data['email']:'')]);
			echo "</div>";	
			echo "</div>";			
			echo "</fieldset>";
			
			echo "<fieldset><legend>". __('Login Information')."</legend>";
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Username").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"username","class"=>"form-control validate[required]","value"=>(($edit)?$data['username']:''),"readonly"=> (($edit)?true:false)]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Password").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->password("",["label"=>false,"name"=>"password","class"=>"form-control validate[required]","value"=>(($edit)?$data['password']:'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Display Image").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-4">';
			echo $this->Form->file("image",["class"=>"form-control"]);
			$image = ($edit && !empty($data['image'])) ? $data['image'] : "Thumbnail-img.png";
			echo "<br><img src='{$this->request->webroot}webroot/upload/{$image}' class='img-responsive'>";
			echo "</div>";	
			echo "</div>";			
			echo "</fieldset>";
			
			echo "<div class='col-md-offset-2'>";
			echo $this->Form->button(__("Save Accountant"),['class'=>"btn btn-flat btn-success","name"=>"add_group"]);
			echo "</div>";
			echo $this->Form->end();
			?>	
		</div>	
		<div class="overlay gym-overlay">
		  <i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
</section>