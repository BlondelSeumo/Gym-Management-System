<?php
$session = $this->request->session()->read("User");
echo $this->Html->css('select2.css');
echo $this->Html->script('select2.min');
?>
<script>
$(document).ready(function(){
	$(".memlist").select2();
	$(".date").datepicker( "option", "dateFormat", "<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>" );
	<?php if($edit){ ?>
	$( ".date" ).datepicker( "setDate", new Date("<?php echo date($this->Gym->getSettings("date_format"),strtotime($data['result_date'])); ?>" ));
	<?php } ?>
	$(".validateForm").validationEngine();
});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-plus"></i>
				<?php echo $title;?>
				<small><?php echo __("Workout Daily");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymDailyWorkout","workoutList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Workout List");?></a>
				&nbsp;
				<a href="<?php echo $this->Gym->createurl("GymDailyWorkout","addWorkout");?>" class="btn btn-flat btn-custom"><i class="fa fa-plus"></i> <?php echo __("Add Workout");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<?php
			echo $this->Form->create("addWorkout",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form"]);
		?>
		
		<?php 
		if($session["role_name"] == "member")
		{
			echo $this->Form->input("",["type"=>"hidden","name"=>"user_id","label"=>false,"class"=>"form-control","value" => $session['id']]);
		}
		else{ ?>
		<div class='form-group'>
			<label class="control-label col-md-2" for="email"><?php echo __("Member");?><span class="text-danger"> *</span></label>
			<div class="col-md-8">
					<?php
						echo $this->Form->select("user_id",$members,["default"=>($edit || $set)?$data['user_id']:"","class"=>"memlist"]);
					 ?>
			</div>			
		</div>
	<?php } ?>
		
		<div class='form-group'>
			<label class="control-label col-md-2" for="mes"><?php echo __("Result Measurement");?><span class="text-danger"> *</span></label>
			<div class="col-md-8">
				<?php 
					$mesuarment = array("Height"=>"Height","Weight"=>"Weight","Chest"=>"Chest","Waist"=>"Waist","Thigh"=>"Thigh","Arms"=>"Arms","Fat"=>"Fat");
					echo $this->Form->select("result_measurment",$mesuarment,["default"=>($edit || $set)?$data["result_measurment"]:"","empty"=>__("Select Result Measurement"),"class"=>"form-control validate[required]"]);
				?>
			</div>			
		</div>
		<div class='form-group'>
			<label class="control-label col-md-2" for="email"><?php echo __("Result");?><span class="text-danger"> *</span></label>
			<div class="col-md-8">
			<?php echo $this->Form->input("",["name"=>"result","label"=>false,"class"=>"form-control validate[required,custom[onlyNumberSp],maxSize[20]]","value"=>($edit)?$data["result"]:""]); ?>
			</div>
		</div>
		<div class='form-group'>
			<label class="control-label col-md-2" for="email"><?php echo __("Result Date");?><span class="text-danger"> *</span></label>
			<div class="col-md-8">
				<?php echo $this->Form->input("",["label"=>false,"class"=>"form-control date validate[required]","name"=>"result_date","value"=>($edit)?date($this->Gym->getSettings("date_format"),strtotime($data["result_date"])):""]); ?>
			</div>
		</div>
		<div class='form-group'>
			<label class="control-label col-md-2" for="email"><?php echo __("Display Image");?></label>
			<div class="col-md-4">
				<?php
				echo $this->Form->file("image",["class"=>""]);
				$image = ($edit && !empty($data['image'])) ? $data['image'] : "measurement.png";
				?>
			<br><img class="measurement_img" src="<?php echo $this->request->webroot ."/webroot/upload/{$image}";?>">
			</div>	
			</div>
		<div class="col-md-offset-2 sucess_measument">
			<input type="submit" class="btn btn-flat btn-success" value="<?php echo __("Save Measurement"); ?>">
		</div>
		<?php 
		$this->Form->end();
		?>
		<!-- END -->
		</div>
	</div>
</section>