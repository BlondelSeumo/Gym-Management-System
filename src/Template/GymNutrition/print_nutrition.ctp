
<script>window.onload = function(){ window.print(); };</script>
<style>
@media print {
  @page { margin: 0; }
  body { margin: 1.6cm; }
}
</style>
<br>
<br>
<?php 

	echo $this->Html->css('bootstrap.min');
	echo $this->Html->css('gym_custom');
	echo $this->Html->script('jQuery/jQuery-2.1.4.min.js');
	echo $this->Html->script('bootstrap/js/bootstrap.min.js') ;
?>

<div class="col-md-12 box box-default">
<?php $session = $this->request->session()->read("User"); 
	echo "<h4>Nutrition Schedule for ".$session["display_name"]."</h4><hr/>";
?>
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
					  </div>
					  <br>
					<div class="work_out_datalist_header">
						<div class="col-md-4 col-sm-4 col-xs-4">  
							<strong><?php echo __("Day name");?></strong>
						</div>
						<div class="col-md-8 col-sm-8 col-xs-8">						
							<span class="col-md-3 col-sm-3 col-xs-4"><?php echo __('Time');?></span>
							<span class="col-md-9 col-sm-9 col-xs-8"><?php echo __('Description');?></span>
						</div>
					</div>				
						<?php 
						foreach($row as $day=>$value)
						{
							if(is_array($value))
							{ 
							?>
								<div class="work_out_datalist">
								<div class="col-md-4 col-sm-4 col-xs-4 day_name"><?php echo $day;?></div>
								<div class="col-md-8 col-xs-8">
								<?php foreach($value as $r)
									{?>
									<div class="col-md-12">						
									<span class="col-md-3 col-sm-3 col-xs-4"><?php echo $r["nutrition_time"];?> </span>
									<span class="col-md-9 col-sm-9 col-xs-8"><?php echo $r["nutrition_value"];?> 
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
<?php die; ?>