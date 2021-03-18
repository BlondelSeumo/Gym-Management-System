<?php
echo $this->Html->css('fullcalendar');
echo $this->Html->script('moment.min');
echo $this->Html->script('fullcalendar.min');
echo $this->Html->script('lang-all');
?>
<style>
	.content-wrapper, .right-side {   
		background-color: #F1F4F9 !important;
	}
	.panel-heading{
		height: 52px;
		background-color: #1DB198;
		padding: 0 0 0 21px;
		margin: 0;
	}
	.panel-heading .panel-title {	
		font-size: 16px;
		color :#eee;
		float: left;
		margin: 0;
		padding: 0;
		line-height :3em;
		font-weight: 600; 
	}
</style>
<script>	
	 $(document).ready(function() {	
		 $('#calendar').fullCalendar({
			header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},
			timeFormat: 'H(:mm)',
			lang: '<?php echo $cal_lang;?>',
			editable: false,
			eventLimit: true, // allow "more" link when too many events
			events: <?php echo json_encode($cal_array);?>
			
		});
	});
</script>
<?php 
	$session = $this->request->session();
	$pull = ($session->read("User.is_rtl") == "1") ? "pull-left" : "pull-right";	
?>
<section class="content">
	<div id="main-wrapper">		
		<div class="row"><!-- Start Row2 -->
			<div class="left_section col-md-8 col-sm-8">
				<div class="col-lg-3 col-md-3 col-xs-6 col-sm-6">
					<a href="<?php echo $this->request->base ."/GymMember/memberList";?>">
						<div class="panel info-box panel-white">
							<div class="panel-body member">
								<img src="<?php echo $this->request->base;?>/webroot/img/dashboard/member.png" class="dashboard_background">
								<div class="info-box-stats">
									<p class="counter"><?php echo $members;?> <span class="info-box-title"><?php echo __("Member");?></span></p>
								</div>
							</div>
						</div>
					</a>
				</div>
				<div class="col-lg-3 col-md-3 col-xs-6 col-sm-6">
					<a href="<?php echo $this->request->base ."/staff-members/staff-list";?>">
						<div class="panel info-box panel-white">
							<div class="panel-body staff-member">
								<img src="<?php echo $this->request->base;?>/webroot/img/dashboard/staff-member.png" class="dashboard_background">
								<div class="info-box-stats">
									<p class="counter"><?php echo $staff_members;?><span class="info-box-title"><?php echo __("Staff Member");?></span></p>
								</div>
							</div>
						</div>
					</a>
				</div>
			
				<div class="col-lg-3 col-md-3 col-xs-6 col-sm-6">
					<a href="<?php echo $this->request->base ."/gym-group/group-list";?>">
						<div class="panel info-box panel-white">
							<div class="panel-body group">
								<img src="<?php echo $this->request->base;?>/webroot/img/dashboard/group.png" class="dashboard_background">
									<div class="info-box-stats groups-label">
										<p class="counter"><?php echo $groups;?><span class="info-box-title"><?php echo __("Group");?></span></p>
									</div>
							</div>
						</div>
					</a>
				</div>
				<div class="col-lg-3 col-md-3 col-xs-6 col-sm-6">
					<a href="<?php echo $this->request->base ."/gym-message/inbox";?>">
						<div class="panel info-box panel-white">
							<div class="panel-body message no-padding">
								<img src="<?php echo $this->request->base;?>/webroot/img/dashboard/message.png" class="dashboard_background_message">
								<div class="info-box-stats">
									<p class="counter"><?php echo $messages;?><span class="info-box-title"><?php echo __("Message");?></span></p>
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-md-4 membership-list <?php echo $pull;?> col-sm-4 col-xs-12">
				<div class="panel panel-white">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo __("Membership");?></h3>						
					</div>
					<div class="panel-body">
						<?php 
						foreach($membership as $ms)
						{
							$m_img = (!empty($ms["gmgt_membershipimage"])) ? $ms["gmgt_membershipimage"] : "Thumbnail-img2.png";
							?>
							<p>
								<img src="<?php echo $this->request->base ."/webroot/upload/" .$m_img; ?>" height="40px" width="40px" class="img-circle">
								<?php echo $ms["membership_label"];?>
							</p>
						<?php
						} ?>
					</div>
				</div>
				<div class="panel panel-white">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo __("Group List");?></h3>						
					</div>
					<div class="panel-body">
						<?php 
						foreach($groups_data as $gd)
						{
							$image = ($gd['image'] == "") ? "Thumbnail-img.png" : $gd['image'];
						?>
							<p>
								<img src="<?php echo $this->request->base ."/webroot/upload/" .$image; ?>" height="40px" width="40px" class="img-circle">
								<?php echo $gd["name"];?>
							</p>
						<?php
						} ?>
					</div>
				</div>
			</div>
			<div class="col-md-8 col-sm-8 col-xs-12">
				<div class="panel panel-white">
					<div class="panel-body">
						<div id="calendar">
						</div>
					</div>
				</div>
			</div>	
			<!-- End row2 -->
			<div class="row inline"><!-- Start Row3 -->
				<div class="col-md-6 col-sm-6 col-xs-12">
				<?php
					$options = Array(
									'title' => __('Payment by month'),
									'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
									'legend' =>Array('position' => 'right',
												'textStyle'=> Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
									
									'hAxis' => Array(
										'title' => __('Month'),
										'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
										'textStyle' => Array('color' => '#66707e','fontSize' => 11),
										'maxAlternation' => 2
										
										),
									'vAxis' => Array(
										'title' => __('Payment'),
										 'minValue' => 0,
										'maxValue' => 5,
										 'format' => '#',
										'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
										'textStyle' => Array('color' => '#66707e','fontSize' => 12)
										),
								'colors' => array('#22BAA0')
									);			
						
						$GoogleCharts = new GoogleCharts;					
						$chart = $GoogleCharts->load( 'column' , 'chart_div1' )->get( $chart_array_pay , $options );
						?>
						<div class="panel panel-white">
							<div class="panel-heading">
								<h3 class="panel-title"><?php echo __('Payment');?></h3>						
							</div>
							<div class="panel-body">
								<div id="chart_div1" style="width: 100%; height: 500px;">
								<?php
								if(empty($result_pay))
									echo __('There is not enough data to generate report');?>
								</div>
								<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
								<script type="text/javascript">
								<?php 
								if(!empty($result_pay))
									echo $chart;?>
								</script>
							</div>
						</div>
				</div>			
				<div class="col-md-6 col-sm-6 col-xs-12">
				<?php
					$options = Array(
						'title' => __('Member Attendance Report'),
						'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
						'legend' =>Array('position' => 'right',
						'textStyle'=> Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
				
						'hAxis' => Array(
							'title' =>  __('Class'),
							'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
							'textStyle' => Array('color' => '#66707e','fontSize' => 10),
							'maxAlternation' => 2
						),
						'vAxis' => Array(
							'title' =>  __('No of Member'),
							'minValue' => 0,
							'maxValue' => 6,
							'format' => '#',
							'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
							'textStyle' => Array('color' => '#66707e','fontSize' => 12)
						),
						'colors' => array('#22BAA0','#f25656')
					);
				
					$GoogleCharts = new GoogleCharts;
					$chart = $GoogleCharts->load( 'column' , 'attendance_report' )->get( $chart_array_at , $options );
					?>
					<div class="panel panel-white">
						<div class="panel-heading">
							<h3 class="panel-title"><?php echo __('Member Attendance Report');?></h3>						
						</div>
						<div class="panel-body">
							<div id="attendance_report" style="width: 100%; height: 500px;">
								<?php
							
								if(empty($report_member))
									echo __('There is not enough data to generate report');?>
							</div>  
							  <!-- Javascript --> 
							  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
							  <script type="text/javascript">
								<?php
								if(!empty($report_member))
									echo $chart;?>
								</script>
						</div>
					</div>
				</div>
				<div class="clear"></div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<?php
					$options12 = Array(
						'title' => __('Staff Attendance Report'),
						'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
						'legend' =>Array('position' => 'right',
						'textStyle'=> Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
				
						'hAxis' => Array(
							'title' =>  __('Staff Member'),
							'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
							'textStyle' => Array('color' => '#66707e','fontSize' => 10),
							'maxAlternation' => 2
						),
						'vAxis' => Array(
							'title' =>  __('Number of Staff Members'),
							'minValue' => 0,
							'maxValue' => 6,
							'format' => '#',
							'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
							'textStyle' => Array('color' => '#66707e','fontSize' => 12)
						),
						'colors' => array('#22BAA0','#f25656')
					);
					$GoogleCharts = new GoogleCharts;
					$chart_staff = $GoogleCharts->load( 'column' , 'staff_att_report' )->get( $chart_array_staff , $options12 );
				
					?>
					<div class="panel panel-white">
						<div class="panel-heading">
							<h3 class="panel-title"><?php echo __('Staff Attendance');?></h3>						
						</div>
						<div class="panel-body">
							<div id="staff_att_report" style="width: 100%; height: 500px;">
							<?php
								if(empty($report_sataff))
									echo __('There is not enough data to generate report');?>
							</div>
									
							  <!-- Javascript --> 
							<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
							<script type="text/javascript">
										<?php 
										if(!empty($report_sataff))
										{
											echo $chart_staff;
										}?>
							</script>
						</div>
					</div>
				</div>
			</div><!-- End Row3 -->	
		</div>
	</div>
</section>