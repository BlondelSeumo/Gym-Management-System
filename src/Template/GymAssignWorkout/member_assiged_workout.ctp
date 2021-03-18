<?php $session = $this->request->session()->read("User");?>
<script>
$(document).ready(function(){
	var box_height = $(".box").height();
	var box_height = box_height + 100 ;
	$(".content-wrapper").css("height",box_height+"px");
});		
</script>


<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-bars"></i>
				<?php echo __("Workout Log");?>
				<small><?php echo __("Assign Workout");?></small>
			  </h1>
			   <?php 
				if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member")
				{ ?>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymAssignWorkout","assignWorkout");?>" class="btn btn-flat btn-custom"><i class="fa fa-plus"></i> <?php echo __("Assign Workout");?></a>
			  </ol>
			  <?php } ?>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<?php
		if(!empty($work_outdata))
		{
			foreach($work_outdata as $data=>$row)
			{				
				foreach($row as $r)
				{
					if(is_array($r))
					{
						$days_array[$data]["start_date"] = $row["start_date"];
						$days_array[$data]["end_date"] = $row["end_date"];
						$day = $r["day_name"];
						$days_array[$data][$day][] = $r;
					}
				}					
			}
			
			
			foreach($days_array as $data=>$row)
			{
				?>
				
				<div class="panel panel-default workout-block" id="remove_panel_<?php echo $data;?>">				
				  <div class="panel-heading">
					<i class="fa fa-calendar"></i> <?php echo __("Start From")." <span class='work_date'>".date($this->Gym->getSettings("date_format"),strtotime($row["start_date"]))."</span> ".__("TO")." <span class='work_date'>".date($this->Gym->getSettings("date_format"),strtotime($row["end_date"]))."</span>";?>
					<a href="<?php echo $this->request->base;?>/GymAssignWorkout/printWorkout/" class="btn btn-sm btn-info pull-right" target="_blank"><?php echo __("Print");?></a>
				  </div>
				  <br>
				<div class="work_out_datalist_header">
					<div class="col-md-2 col-sm-2">  
						<strong><?php echo __("Day name");?></strong>
					</div>
					<div class="col-md-10 col-sm-10 hidden-xs">
						<span class="col-md-3 col-sm-3"><?php echo __("Activity");?></span>
						<span class="col-md-2 col-sm-2"><?php echo __("Sets");?></span>
						<span class="col-md-2 col-sm-2"><?php echo __("Reps");?></span>
						<span class="col-md-2 col-sm-2"><?php echo __("KG");?></span>
						<span class="col-md-2 col-sm-2"><?php echo __("Rest Time");?></span>
					</div>
				</div>				
				<?php 
				foreach($row as $day=>$value)
				{
					if(is_array($value))
					{ 
						
					?>
						<div class="work_out_datalist">
						<div class="col-md-2 col-sm-2 day_name"><?php echo __($day);?></div>
						<div class="col-md-10 col-sm-10">
						<?php foreach($value as $r)
							{?>
							<!-- <div class="col-md-12"> -->
							<span class="col-md-3 col-sm-3 col-xs-12"><?php echo $this->Gym->get_activity_by_id($r["workout_name"]);?></span>   
							<span class="col-md-2 col-sm-2 col-xs-6"><?php echo $r["sets"];?></span>
							<span class="col-md-2 col-sm-2 col-xs-6"><?php echo $r["reps"];?> </span>
							<span class="col-md-2 col-sm-2 col-xs-6"><?php echo $r["kg"];?> </span>
							<span class="col-md-2 col-sm-2 col-xs-6"><?php echo $r["time"];?> </span>
							<!-- </div> -->
						<?php } ?>
						</div>
						</div>
					<?php } 
				}?>				
				</div>
	  <?php } 
		}
		else{
			echo __("No Record Found");
		}?>	
		</div>
</section>