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
				<i class="fa fa-pie-chart"></i>
				<?php echo __("Membership Status Report");?>
				<small><?php echo __("Reports");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("Reports","membershipReport");?>" class="btn btn-flat btn-custom"><i class="fa fa-bar-chart"></i> <?php echo __("Membership Report");?></a>
				&nbsp;
				<a href="<?php echo $this->Gym->createurl("Reports","attendanceReport");?>" class="btn btn-flat btn-custom"><i class="fa fa-bar-chart"></i> <?php echo __("Attendance Report");?></a>
				&nbsp;
				<a href="<?php echo $this->Gym->createurl("Reports","membershipStatusReport");?>" class="btn active btn-flat btn-custom"><i class="fa fa-pie-chart"></i> <?php echo __("Membership Status Report");?></a>
				&nbsp;
				<a href="<?php echo $this->Gym->createurl("Reports","paymentReport");?>" class="btn btn-flat btn-custom"><i class="fa fa-bar-chart"></i> <?php echo __("Payment Report");?></a>
				&nbsp;
				<a href="<?php echo $this->Gym->createurl("Reports","monthlyworkoutreport");?>" class="btn btn-flat btn-custom"><i class="fa fa-bar-chart"></i> <?php echo __("Members Monthly Workout Report");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
			<?php
			 $options = Array(
								'title' => __('Membership by status'),
								'colors' => array('#22BAA0','#F25656','#12AFCB')
								);
			$GoogleCharts = new GoogleCharts;
			$chart = $GoogleCharts->load( 'PieChart' , 'chart_div' )->get( $chart_array , $options );	
			?>	
			<?php //debug($data);die;
				if(!isset($data) && empty($data)) {?>
					<div class="clear col-md-12">
						<i><?php echo __("There is not enough data to generate report.");?></i>
					</div>
			<?php } ?>
			<div id="chart_div" style="width: 100%; height: 500px;"></div>		  
				<!-- Javascript --> 
			<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
			<script type="text/javascript">
				<?php 
					if(!empty($data)) {
						echo $chart;
					}?>
			</script>
 		
		<!-- END -->
		</div>
	</div>
</section>