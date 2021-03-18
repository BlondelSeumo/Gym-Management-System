<input type='hidden' value='<?php echo json_encode($date_array);?>' id='dates'>
<script>
$(document).ready(function(){
	$(".validateForm").validationEngine();
	
	var enableDays = jQuery("#dates").val();
	var enableDays = jQuery.parseJSON(enableDays);
	
	function enableAllTheseDays(date) {
        var sdate = jQuery.datepicker.formatDate('yy-mm-dd', date);
      
        if(jQuery.inArray(sdate, enableDays) != -1) {
			
            return [true];
        }
		return [false];
    }
	$("#enabledate").datepicker({
		//dateFormat: 'yy-mm-dd',
		dateFormat: '<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>',
		changeMonth: true,
		changeYear: true,
		beforeShowDay: enableAllTheseDays,
		   onSelect : function(){
			  var date = $('#enabledate').datepicker('getDate');  
				date.setDate(date.getDate());
				date1=formatDate(date);
			$('#hidedate').val(date1);
			
		}   
		
	}); 
});

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}

var box_height = $(".box").height();
var box_height = box_height + 100 ;
$(".content-wrapper").css("height",box_height+"px");
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-eye"></i>
				<?php echo __("View Workout");?>
				<small><?php echo __("Workout Daily");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymDailyWorkout","workoutList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Workout List");?></a>
				&nbsp;
				<?php $role = $this->request->session()->read('User.role_name');
				if($role != 'accountant'){
					?>
					<a href="<?php echo $this->Gym->createurl("GymDailyWorkout","addMeasurment");?>" class="btn btn-flat btn-custom"><i class="fa fa-plus"></i> <?php echo __("Add Measurement");?></a>
				<?php } ?>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<form method="post" class="form-horizontal validateForm"> 
         <div class="col-md-12">
			<h3 class="no-margin"><?php echo $member_name."'s Workout";?></h3>
		 </div>
		 <br><br><br>
        <div class="form-group">
			<label class="col-sm-1 control-label" for="curr_date"><?php echo __("Date");?></label>
			<div class="col-sm-3 module_padding">				
				<input type='hidden' value='<?php echo $uid;?>' name="uid">
				<input type="text" name="schedule_date1" class="validate[required] date1 form-control" id="enabledate" value="<?php if(isset($_REQUEST['schedule_date'])){ echo $this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($_REQUEST['schedule_date'])));}else{echo "";}?>" autocomplete="off">
				<input type="hidden" name="schedule_date" class="validate[required] date1 form-control" id="hidedate" value="<?php if(isset($schedule_date)){ echo $schedule_date;}else{echo "";}?>">
			
			</div>
			<div class="col-sm-3">
			<input type="submit" value="<?php echo __("View Workouts");?>" name="view_workouts" class="btn btn-flat btn-success" >
			</div>
		</div>
          </form>
		<?php
		
		if(isset($workouts))
		{
			foreach($workouts as $workout)
			{ ?>
				<div class="col-md-12">
				<?php 
				$workout_note = ($workout['note'] != '')?$workout['note']:__("No");
				echo __("Description : ").$workout_note;?>
				</div>
				<div class="col-md-10 activity-data no-padding">
					<div class="workout_datalist_header">
						<h2><?php 
						if($workout['GymUserWorkout']["workout_name"] != null) {
							echo $this->Gym->get_activity_by_id($workout['GymUserWorkout']["workout_name"]);
						}
						?></h2>
					</div>
					<div class="col-md-10 workout_datalist no-padding"> 
						<?php 
						$i = 1;
						for($i;$i<=$workout['GymUserWorkout']["sets"];$i++)
						{ ?>
						<div class="col-md-6 sets-row no-paddingleft">	
							<span class="text-center sets_counter"><?php echo $i;?></span>
							<span class="sets_kg"><?php echo $workout['GymUserWorkout']["kg"];?> Kg</span>								
							<span class="col-md-2 reps_count"><?php echo $workout['GymUserWorkout']["reps"];?></span>
						</div>
				  <?php } ?>					
					</div>
					<div class="border_line"></div>
				</div>		
	  <?php }
		}
		?>		  
		<!-- END -->
		</div>
		<div class='overlay gym-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>
