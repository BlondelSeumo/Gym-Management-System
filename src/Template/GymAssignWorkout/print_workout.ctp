<?php $session = $this->request->session()->read("User");

	echo $this->Html->css('bootstrap.min');
	echo $this->Html->css('gym_custom');
	echo $this->Html->script('jQuery/jQuery-2.1.4.min.js');
		echo $this->Html->script('bootstrap/js/bootstrap.min.js') ;
?>
<script>window.onload = function(){ window.print(); };</script>
<style>
@media print {
  @page { margin: 0; }
  body { margin: 1.6cm; }
}
</style>



<div class="">
	<br>
	<div class="col-md-12 box box-default">		
	<?php echo "<h4>".$session["display_name"]."'s Workouts</h4>"; ?>
		<hr />
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
			{?>
				<div class="panel panel-default workout-block" id="remove_panel_<?php echo $data;?>">				
				  <div class="panel-heading">
					<i class="fa fa-calendar"></i> <?php echo __("Start From")." <span class='work_date'>".date($this->Gym->getSettings("date_format"),strtotime($row["start_date"]))."</span> ".__("TO")." <span class='work_date'>".date($this->Gym->getSettings("date_format"),strtotime($row["end_date"]))."</span>";?>
				    </div>
				  <br>
				<div class="work_out_datalist_header">
					<div class="col-md-2 col-sm-2 col-xs-2">  
						<strong><?php echo __("Day name");?></strong>
					</div>
					<div class="col-md-10 col-sm-10 col-xs-10">
						<span class="col-md-3 col-sd-3 col-xs-3"><?php echo __("Activity");?></span>
						<span class="col-md-3 col-sd-3 col-xs-3"><?php echo __("Sets");?></span>
						<span class="col-md-2 col-sd-2 col-xs-2"><?php echo __("Reps");?></span>
						<span class="col-md-2 col-sd-2 col-xs-2"><?php echo __("KG");?></span>
						<span class="col-md-2 col-sd-2 col-xs-2"><?php echo __("Rest Time");?></span>
					</div>
				</div>				
				<?php 
				foreach($row as $day=>$value)
				{
					if(is_array($value))
					{ 
					?>
						<div class="work_out_datalist">
						<div class="col-md-2 col-sm-2 col-xs-2 day_name"><?php echo $day;?></div>
						<div class="col-md-10 col-sm-10 col-xs-10">
						<?php foreach($value as $r)
							{?>
							<div class="col-md-12 col-sm-12 col-xs-12">
							<span class="col-md-3 col-sm-3 col-xs-3"><?php echo $this->Gym->get_activity_by_id($r["workout_name"]);?></span>   
							<span class="col-md-3 col-sm-3 col-xs-3"><?php echo $r["sets"];?></span>
							<span class="col-md-2 col-sm-2 col-xs-2"><?php echo $r["reps"];?> </span>
							<span class="col-md-2 col-sm-2 col-xs-2"><?php echo $r["kg"];?> </span>
							<span class="col-md-2 col-sm-2  col-xs-2"><?php echo $r["time"];?> </span>
							</div>
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
</div>
</div>
	<?php die; ?>