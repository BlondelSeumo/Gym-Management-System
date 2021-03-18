<script type="text/javascript">
$(document).ready(function() {		
	var box_height = $(".box").height();
	var box_height = box_height + 100 ;
	$(".content-wrapper").css("height",box_height+"px");
	
	$(".datepick").datepicker({format: 'yyyy-mm-dd'});
	
	$(".content-wrapper").css("min-height","600px");
});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-user"></i>
				<?php echo __("Profile Setting");?>
				<small><?php echo __("Admin");?></small>
			  </h1>			 
			</section>
		</div>
		<hr>
		<div class="box-body">
		<?php				
			echo $this->Form->create("editprofile",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form"]);
			echo "<fieldset><legend>". __('Personal Information')."</legend>";
						
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("First Name").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"first_name","class"=>"form-control validate[required]","value"=>(($edit)?$data['first_name']:'')]);
			echo "</div>";	
			echo "</div>";	
						
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Last Name").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"last_name","class"=>"form-control validate[required]","value"=>(($edit)?$data['last_name']:'')]);
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
			echo $this->Form->input("",["label"=>false,"name"=>"username","class"=>"form-control validate[required]","value"=>(($edit)?$data['username']:'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Password").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->password("",["label"=>false,"name"=>"password","class"=>"form-control"]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Display Image").'</label>';
			echo '<div class="col-md-4">';
			echo $this->Form->file("image",["class"=>"form-control"]);
			$image = ($edit && !empty($data['image'])) ? $data['image'] : "Thumbnail-img.png";
			echo "<br><img src='{$this->request->webroot}webroot/upload/{$image}'>";
			echo "</div>";	
			echo "</div>";			
			echo "</fieldset>";				
			
			echo "<br>";
			echo $this->Form->button(__("Save Details"),['class'=>"col-md-offset-2 btn btn-flat btn-success","name"=>"add_member"]);
			echo $this->Form->end();
		?>
		</div>	
		<div class="overlay gym-overlay">
			<i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
</section>
