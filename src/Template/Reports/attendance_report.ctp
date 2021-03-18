<style>
.content-header>.breadcrumb{
	position: relative;
}
</style>
<script type="text/javascript">
	$(document).ready(function() { 
		$("#startDate").datepicker({
			dateFormat: '<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>',
			changeMonth: true,
			changeYear: true,
			onSelect: function() {
				var date = $('#startDate').datepicker('getDate');
				date.setDate(date.getDate());
				$("#endDate").datepicker("option","minDate", date);  
			}
		}); 
		$("#endDate").datepicker({
			dateFormat: '<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>',
			changeMonth: true,
			changeYear: true,
		}); 
		
	});
		
	$(document).ready(function(){
		$('#attendance').validationEngine();
	});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header box_payment">
			<section class="content-header bread_payment">
				<h1>
					<i class="fa fa-bar-chart"></i>
					<?php echo __("Attendance Report");?>
					<small><?php echo __("Reports");?></small>
				</h1>
				<ol class="breadcrumb">
					<a href="<?php echo $this->Gym->createurl("Reports","membershipReport");?>" class="btn btn-flat btn-custom "><i class="fa fa-bar-chart"></i> <?php echo __("Membership Report");?></a>
					&nbsp;
					<a href="<?php echo $this->Gym->createurl("Reports","attendanceReport");?>" class="btn btn-flat active btn-custom"><i class="fa fa-bar-chart"></i> <?php echo __("Attendance Report");?></a>
					&nbsp;
					<a href="<?php echo $this->Gym->createurl("Reports","membershipStatusReport");?>" class="btn btn-flat btn-custom"><i class="fa fa-pie-chart"></i> <?php echo __("Membership Status Report");?></a>
					&nbsp;
					<a href="<?php echo $this->Gym->createurl("Reports","paymentReport");?>" class="btn btn-flat btn-custom"><i class="fa fa-bar-chart"></i> <?php echo __("Payment Report");?></a>
					&nbsp;
					<a href="<?php echo $this->Gym->createurl("Reports","monthlyworkoutreport");?>" class="btn btn-flat btn-custom"><i class="fa fa-bar-chart"></i> <?php echo __("Members Monthly Workout Report");?></a>
				</ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
			<form method="post" id="attendance">  
				<div class="form-group col-md-3">
					<label for="exam_id"><?php echo __('Start Date');?></label><span class='text-danger'> *</span>
					<input type="text" autocomplete="off" class="form-control sdate" required name="sdate" id="startDate"  placeholder="<?php echo  $this->Gym->get_db_format(date($this->Gym->getSettings("date_format")));?>">				
				</div>
				<div class="form-group col-md-3">
					<label for="exam_id"><?php echo __('End Date');?></label><span class='text-danger'> *</span>
					<input type="text" autocomplete="off" class="form-control edate" required name="edate" id="endDate"  placeholder="<?php echo $this->Gym->get_db_format(date($this->Gym->getSettings("date_format")));?>">					
				</div>
				<div class="form-group col-md-3 button-possition">
					<label for="subject_id">&nbsp;</label>
					<input type="submit" name="attendance_report" Value="<?php echo __('Go');?>"  class="btn btn-flat btn-success"/>
				</div>   	
			</form>
			<?php
			if(isset($_REQUEST['attendance_report']))
			{
				$options = Array(
					'title' => __('Member Attendance Report','gym_mgt'),
					'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
					'legend' =>Array('position' => 'right',
					'textStyle'=> Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
					
					'hAxis' => Array(
						'title' =>  __('Class','gym_mgt'),
						'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
						'textStyle' => Array('color' => '#66707e','fontSize' => 10),
						'maxAlternation' => 2
					),
					'vAxis' => Array(
						'title' =>  __('No of Member','gym_mgt'),
						'minValue' => 0,
						'maxValue' => 6,
						'format' => '#',
						'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
						'textStyle' => Array('color' => '#66707e','fontSize' => 12)
					),
					'colors' => array('#22BAA0','#f25656')
				);
				$GoogleCharts = new GoogleCharts;
				if(isset($report_2) && count($report_2) >0)
				{
					$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
				?>
					<div id="chart_div" style="width: 100%; height: 500px;margin-top: 100px;"></div>
				  
					<!-- Javascript --> 
				  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
				  <script type="text/javascript">
						<?php echo $chart;?>
					</script>
				<?php 
				}
				if(isset($report_2) && empty($report_2)) {?>
  
					<div class="clear col-md-12">
						<i><?php echo __("There is not enough data to generate report.");?></i>
					</div>
			<?php }
			}?>	
			<hr>
			<div class="list_table">
			<?php if(isset($attendance)){ ?>
				<h3 class="report-head">
					<?php echo __("Attendance Report List");?>
				</h3>
				<table class="mydataTable table table-striped" width="100%">
					<thead>
						<tr>
							<th>#</th>
							<th><?php echo __('Name');?></th>
							<th><?php echo __('Class Name');?></th>
							<th><?php echo __('Attendance Date'); ?></th>
							<th><?php echo __('Status');?></th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$count =1 ; 
						foreach($attendance as $att){ ?>
							<tr>
								<td><?php echo $count; ?></td>
								<td><?php echo $this->Gym->get_user_data($att['user_id'],'first_name').' '.$this->Gym->get_user_data($att['user_id'],'last_name'); ?></td>
								<td><?php  echo $this->Gym->get_class_by_id($att['class_id']); ?></td>
								<td><?php echo $this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($att['attendance_date']))); ?></td>
								<td><?php echo $att['status']; ?></td>
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
		"order": [[ 1, "asc" ]],
		"language" : {<?php echo $this->Gym->data_table_lang();?>}
	});
});		
</script>