<script type="text/javascript">
$(document).ready(function() {	
	//$(".content-wrapper").css("min-height","1550px");
});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-key"></i>
				<?php echo __("Access Right Settings");?>
				<small><?php echo __("Access Right");?></small>
			  </h1>			  
			</section>
		</div>
		<hr>
		<div class="box-body">	
<form name="student_form" action="" method="post" class="form-horizontal" id="access_right_form">
		<div class="row">
		<div class="col-md-2 col-sm-3 col-xs-3 "><?php echo __("Menu");?></div>
		<div class="col-md-2 col-sm-3 col-xs-3 text-center"><?php echo __("Member");?></div>
		<div class="col-md-2 col-sm-3 col-xs-3 text-center"><?php echo __("Staff Member");?></div>
		<div class="col-md-2 col-sm-3 col-xs-3 text-center"><?php echo __("Accountant");?></div>
		
		</div>
		 <div class="access_right_menucroll">
		<div class="row">
		
			<div class="col-md-2 col-sm-3 col-xs-5 ">
				<span class="menu-label">
					<?php echo __("Staff Member"); ?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["staff_member"])?"checked":"";?> value="1" name="member_staff_member" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["staff_member"])?"checked":"";?> value="1" name="staff_member_staff_member" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($accountant["staff_member"])?"checked":"";?> value="1" name="accountant_staff_member" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
		<!--------Membership------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Membership Type");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["membership"])?"checked":"";?> value="1" name="member_membership" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["membership"])?"checked":"";?> value="1" name="staff_member_membership" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" value="1" <?php echo ($accountant["membership"])?"checked":"";?> name="accountant_membership" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
		<!--------Group------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Group");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["group"])?"checked":"";?> value="1" name="member_group" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["group"])?"checked":"";?> value="1" name="staff_member_group" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox"  <?php echo ($accountant["group"])?"checked":"";?> value="1" name="accountant_group" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
		<!--------Member------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Member");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["member"])?"checked":"";?> value="1" name="member_member" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["member"])?"checked":"";?> value="1" name="staff_member_member" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($accountant["member"])?"checked":"";?> value="1" name="accountant_member" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
		<!--------Activity------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Activity");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["activity"])?"checked":"";?> value="1" name="member_activity" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["activity"])?"checked":"";?> value="1" name="staff_member_activity" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($accountant["activity"])?"checked":"";?> value="1" name="accountant_activity" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
		<!--------Class Schedule------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Class Schedule");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["class-schedule"])?"checked":"";?> value="1" name="member_class-schedule" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["class-schedule"])?"checked":"";?> value="1" name="staff_member_class-schedule" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($accountant["class-schedule"])?"checked":"";?> value="1" name="accountant_class-schedule" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
		<!--------Class Booking------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Class Booking");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["class-booking"])?"checked":"";?> value="1" name="member_class-booking" readonly="true" disabled>	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["class-booking"])?"checked":"";?> value="1" name="staff_member_class-booking" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($accountant["class-booking"])?"checked":"";?> value="1" name="accountant_class-booking" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
		<!--------Attendence------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Attendence");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["attendance"])?"checked":"";?> value="1" name="member_attendence" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["attendance"])?"checked":"";?> value="1" name="staff_member_attendence" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($accountant["attendance"])?"checked":"";?> value="1" name="accountant_attendence" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
		<!--------Assigne Workouts------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Assigned Workouts");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["assign-workout"])?"checked":"";?> value="1" name="member_assign-workout" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["assign-workout"])?"checked":"";?> value="1" name="staff_member_assign-workout" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($accountant["assign-workout"])?"checked":"";?> value="1" name="accountant_assign-workout" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
		<!--------Workouts------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Workouts");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["workouts"])?"checked":"";?> value="1" name="member_workouts" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["workouts"])?"checked":"";?> value="1" name="staff_member_workouts" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($accountant["workouts"])?"checked":"";?> value="1" name="accountant_workouts" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
		<!--------Accountant------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Accountant");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["accountant"])?"checked":"";?> value="1" name="member_accountant" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["accountant"])?"checked":"";?> value="1" name="staff_member_accountant" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($accountant["accountant"])?"checked":"";?> value="1" name="accountant_accountant" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
		<!--------Payment------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Fee Payment");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["membership_payment"])?"checked":"";?> value="1" name="member_membership_payment" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" value="1" <?php echo ($staff_member["membership_payment"])?"checked":"";?> name="staff_member_membership_payment" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($accountant["membership_payment"])?"checked":"";?> value="1" name="accountant_membership_payment" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
		<!--------Income------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Income");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" disabled <?php echo ($member["income"])?"checked":"";?> value="1" name="member_income" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["income"])?"checked":"";?> value="1" name="staff_member_income" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($accountant["income"])?"checked":"";?> value="1" name="accountant_income" readonly="">	              
					</label>
				</div>
			</div>			
		</div>
		<!--------Expense------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Expense");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" disabled <?php echo ($member["expense"])?"checked":"";?> value="1" name="member_expense" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["expense"])?"checked":"";?> value="1" name="staff_member_expense" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($accountant["expense"])?"checked":"";?> value="1" name="accountant_expense" readonly="">	              
					</label>
				</div>
			</div>			
		</div>
		
		<!--------product------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Product");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" value="1" <?php echo ($member["product"])?"checked":"";?> name="member_product" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["product"])?"checked":"";?> value="1" name="staff_member_product" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($accountant["product"])?"checked":"";?> value="1" name="accountant_product" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
				<!--------Store------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Store");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2 ">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" value="1" <?php echo ($member["store"])?"checked":"";?> name="member_store" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["store"])?"checked":"";?> value="1" name="staff_member_store" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($accountant["store"])?"checked":"";?> value="1" name="accountant_store" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
				<!--------News letter------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Newsletter");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["news_letter"])?"checked":"";?> value="1" name="member_news_letter" readonly="" disabled>	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["news_letter"])?"checked":"";?> value="1" name="staff_member_news_letter" readonly="" disabled>	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($accountant["news_letter"])?"checked":"";?> value="1" name="accountant_news_letter" readonly="" disabled>	              
					</label>
				</div>
			</div>
			
		</div>
				<!--------Message------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Message");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["message"])?"checked":"";?> value="1" name="member_message" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["message"])?"checked":"";?> value="1" name="staff_member_message" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($accountant["message"])?"checked":"";?> value="1" name="accountant_message" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
				<!--------Notice------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Notice");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["notice"])?"checked":"";?> value="1" name="member_notice" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["notice"])?"checked":"";?> value="1" name="staff_member_notice" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($accountant["notice"])?"checked":"";?> value="1" name="accountant_notice" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
				<!--------Report------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Report");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["report"])?"checked":"";?> value="1" name="member_report" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["report"])?"checked":"";?> value="1" name="staff_member_report" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($accountant["report"])?"checked":"";?> value="1" name="accountant_report" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
		
				<!--------Nutrition Schedule------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Nutrition Schedule");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["nutrition"])?"checked":"";?> value="1" name="member_nutrition" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["nutrition"])?"checked":"";?> value="1" name="staff_member_nutrition" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" value="1" <?php echo ($accountant["nutrition"])?"checked":"";?> name="accountant_nutrition" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
				<!--------Event------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Event");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["reservation"])?"checked":"";?> value="1" name="member_reservation" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["reservation"])?"checked":"";?> value="1" name="staff_member_reservation" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" value="1" <?php echo ($accountant["reservation"])?"checked":"";?> name="accountant_reservation" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
				<!--------Account------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Account");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["account"])?"checked":"";?> value="1" name="member_account" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($staff_member["account"])?"checked":"";?> value="1" name="staff_member_account" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($accountant["account"])?"checked":"";?> value="1" name="accountant_account" readonly="">	              
					</label>
				</div>
			</div>
			
		</div>
		
		<!--------Subscription History------------->
		<div class="row">
			<div class="col-md-2 col-sm-3 col-xs-5">
				<span class="menu-label">
					<?php echo __("Subscription History");?>				</span>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" <?php echo ($member["subscription_history"])?"checked":"";?> value="1" name="member_subscription_history" readonly="">	              
					</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" value="1" <?php echo ($staff_member["subscription_history"])?"checked":"";?> name="staff_member_subscription_history" readonly="">	              
					</label>
				</div>
			</div>
		
			<div class="col-md-2 col-sm-3 col-xs-2">
				<div class="checkbox text-center">
					<label>
						<input type="checkbox" value="1" <?php echo ($accountant["subscription_history"])?"checked":"";?> name="accountant_subscription_history" readonly="">	              
					</label>
				</div>
			</div>
		</div>	
		</div>
		<br>
		
		<div class="col-sm-offset-2 col-sm-8 row_bottom">
        	
        	<input type="submit" value="<?php echo __("Save");?>" name="save_access_right" class="btn btn-flat btn-success">
        </div>
        
        
        </form>
		
		
		
		
	
	
	<!-- END -->
		</div>
		<div class='overlay gym-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>
