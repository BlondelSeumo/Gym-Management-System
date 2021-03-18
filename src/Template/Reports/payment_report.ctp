<style>
.content-header>.breadcrumb{
	position: relative;
}
</style>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header box_payment">
			<section class="content-header bread_payment">
				<h1>
					<i class="fa fa-bar-chart"></i>
					<?php echo __("Membership Payment report");?>
					<small><?php echo __("Reports");?></small>
				</h1>
				<ol class="breadcrumb">
					<a href="<?php echo $this->Gym->createurl("Reports","membershipReport");?>" class="btn btn-flat btn-custom "><i class="fa fa-bar-chart"></i> <?php echo __("Membership Report");?></a>
					&nbsp;
					<a href="<?php echo $this->Gym->createurl("Reports","attendanceReport");?>" class="btn btn-flat btn-custom"><i class="fa fa-bar-chart"></i> <?php echo __("Attendance Report");?></a>
					&nbsp;
					<a href="<?php echo $this->Gym->createurl("Reports","membershipStatusReport");?>" class="btn btn-flat btn-custom"><i class="fa fa-pie-chart"></i> <?php echo __("Membership Status Report");?></a>
					&nbsp;
					<a href="<?php echo $this->Gym->createurl("Reports","paymentReport");?>" class="btn active btn-flat btn-custom"><i class="fa fa-bar-chart"></i> <?php echo __("Payment Report");?></a>
					&nbsp;
					<a href="<?php echo $this->Gym->createurl("Reports","monthlyworkoutreport");?>" class="btn btn-flat btn-custom"><i class="fa fa-bar-chart"></i> <?php echo __("Members Monthly Workout Report");?></a>
				</ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
			<?php 
			$options = Array(
				'title' => __('Fee Payment Report By Month','gym_mgt'),
				'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
				'legend' =>Array('position' => 'right',
							'textStyle'=> Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
				
				'hAxis' => Array(
					'title' => __('Month','gym_mgt'),
					'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
					'textStyle' => Array('color' => '#66707e','fontSize' => 11),
					'maxAlternation' => 2
					),
				'vAxis' => Array(
					'title' => __('Fee Payment','gym_mgt'),
					 'minValue' => 0,
					'maxValue' => 5,
					 'format' => '#',
					'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
					'textStyle' => Array('color' => '#66707e','fontSize' => 12)
					),
				'colors' => array('#22BAA0') 		
			);
			
			$GoogleCharts = new GoogleCharts;
			$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
			
			?>
			<?php 
			if(isset($result) && empty($result)) {?>
		  
				<div class="clear col-md-12">
					<i>
					<?php echo __("There is not enough data to generate report.");?>
					</i>
				</div>
			<?php } ?>
    
			<div id="chart_div" style="width: 100%; height: 500px;"></div>
  
			<!-- Javascript --> 
			<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
			<script type="text/javascript">
					<?php if(!empty($result)) {
								echo $chart;
						}?>
			</script>
 
 <div class="list_table">
			<?php if(isset($result)){ ?>
				<h3 class="report-head">
					<?php echo __("Payment Report List");?>
				</h3>
				<table class="mydataTable table table-striped" width="100%">
					<thead>
						<tr>
							<th>#</th>
							<th><?php echo __('Month');?></th>
							<th><?php echo __('Total Amount');?></th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$count =1 ; 
						$month =array('1'=>"January",'2'=>"February",'3'=>"March",'4'=>"April",'5'=>"May",'6'=>"June",'7'=>"July",'8'=>"August",'9'=>"September",'10'=>"Octomber",'11'=>"November",'12'=>"December",);
						foreach($result as $att){ ?>
							<tr>
								<td><?php echo $count; ?></td>
								<td><?php echo __($month[$att["date"]]); ?></td>
								<td><?php echo $att['count']; ?></td>
								
							</tr>
							
						<?php $count++;
						}
						?>
					</tbody>
				</table>
			<?php } ?>
			</div>
		<!-- END -->
		</div>	
		<div class="overlay gym-overlay">
			<i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
</section>
<script>
$(document).ready(function(){		
	$(".mydataTable").DataTable({
		"responsive": true,
		"order": [[ 0, "asc" ]],
		"language" : {<?php echo $this->Gym->data_table_lang();?>},
	});
});		
</script>