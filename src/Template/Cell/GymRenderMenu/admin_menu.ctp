<?php 
$session = $this->request->session();
$is_rtl = $session->read("User.is_rtl");
$role_name = $session->read('User.role_name');
$pull = ($is_rtl == "1") ? "pull-left":"pull-right";

if($is_rtl == "1") 
{ ?>
	<style>
		.treeview a {
			display: inline-block !important;
			width : 100% !important;
		}
		.treeview i:first-child{
			float:right !important;
			padding-top : 5px;
		}
		.treeview span{
			float: right !important;
			margin-right: 5px !important;
		}
	</style>
<?php } ?>
          <br>
		  
          <ul class="sidebar-menu">	 
            <li class= "treeview <?php echo ($this->request->controller == "Dashboard") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("Dashboard","index");?>">
                <i class="fa fa-pie-chart"></i> <span><?php echo __('Dashboard');?></span></i> 
              </a>             
            </li>
			<li class="treeview <?php echo ($this->request->controller == "Membership") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("membership","membership_list");?>">
                <i class="fa fa-users"></i> <span><?php echo __('Membership Type');?></span>  
              </a>			   
			</li>
			<li class="treeview <?php echo ($this->request->controller == "GymGroup") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("GymGroup","GroupList");?>">
                <i class="fa fa-object-group"></i> <span><?php echo __('Group');?></span> 
              </a>
			</li>			
			<li class="treeview <?php echo ($this->request->controller == "GymNutrition" || $this->request->controller == "ClassSchedule") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("GymNutrition","nutritionList");?>">
                <i class="fa fa-calendar"></i> <span><?php echo __("Class & Nutrition Schedule");?></span><i class="fa fa-angle-left <?php echo $pull;?>"></i>
              </a>
			  <ul class="treeview-menu">					
					<li class="<?php echo ($this->request->action == "classList" || $this->request->action == "viewSchedule" || $this->request->action == "editClass"|| $this->request->action == "addClass") ? "active" : "";?>">
						<a href="<?php echo $this->Gym->createurl("classSchedule","classList");?>">
							<i class="fa fa-circle-o"></i><span><?php echo __('Class Schedule');?></span></i>
						</a>
					</li>
					<li class="<?php echo ($this->request->action == "bookingList" || $this->request->action == "viewBooking" || $this->request->action == "editBooking"|| $this->request->action == "addBooking") ? "active" : "";?>">
						<a href="<?php echo $this->Gym->createurl("classBooking","bookingList");?>">
							<i class="fa fa-circle-o"></i><span><?php echo __('Class Booking');?></span></i>
						</a>
					</li>
					<li class="<?php echo ($this->request->action == "nutritionList" || $this->request->action == "addnutritionSchedule" || $this->request->action == "viewNutirion") ? "active" : "";?>">
						<a href="<?php echo $this->Gym->createurl("GymNutrition","nutritionList");?>"><i class="fa fa-circle-o"></i><?php echo __('Nutrition Schedule');?></a>
					</li>	
              </ul>	
			</li>
			<li class="treeview <?php echo ($this->request->controller == "GymMember" || $this->request->controller == "StaffMembers" || $this->request->controller == "GymAccountant") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("GymMember","memberList");?>">
                <i class="fa fa-user"></i> <span><?php echo __('Member Management');?></span></i><i class="fa fa-angle-left <?php echo $pull;?>"></i>
              </a>
			   <ul class="treeview-menu">
					<li class="<?php echo ($this->request->action == "memberList" || $this->request->action == "addMember" || $this->request->action == "editMember" || $this->request->action == "viewMember") ? "active" : "";?>">
						<a href="<?php echo $this->Gym->createurl("GymMember","memberList");?>"><i class="fa fa-circle-o"></i><?php echo __('Members');?></a>
					</li>
					<li class="<?php echo ($this->request->action == "staffList" || $this->request->action == "addStaff") ? "active" : "";?>">
						<a href="<?php echo $this->Gym->createurl("StaffMembers","StaffList");?>">
							<i class="fa fa-circle-o"></i><span><?php echo __('Staff Member');?></span></i>
						</a>
					</li>
					<li class="treeview <?php echo ($this->request->action == "accountantList" || $this->request->action == "addAccountant" || $this->request->action == "editAccountant") ? "active" : "";?>">
					  <a href="<?php echo $this->Gym->createurl("GymAccountant","accountantList");?>">
						<i class="fa fa-circle-o"></i> <span><?php echo __("Accountant");?></span>
					  </a>
					</li>
              </ul>			  
			</li>
			<li class="treeview <?php echo ($this->request->controller == "Activity") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("Activity","activityList");?>">
                <i class="fa fa-bicycle"></i> <span><?php echo __('Activity');?></span>  
              </a>
			</li>
			<li class="treeview <?php echo ($this->request->controller == "GymAssignWorkout" || $this->request->controller == "GymDailyWorkout") ? "active" : "";?>">
				<a href="<?php echo $this->Gym->createurl("GymAssignWorkout","WorkoutLog");?>">
					<i class="fa fa-hand-grab-o"></i> <span><?php echo __('Workout');?></span><i class="fa fa-angle-left <?php echo $pull;?>"></i>
				</a>
			   <ul class="treeview-menu">
					<li class="<?php echo ($this->request->action == "workoutLog" || $this->request->action == "assignWorkout" || $this->request->action == "viewWorkouts") ? "active" : "";?>">
						<a href="<?php echo $this->Gym->createurl("GymAssignWorkout","WorkoutLog");?>"><i class="fa fa-circle-o"></i><?php echo __('Assign Workout');?></a>
					</li>
					<li class="<?php echo ($this->request->action == "workoutList" || $this->request->action == "addWorkout" || $this->request->action =="addMeasurment" || $this->request->action =="viewWorkout" || $this->request->action =="editMeasurment") ? "active" : "";?>">
						<a href="<?php echo $this->Gym->createurl("GymDailyWorkout","workoutList");?>">
							<i class="fa fa-circle-o"></i><span><?php echo __('Daily Workout');?></span></i>
						</a>
					</li>				
              </ul>	
			</li>			
			<li class="treeview <?php echo ($this->request->controller == "GymProduct" || $this->request->controller == "GymStore") ? "active" : "";?>">
				<a href="<?php echo $this->Gym->createurl("GymProduct","productList");?>">
					<i class="fa fa-tags"></i> <span><?php echo __("Store & Products");?></span><i class="fa fa-angle-left <?php echo $pull;?>"></i>
				</a>
			  <ul class="treeview-menu">
					<li class="<?php echo ($this->request->action == "productList" || $this->request->action == "addProduct" || $this->request->action == "editProduct") ? "active" : "";?>">
						<a href="<?php echo $this->Gym->createurl("GymProduct","productList");?>"><i class="fa fa-circle-o"></i><?php echo __('Product');?></a>
					</li>
					<li class="<?php echo ($this->request->action == "sellRecord" || $this->request->action == "sellProduct" || $this->request->action == "editRecord") ? "active" : "";?>">
						<a href="<?php echo $this->Gym->createurl("GymStore","sellRecord");?>">
							<i class="fa fa-circle-o"></i><span><?php echo __('Store');?></span></i>
						</a>
					</li>				
              </ul>
			</li>			
			<li class="treeview <?php echo ($this->request->controller == "GymReservation") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("GymReservation","reservationList");?>">
                <i class="fa fa-ticket"></i> <span><?php echo __("Event");?></span>  
              </a>
			</li>
			<li class="treeview <?php echo ($this->request->controller == "GymAttendance") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("GymAttendance","attendance");?>">
                <i class="fa fa-braille"></i> <span><?php echo __("Attendance");?></span>  
              </a>
			</li>			
			<li class="treeview <?php echo ($this->request->controller == "MembershipPayment") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("MembershipPayment","paymentList");?>">
                <i class="fa fa-money"></i> <span><?php echo __("Payment");?></span><i class="fa fa-angle-left <?php echo $pull;?>"></i>
              </a>
				<ul class="treeview-menu">
					<li class="<?php echo ($this->request->action == "paymentList" || $this->request->action == "generatePaymentInvoice" || $this->request->action == "membershipEdit") ? "active" : "";?>">
						<a href="<?php echo $this->Gym->createurl("MembershipPayment","paymentList");?>">
						<i class="fa fa-circle-o"></i><span><?php echo __('Membership Payment');?></span>
						</a>
					</li>
					<li class="<?php echo ($this->request->action == "incomeList" || $this->request->action == "addIncome" || $this->request->action == "incomeEdit") ? "active" : "";?>">
						<a href="<?php echo $this->Gym->createurl("MembershipPayment","incomeList");?>">
							<i class="fa fa-circle-o"></i><span><?php echo __('Income');?></span>
						</a>
					</li>
					<li class="<?php echo ($this->request->action == "expenseList" || $this->request->action == "addExpense" || $this->request->action == "expenseEdit") ? "active" : "";?>">
						<a href="<?php echo $this->Gym->createurl("MembershipPayment","expenseList");?>">
							<i class="fa fa-circle-o"></i><span><?php echo __('Expenses');?></span>
						</a>
					</li>
				</ul> 
			  
			</li>
			<li class="treeview <?php echo ($this->request->controller == "GymMessage") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("GymMessage","composeMessage");?>">
                <i class="fa fa-commenting"></i> <span><?php echo __("Message");?></span>  
              </a>
			</li>
			<li class="treeview <?php echo ($this->request->controller == "GymNewsletter") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("GymNewsletter","setting");?>">
                <i class="fa fa-envelope-square"></i> <span><?php echo __("Newsletter");?></span>  
              </a>
			</li>
			<li class="treeview <?php echo ($this->request->controller == "GymNotice") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("GymNotice","noticeList");?>">
                <i class="fa fa-bell"></i> <span><?php echo __("Notice");?></span>  
              </a>
			</li>	
			<li class="treeview <?php echo ($this->request->controller == "Reports") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("Reports","membershipReport");?>">
                <i class="fa fa-file-text-o"></i> <span><?php echo __("Report");?></span> 
              </a>
			</li> 	
			<li class= "treeview <?php echo ($this->request->controller == "GymSubscriptionHistory") ? "active" : "";?>">
				<a href="<?php echo $this->Gym->createurl("GymSubscriptionHistory",""); ?>">
					<i class="fa fa-history"></i>
					<span><?php echo __("SubscriptionHistory");?></span>
				</a>             
			</li>
			<?php if($role_name == 'administrator')
			{?>
			<li class="treeview <?php echo ($this->request->controller == "GeneralSetting") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("GeneralSetting","SaveSetting");?>">
                <i class="fa fa-sliders"></i> <span><?php echo __("General Settings");?></span></i>
              </a>
			</li>
			<li class="treeview <?php echo ($this->request->controller == "GymAccessright") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("GymAccessright","accessRight");?>">
                <i class="fa fa-key"></i> <span><?php echo __("Access Right");?></span></i>
              </a>
			</li>
			<?php } ?>
          </ul>
      
      