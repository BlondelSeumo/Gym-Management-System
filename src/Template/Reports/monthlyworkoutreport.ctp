<style>
.content-header>.breadcrumb{
	position: relative;
}
</style>
<?php
$role_name = $this->request->session()->read("User.role_name");
?>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header box_payment">
			<section class="content-header bread_payment">
				<h1>
					<i class="fa fa-bar-chart"></i>
						<?php echo __("Members Monthly Workout Report");?>
						<small><?php echo __("Reports");?></small>
				</h1>
				<ol class="breadcrumb">
					<?php
					if($role_name == 'member')
					{
						?>
						<a href="<?php echo $this->Gym->createurl("Reports","monthlyworkoutreport");?>" class="btn btn-flat btn-custom"><i class="fa fa-bar-chart"></i> <?php echo __("Members Monthly Workout Report");?></a>
						<?php
					}
					else
					{ ?>
						<a href="<?php echo $this->Gym->createurl("Reports","membershipReport");?>" class="btn btn-flat btn-custom "><i class="fa fa-bar-chart"></i> <?php echo __("Membership Report");?></a>
						&nbsp;
						<a href="<?php echo $this->Gym->createurl("Reports","attendanceReport");?>" class="btn btn-flat btn-custom"><i class="fa fa-bar-chart"></i> <?php echo __("Attendance Report");?></a>
						&nbsp;
						<a href="<?php echo $this->Gym->createurl("Reports","membershipStatusReport");?>" class="btn btn-flat btn-custom"><i class="fa fa-pie-chart"></i> <?php echo __("Membership Status Report");?></a>
						&nbsp;
						<a href="<?php echo $this->Gym->createurl("Reports","paymentReport");?>" class="btn btn-flat btn-custom"><i class="fa fa-bar-chart"></i> <?php echo __("Payment Report");?></a>
						&nbsp;
						<a href="<?php echo $this->Gym->createurl("Reports","monthlyworkoutreport");?>" class="btn active btn-flat btn-custom"><i class="fa fa-bar-chart"></i> <?php echo __("Members Monthly Workout Report");?></a>
					<?php
					}
					?>
				</ol>
			</section>
		</div>
		<hr>
		<form method="post" class="validateForm">  
			<input type="hidden" name="member_id" value="0">
			<?php
			if($role_name == 'member')
			{
			}
			else
			{
			?>
			<div class="form-group col-md-3">
				<label for="member_id"><?php echo __("Select Member");?></label>	<?php echo $this->Form->select("member_id",$members,["empty"=>__("Select Member"),"class"=>"validation[required] form-control",'required'=>'true']);?>			
			</div>
			<?php
			}
			?>
			<div class="form-group col-md-3">
				<label class="control-label" for="curr_month"><?php echo __("Month");?></label>				
				<input id="curr_month" class="form-control validate[required]" autocomplete="off" type="text" value="<?php echo (isset($_POST['curr_month'])) ? $_POST["curr_month"]:"";?>" name="curr_month">
			</div>
			<div class="form-group col-md-3 button-possition">
				<label for="subject_id">&nbsp;</label>
				<input type="submit" value="<?php echo __("GO");?>" name="monthlyreport" class="btn btn-flat btn-success">
			</div>
		
			<div class="clearfix"> </div>
			<?php
			if($post)
			{
				if(isset($data))
				{
				?>
				<div class="box-body">
					<table class="mydataTable table table-striped">
						<thead>
							<tr>
								<th><?php echo __("Workout Date");?></th>
								<th><?php echo __("Workout Name");?></th>
								<th><?php echo __("Sets");?></th>
								<th><?php echo __("Reps");?></th>
								<th><?php echo __("Kg");?></th>
								<th><?php echo __("Rest Time");?></th>
							</tr>
						</thead>
						<tbody>
							<?php			
							$firstname = $this->Gym->get_user_data($member_id,'first_name');
							$middlename = $this->Gym->get_user_data($member_id,'middle_name');
							$lastname = $this->Gym->get_user_data($member_id,'last_name');
							$rows=array();
							$rows[] = array("Member Name",$firstname." ".$middlename." ".$lastname);
							$rows[] = array();	
							$rows[]=array("No","Workout Date","Workout Name","Sets","Reps","Kg","Rest Time");
							$i = 1;
							foreach($data as $drow) {						
								$row = array();
								$row[] = $i;
							?>
								<tr>
									<td><?php echo $row[] = $this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($drow['record_date'])));?></td>
									<td><?php echo $row[] = $this->Gym->get_activity_by_id($drow['workout_name']);?></td>
									<td><?php echo $row[] = $drow['sets'];?></td>
									<td><?php echo $row[] = $drow['reps'];?></td>
									<td><?php echo $row[] = $drow['kg'];?></td>
									<td><?php echo $row[] = $drow['rest_time'];?></td>
								</tr>
							<?php
							$i++;
							$rows[] = $row;
							}
							?>
						</tbody>
					</table>
				<!-- END -->
				</div>
				<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
				<div class="form-group col-md-3 button-possition">
					<label for="subject_id">&nbsp;</label>
					<input type="submit" value="<?php echo __("Download");?>" name="export_csv" class="btn btn-flat btn-primary">
				</div>
		
				<?php
				}
				else
				{	
					echo "<i>".__('No Workout Found.')."</i>";
				}
			}?>
		</form>
	</div>
</section>	
	
<link href="<?php echo $this->request->base;?>/css/bootstrap-datepicker.css" rel="stylesheet">
<script src="<?php echo $this->request->base;?>/js/bootstrap-datepicker.js"></script>
<script>
$(function(){		
$('#curr_month').datepicker({
	minViewMode: 1,
	format: 'MM yyyy',
});
});
</script>		