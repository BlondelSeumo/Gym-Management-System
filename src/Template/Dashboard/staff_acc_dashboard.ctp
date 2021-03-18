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
	$session_user = $this->request->session()->read("User");
?>
<section class="content">
<div id="main-wrapper">		
		<div class="row"><!-- Start Row2 -->
		<div class="left_section col-md-8 col-sm-8">
			<?php
			if($session_user["role_name"] == "staff_member")
				$access = $this->Gym->get_staff_member_accessright('member');
			else
				$access = $this->Gym->get_accountant_accessright('member');
			
			if($access == 1)
			{
			?>
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
			<?php
			}
			
			if($session_user["role_name"] == "staff_member")
				$access = $this->Gym->get_staff_member_accessright('staff_member');
			else
				$access = $this->Gym->get_accountant_accessright('staff_member');
			
			if($access == 1)
			{
			?>
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
			<?php
			}
			
			if($session_user["role_name"] == "staff_member")
				$access = $this->Gym->get_staff_member_accessright('group');
			else
				$access = $this->Gym->get_accountant_accessright('group');
			
			if($access == 1)
			{
			?>
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
			<?php
			}
			
			if($session_user["role_name"] == "staff_member")
				$access = $this->Gym->get_staff_member_accessright('message');
			else
				$access = $this->Gym->get_accountant_accessright('message');
			
			if($access == 1)
			{
			?>
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
			<?php
			}
			?>
			</div>
			<div class="col-md-4 membership-list <?php echo $pull;?> col-sm-4 col-xs-12">
				<?php
				if($session_user["role_name"] == "staff_member")
					$access = $this->Gym->get_staff_member_accessright('membership');
				else
					$access = $this->Gym->get_accountant_accessright('membership');
				
				if($access == 1)
				{
				?>
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
				<?php
				}
				
				if($session_user["role_name"] == "staff_member")
					$access = $this->Gym->get_staff_member_accessright('group');
				else
					$access = $this->Gym->get_accountant_accessright('group');
				
				if($access == 1)
				{
				?>
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
								<img src="<?php echo $this->request->base ."/webroot/upload/".$image; ?>" height="40px" width="40px" class="img-circle">
								<?php echo $gd["name"];?>
							</p>
						<?php
						} ?>
					</div>
				</div>
				<?php
				}
				?>
		   </div>
			<div class="col-md-8 col-sm-8 col-xs-12">
				<div class="panel panel-white">
					<div class="panel-body">
						<div id="calendar">
						</div>
				</div>
			</div>
			
		</div>	<!-- End row2 -->
					
	</div>
 </div>
</section>