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
				<?php echo __("Nutrition Schedule List");?>
				<small><?php echo __("Nutrition Schedule");?></small>
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
			if(!empty($nutrition_data))
			{
				foreach($nutrition_data as $data=>$row)
				{				
					foreach($row as $r)
					{
						if(is_array($r))
						{
							$days_array[$data]["start_date"] = $row["start_date"];
							$days_array[$data]["expire_date"] = $row["expire_date"];
							$day = $r["day_name"];
							$days_array[$data][$day][] = $r;
						}
					}					
				}
				
				foreach($days_array as $data=>$row)
				{?>
					<div class="panel panel-default workout-block" id="remove_panel_<?php echo $data;?>">				
					  <div class="panel-heading">
						<i class="fa fa-calendar"></i> <?php echo __("Start From")." <span class='work_date'>".date($this->Gym->getSettings("date_format"),strtotime($row["start_date"]))."</span> ".__("TO")." <span class='work_date'>".date($this->Gym->getSettings("date_format"),strtotime($row["expire_date"]))."</span>";?>
						<a href="<?php echo $this->request->base;?>/GymNutrition/printNutrition/" class="btn btn-sm btn-info pull-right" target="_blank" ><?php echo __("Print");?></a>
					 </div>
					  <br>
					<div class="work_out_datalist_header">
						<div class="col-md-4 col-sm-4">  
							<strong><?php echo __("Day name");?></strong>
						</div>
						<div class="col-md-8 col-sm-8 hidden-xs">						
							<span class="col-md-3 col-sm-3 col-xs-12"><?php echo __('Time');?></span>
							<span class="col-md-9 col-sm-9 col-xs-12"><?php echo __('Description');?></span>
						</div>
					</div>				
						<?php 
						foreach($row as $day=>$value)
						{
							if(is_array($value))
							{ 
							?>
								<div class="work_out_datalist">
								<div class="col-md-4 col-sm-4 day_name"><?php echo $day;?></div>
								<div class="col-md-8 col-xs-8">
								<?php foreach($value as $r)
									{?>
									<div class="col-md-12">						
									<span class="col-md-3 col-sm-3 col-xs-12"><?php echo $r["nutrition_time"];?> </span>
									<span class="col-md-9 col-sm-9 col-xs-12"><?php echo $r["nutrition_value"];?> 
									</div>
								<?php } ?>
								</div>
								</div>
							<?php } 
						}?>				
					</div>
			  <?php } 
			}else{
				echo "<i>".__("No record found.")."</i>";
			}?>	
		</div>
	</div>
</section>
