<?php $session = $this->request->session(); ?>

		  <div class="user-panel">
            <div class="pull-left image">
			  <?php 
			  $user_img = $session->read("User.profile_img");
			  echo $this->Html->image("../webroot/upload/{$user_img}",array("class"=>"img-circle","alt"=>"User Image")); ?>
			</div>
            <div class="pull-left info">
              <p><?php echo $session->read("User.display_name");?></p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
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
                <i class="fa fa-calendar"></i> <span><?php echo __("Class & Nutrition Schedule");?></span><i class="fa fa-angle-left pull-right"></i>
              </a>
			  <ul class="treeview-menu">					
					<li class="<?php echo ($this->request->action == "classList" || $this->request->action == "viewSchedule" || $this->request->action == "editClass"|| $this->request->action == "addClass") ? "active" : "";?>">
						<a href="<?php echo $this->Gym->createurl("classSchedule","classList");?>">
							<i class="fa fa-circle-o"></i><span><?php echo __('Class Schedule');?></span></i>
						</a>
					</li>
					<li class="<?php echo ($this->request->action == "nutritionList" || $this->request->action == "addnutritionSchedule" || $this->request->action == "viewNutirion") ? "active" : "";?>">
						<a href="<?php echo $this->Gym->createurl("GymNutrition","nutritionList");?>"><i class="fa fa-circle-o"></i><?php echo __('Nutrition Schedule');?></a>
					</li>	
              </ul>	
			</li>
			<li class="treeview <?php echo ($this->request->controller == "GymMember" || $this->request->controller == "StaffMembers" || $this->request->controller == "GymAccountant") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("GymMember","memberList");?>">
                <i class="fa fa-user"></i> <span><?php echo __('Member Management');?></span></i><i class="fa fa-angle-left pull-right"></i>
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
                <i class="fa fa-hand-grab-o"></i> <span><?php echo __('Workout');?></span><i class="fa fa-angle-left pull-right"></i>
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
                <i class="fa fa-tags"></i> <span><?php echo __("Store & Products");?></span><i class="fa fa-angle-left pull-right"></i>
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
                <i class="fa fa-ticket"></i> <span><?php echo __("Attendance");?></span>  
              </a>
			</li>			
			<li class="treeview <?php echo ($this->request->controller == "MembershipPayment") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("MembershipPayment","paymentList");?>">
                <i class="fa fa-money"></i> <span><?php echo __("Payment");?></span><i class="fa fa-angle-left pull-right"></i>
              </a>
				<ul class="treeview-menu">
					<li class="<?php echo ($this->request->action == "paymentList" || $this->request->action == "generatePaymentInvoice" || $this->request->action == "membershipEdit") ? "active" : "";?>">
						<a href="<?php echo $this->Gym->createurl("MembershipPayment","paymentList");?>">
						<i class="fa fa-circle-o"></i> <span><?php echo __('Membership Payment');?></span></i>
						</a>
					</li>
					<li class="<?php echo ($this->request->action == "incomeList" || $this->request->action == "addIncome" || $this->request->action == "incomeEdit") ? "active" : "";?>">
						<a href="<?php echo $this->Gym->createurl("MembershipPayment","incomeList");?>">
							<i class="fa fa-circle-o"></i><?php echo __('Income');?>
						</a>
					</li>
					<li class="<?php echo ($this->request->action == "expenseList" || $this->request->action == "addExpense" || $this->request->action == "expenseEdit") ? "active" : "";?>">
						<a href="<?php echo $this->Gym->createurl("MembershipPayment","expenseList");?>">
							<i class="fa fa-circle-o"></i><?php echo __('Expenses');?>
						</a>
					</li>
				</ul> 
			  
			</li>
			<li class="treeview <?php echo ($this->request->controller == "GymMessage") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("GymMessage","composeMessage");?>">
                <i class="fa fa-money"></i> <span><?php echo __("Message");?></span>  
              </a>
			</li>
			<li class="treeview <?php echo ($this->request->controller == "GymNewsletter") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("GymNewsletter","setting");?>">
                <i class="fa fa-money"></i> <span><?php echo __("Newsletter");?></span>  
              </a>
			</li>
			<li class="treeview <?php echo ($this->request->controller == "GymNotice") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("GymNotice","noticeList");?>">
                <i class="fa fa-money"></i> <span><?php echo __("Notice");?></span>  
              </a>
			</li>	
			<li class="treeview <?php echo ($this->request->controller == "Reports") ? "active" : "";?>">
				<a href="<?php 
				if($session["role_name"] == "member")
				{
					echo $this->Gym->createurl("Reports","monthlyworkoutreport");
				}
				else
				{
					echo $this->Gym->createurl("Reports","membershipReport");
				}
				?>">
                <i class="fa fa-money"></i> <span><?php echo __("Report");?></span> 
              </a>
			</li>
			<li class="treeview <?php echo ($this->request->controller == "GeneralSetting") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("GeneralSetting","SaveSetting");?>">
                <i class="fa fa-cog"></i> <span><?php echo __("General Settings");?></span></i>
              </a>
			</li>
			<li class="treeview <?php echo ($this->request->controller == "GymAccessright") ? "active" : "";?>">
              <a href="<?php echo $this->Gym->createurl("GymAccessright","accessRight");?>">
                <i class="fa fa-cog"></i> <span><?php echo __("Access Right");?></span></i>
              </a>
			</li>
          </ul>
      
      