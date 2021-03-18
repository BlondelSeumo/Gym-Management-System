
<script>
$(".content-wrapper").css("min-height","2500px");
$(document).ready(function(){	
$(".sub-history").dataTable({
		"responsive": true,
		"order": [[ 1, "asc" ]],
		"aoColumns":[	                  
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true}],
	"language" : {<?php echo $this->Gym->data_table_lang();?>}	
	});
	
	var box_height = $(".box").height();
	var box_height = box_height + 100 ;
	/* $(".content").css("height",box_height+"px"); */
});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-eye"></i> 
				<?php echo __("View Member");?>
				<small><?php echo __("Member");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymMember","memberList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Members List");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<div class="row">
			<div class="col-md-7 col-sm-12  col-xs-12 no-padding border">
			<div class="col-md-5 col-sm-4 col-xs-12 no-padding text-center" style="margin-top: 20px;">
				<?php
					
					$logo = $data['image'];
					$logo = (!empty($logo)) ? "/webroot/upload/". $logo : "Thumbnail-img2.png";
					echo $this->Html->image($logo,["style"=>"height:140px;width:180px"]);
				
				$date = $data['birth_date'];
				$timestamp = $date->getTimestamp();
				$date->setTimestamp($timestamp);
				$birthday = $this->Gym->get_db_format($date->format($this->Gym->getSettings("date_format")));
				
				?>
				<div style="width: 80%;margin: 10px 0px 0px 20px;">
				<?php 	
				
					$parameter = array('id'=>$data['id'],'email'=>$data['email']);
					$qrcode =  $this->Qr->contact($parameter);
				?>
					<img src="<?php echo $qrcode; ?>" style="max-width:100%">
				</div>
			</div>
			
			<div class="col-md-7 col-sm-7 col-xs-12 pull-right">
				<br>
				<table class="table tbl-content">
					<tr>
						<th><i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;<?php echo __("Member ID");?></th>
						<td class="txt_color"><?php echo $data['member_id'];?></td>
					</tr>
					<tr>
						<th><i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;<?php echo __("Name");?></th>
						<td class="txt_color"><?php echo $data['first_name'];?></td>
					</tr>
					<tr>
						<th><i class="fa fa-map-marker"></i>&nbsp;&nbsp;&nbsp;<?php echo __("Address");?></th>
						<td class="txt_color"><?php echo $data['address'];?></td>
					</tr>
					<tr>
						<th><i class="fa fa-envelope"></i>&nbsp;&nbsp;&nbsp;<?php echo __("Email");?></th>
						<td class="txt_color emailid"><?php echo $data['email'];?></td>
					</tr>
					<tr>
						<th><i class="fa fa-phone"></i>&nbsp;&nbsp;&nbsp;<?php echo __("Mobile No");?></th>
						<td class="txt_color"><?php echo $data['mobile'];?></td>
					</tr>
					<tr>
						<th><i class="fa fa-calendar"></i>&nbsp;&nbsp;&nbsp;<?php echo __("Date Of Birth");?></th>
						<td class="txt_color"><?php echo $birthday;?></td>
					</tr>
					<tr>
						<th><i class="fa fa-mars"></i>&nbsp;&nbsp;&nbsp;<?php echo __("Gender");?></th>
						<td class="txt_color"><?php echo $data['gender'];?></td>
					</tr>									
					<tr>
						<th><i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;<?php echo __("Username");?></th>
						<td class="txt_color"><?php echo $data['username'];?></td>
					</tr>
				</table>
			</div>
			</div>
			<div class="col-md-1 space_member" style="padding-right: ">
			</div>
			<div class="col-md-4 no-padding border">	
			
					<table class="table table-margin">
					<tr>
						<th><i class="fa fa-users"></i>&nbsp;&nbsp;&nbsp;<?php echo __("MemberShip");?></th>
						<td class="txt_color"><?php echo $data['membership']['membership_label'];?></td>
					</tr>
					<tr>
						<th><i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo __("Expiry Date");?></th>
						<td class="txt_color"><?php echo $this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($data['membership_valid_to'])));?></td>
					</tr>
					<tr>
						<th><i class="fa fa-graduation-cap"></i>&nbsp;&nbsp;<?php echo __("Classes");?></th>
						<td class="txt_color"><?php echo $this->Gym->get_class_by_member($data["id"]);
						//echo $data['id'];?></td>
					</tr>
					<tr>
						<th><i class="fa fa-heart"></i>&nbsp;&nbsp;&nbsp;<?php echo __("Interest Area");?></th>
						<td class="txt_color"><?php echo $data['gym_interest_area']['interest'];?></td>
					</tr>	
					<tr>
						<th><i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;<?php echo __("Staff Member");?></th>
						<td class="txt_color"><?php echo $this->Gym->get_staff_name($data['assign_staff_mem']);?></td>
					</tr>
									
					<tr>
						<th><i class="fa fa-power-off"></i>&nbsp;&nbsp;&nbsp;<?php echo __("Status");?></th>
						<td class="txt_color"><?php echo $data['membership_status'];?></td>
					</tr>
					
					<tr>
						<th><i class="fa fa-map-marker"></i>&nbsp;&nbsp;&nbsp;<?php echo __("Groups");?></th>
						<td class="txt_color"><?php echo $this->Gym->get_group_by_member($data["id"]);?></td>
					</tr>					
				</table>
			</div>
		</div>
		<hr>
		<div class="row view_detail view_member_detail">
			<div class="col-md-6 col-sm-6 col-xs-12 border">
			<span class="report_title">
				<span class="fa-stack">
					<i class="fa fa-line-chart fa-stack-1x"></i>
				</span> 
				<span class="shiptitle"><?php echo __("Weight Report");?></span>

				<?php $role = $this->request->session()->read('User.role_name');?>
				<?php if($role != 'accountant'){ ?>	
				<a href="<?php echo $this->request->base ."/GymDailyWorkout/add_measurment/{$data['id']}/Weight";?>" class="btn btn-flat btn-danger right"> <?php echo __("Add Weight");?></a>
			<?php } ?>
			</span>			
			<div id="weight_report" style="width: 100%; height: 250px;float:left;">
				<?php 
				$GoogleCharts = new GoogleCharts;
				$weight_chart = $GoogleCharts->load( 'LineChart' , 'weight_report' )->get( $weight_data["data"] , $weight_data["option"] );
			
				if(empty($weight_data["data"]) || count($weight_data["data"]) == 1)
				echo __('There is not enough data to generate report'); ?>
			</div>  
			<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
			<script type="text/javascript">
				<?php 
				if(!empty($weight_data["data"]) && count($weight_data["data"]) > 1)
				echo $weight_chart;?>
			</script>
			</div>			
			
			<div class="col-md-6 col-sm-6 col-xs-12 border view_report_top">
			<span class="report_title">
				<span class="fa-stack cutomcircle">
					<i class="fa fa-line-chart fa-stack-1x"></i>
				</span> 
				<span class="shiptitle"><?php echo __("Waist Report");?></span>

				<?php $role = $this->request->session()->read('User.role_name');?>
				<?php if($role != 'accountant'){ ?>		
				<a href="<?php echo $this->request->base ."/GymDailyWorkout/add_measurment/{$data['id']}/Waist";?>" class="btn btn-flat btn-danger right"> <?php echo __("Add Waist"); ?></a>
				<?php } ?>	
			</span>
			<div id="waist_report" style="width: 100%; height: 250px;float:left;">
				<?php 
				$GoogleCharts = new GoogleCharts;
				$waist_chart = $GoogleCharts->load( 'LineChart' , 'waist_report' )->get( $waist_data["data"] , $waist_data["option"] );
			
				if(empty($waist_data["data"]) || count($waist_data["data"]) == 1)
				echo __('There is not enough data to generate report'); ?>
			</div>  
			<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
			<script type="text/javascript">
				<?php 
				if(!empty($waist_data["data"]) && count($waist_data["data"]) > 1)
				echo $waist_chart;?>
			</script>
			</div>
		</div>	
		<br><br>
		<div class="row view_detail">
			<div class="col-md-6 col-sm-6 col-xs-12 border">
			<span class="report_title">
				<span class="fa-stack cutomcircle">
					<i class="fa fa-line-chart fa-stack-1x"></i>
				</span> 
				<span class="shiptitle"><?php echo __("Thigh Report");?></span>	

				<?php $role = $this->request->session()->read('User.role_name');?>
				<?php if($role != 'accountant'){ ?>	
				<a href="<?php echo $this->request->base ."/GymDailyWorkout/add_measurment/{$data['id']}/Thigh";?>" class="btn btn-flat btn-danger right"><?php echo __("Add Thigh"); ?></a>
				<?php } ?>	
			</span>			
			<div id="thing_report" style="width: 100%; height: 250px;float:left;">
				<?php 
				$GoogleCharts = new GoogleCharts;
				$thing_chart = $GoogleCharts->load( 'LineChart' , 'thing_report' )->get( $thigh_data["data"] , $thigh_data["option"] );
			
				if(empty($thigh_data["data"]) || count($thigh_data["data"]) == 1)
				echo __('There is not enough data to generate report'); ?>
			</div>  
			<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
			<script type="text/javascript">
				<?php 
				if(!empty($thigh_data["data"]) && count($thigh_data["data"]) > 1)
				echo $thing_chart;?>
			</script>
			</div>			
			
			<div class="col-md-6 col-sm-6 col-xs-12 border view_report_top">
			<span class="report_title">
				<span class="fa-stack cutomcircle">
					<i class="fa fa-line-chart fa-stack-1x"></i>
				</span> 
				<span class="shiptitle"><?php echo __("Arms Report");?></span>	

				<?php $role = $this->request->session()->read('User.role_name');?>
				<?php if($role != 'accountant'){ ?>	
				<a href="<?php echo $this->request->base ."/GymDailyWorkout/add_measurment/{$data['id']}/Arms";?>" class="btn btn-flat btn-danger right"><?php echo __("Add Arms")?></a>
				<?php } ?>	
			</span>
			<div id="arms_report" style="width: 100%; height: 250px;float:left;">
				<?php 
				$GoogleCharts = new GoogleCharts;
				$arms_chart = $GoogleCharts->load( 'LineChart' , 'arms_report' )->get( $arms_data["data"] , $arms_data["option"] );
			
				if(empty($arms_data["data"]) || count($arms_data["data"]) == 1)
				echo __('There is not enough data to generate report'); ?>
			</div>  
			<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
			<script type="text/javascript">
				<?php 
				if(!empty($arms_data["data"]) && count($arms_data["data"]) > 1)
				echo $arms_chart;?>
			</script>
			</div>
		</div>		
		<br><br>
		<div class="row view_detail">
			<div class="col-md-6 col-sm-6 col-xs-12 border">
			<span class="report_title">
				<span class="fa-stack cutomcircle">
					<i class="fa fa-line-chart fa-stack-1x"></i>
				</span> 
				<span class="shiptitle"><?php echo __("Height Report");?></span>	

				<?php $role = $this->request->session()->read('User.role_name');?>
				<?php if($role != 'accountant'){ ?>	
				<a href="<?php echo $this->request->base ."/GymDailyWorkout/add_measurment/{$data['id']}/Height";?>" class="btn btn-flat btn-danger right"><?php echo __("Add Height"); ?></a>	

			<?php } ?>
			</span>			
			<div id="height_report" style="width: 100%; height: 250px;float:left;">
				<?php 
				$GoogleCharts = new GoogleCharts;
				$height_chart = $GoogleCharts->load( 'LineChart' , 'height_report' )->get( $height_data["data"] , $height_data["option"] );
			
				if(empty($height_data["data"]) || count($height_data["data"]) == 1)
				echo __('There is not enough data to generate report'); ?>
			</div>  
			<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
			<script type="text/javascript">
				<?php 
				if(!empty($height_data["data"]) && count($height_data["data"]) > 1)
				echo $height_chart;?>
			</script>
			</div>			
			
			<div class="col-md-6 col-sm-6 col-xs-12 border view_report_top">
			<span class="report_title">
				<span class="fa-stack cutomcircle">
					<i class="fa fa-line-chart fa-stack-1x"></i>
				</span> 
				<span class="shiptitle"><?php echo __("Chest Report");?></span>	

				<?php $role = $this->request->session()->read('User.role_name');?>
				<?php if($role != 'accountant'){ ?>	
				<a href="<?php echo $this->request->base ."/GymDailyWorkout/add_measurment/{$data['id']}/Chest";?>" class="btn btn-flat btn-danger right"><?php echo __("Add Chest")?></a>	
			<?php } ?>
			</span>
			<div id="chest_report" style="width: 100%; height: 250px;float:left;">
				<?php 
				$GoogleCharts = new GoogleCharts;
				$chest_chart = $GoogleCharts->load( 'LineChart' , 'chest_report' )->get( $chest_data["data"] , $chest_data["option"] );
			
				if(empty($chest_data["data"]) || count($chest_data["data"]) == 1)
				echo __('There is not enough data to generate report'); ?>
			</div>  
			<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
			<script type="text/javascript">
				<?php 
				if(!empty($chest_data["data"]) && count($chest_data["data"]) > 1)
				echo $chest_chart;?>
			</script>
			</div>
		</div>		
		<br><br>
		<div class="row view_detail">
			<div class="col-md-6 col-sm-6 col-xs-12 border">
			<span class="report_title">
				<span class="fa-stack cutomcircle">
					<i class="fa fa-line-chart fa-stack-1x"></i>
				</span> 
				<span class="shiptitle"><?php echo __("Fat Report");?></span>

				<?php $role = $this->request->session()->read('User.role_name');?>
				<?php if($role != 'accountant'){ ?>		
				<a href="<?php echo $this->request->base ."/GymDailyWorkout/add_measurment/{$data['id']}/Fat";?>" class="btn btn-flat btn-danger right"><?php echo __("Add Fat"); ?></a>
			<?php } ?>
			
			</span>			
			<div id="fat_report" style="width: 100%; height: 250px;float:left;">
				<?php 
				$GoogleCharts = new GoogleCharts;
				$fat_chart = $GoogleCharts->load( 'LineChart' , 'fat_report' )->get( $fat_data["data"] , $fat_data["option"] );
			
				if(empty($fat_data["data"]) || count($fat_data["data"]) == 1)
				echo __('There is not enough data to generate report'); ?>
			</div>  
			<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
			<script type="text/javascript">
				<?php 
				if(!empty($fat_data["data"]) && count($fat_data["data"]) > 1)
				echo $fat_chart;?>
			</script>
			</div>				
	
			<div class="col-md-6 col-sm-6 col-xs-12 border view_report_top">
			<span class="report_title">
				<span class="fa-stack cutomcircle">
					<i class="fa fa-line-chart fa-stack-1x"></i>
				</span> 
				<span class="shiptitle"><?php echo __("Photo");?></span>

				<?php $role = $this->request->session()->read('User.role_name');?>
				<?php if($role != 'accountant'){ ?>		
				<a href="<?php echo $this->request->base ."/GymDailyWorkout/add_measurment/{$data['id']}/Fat";?>" class="btn btn-flat btn-danger right"><?php echo __("Add Photo"); ?></a>	
			<?php } ?>
			</span>	
			<div id="fat_report" style="width: 100%; height: 250px;float:left;">
			<div id="myCarousel" class="carousel slide" data-ride="carousel">
			  <!-- Indicators -->
			  <ol class="carousel-indicators">
				<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
				<li data-target="#myCarousel" data-slide-to="1"></li>
				<li data-target="#myCarousel" data-slide-to="2"></li>
				<li data-target="#myCarousel" data-slide-to="3"></li>
			  </ol>
			   <!-- Wrapper for slides -->
			  <div class="carousel-inner" role="listbox">
				<?php 
					if(!empty($photos))
					{  
						$active = "active";
						foreach($photos as $photo)
						{?>
							<div class="item carousel-margin <?php echo $active;?>">
							  <img src="<?php echo $this->request->base;?>/webroot/upload/<?php echo $photo["image"];?>" alt="image">
							</div>	
					<?php $active = null;
						}
					}
				?>	
				
			 </div>
					
			  <!-- Left and right controls -->
			  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			  </a>
			  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			  </a>
			</div>
			</div>
			</div>					
		</div>
		
		<br>
		<div class="col-md-12  col-sm-12  col-xs-12" style="border:1px solid #dedede;">
		<span class="report_title">
			<span class="fa-stack cutomcircle">
				<i class="fa fa-align-left fa-stack-1x"></i>
			</span> 
			<span class="shiptitle"><?php echo __("Subscription History");?></span>	
		</span>
		<table class="table table-striped sub-history" width="100%">
			<thead>				
				<tr>
					<th><?php echo __("Membership Title");?></th>
					<th><?php echo __("Amount");?></th>
					<th><?php echo __("Due Amount");?></th>
					<th><?php echo __("Membership Start Date");?></th>
					<th><?php echo __("Membership End Date");?></th>
					<th><?php echo __("Payment Status");?></th>
				</tr>
			</thead>
			<tbody>
			<?php 
			if(!empty($history))
			{
				foreach($history as $row)
				{
					echo "<tr>
							<td>{$row["membership"]["membership_label"]}</td>
							<td>". $this->Gym->get_currency_symbol() ." {$row["membership_amount"]}</td>
							<td>". $this->Gym->get_currency_symbol() ." ". ($row["membership_amount"] - $row["paid_amount"]) ."</td>
							<td>".(($row['start_date'] != '')?$this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($row["start_date"]))):'Null')."</td>
							<td>".(($row['end_date'] != '')?$this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($row["end_date"]))):'Null')."</td>
							<td><span class='bg-primary pay_status'>".$this->Gym->get_membership_paymentstatus($row["mp_id"])."</span></td>
						</tr>";
				}
			}
			?>
			</tbody>
			<tfoot>
				<tr>
					<th><?php echo __("Membership Title");?></th>
					<th><?php echo __("Amount");?></th>
					<th><?php echo __("Due Amount");?></th>
					<th><?php echo __("Membership Start Date");?></th>
					<th><?php echo __("Membership End Date");?></th>
					<th><?php echo __("Payment Status");?></th>
				</tr>
			</tfoot>
		</table>
	</div>
		
		
		</div>
	</div>
</section>