<script>
$(document).ready(function(){
	$(".date").datepicker( "option", "dateFormat", "<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>", "setDate", new Date("<?php echo $this->Gym->get_db_format(date($this->Gym->getSettings("date_format")));?>");
	<?php
	if(isset($_POST['curr_date']))
	{?>
	$( ".date" ).datepicker( "setDate","<?php echo $this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($attendance_date))); ?>" );
	<?php } ?>
});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-plus"></i>
				<?php echo __("Staff Attendance");?>
				<small><?php echo __("Attendance");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymAttendance","Attendance");?>" class="btn btn-flat btn-custom"><i class="fa fa-plus"></i> <?php echo __("Member Attendance");?></a>
			 </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">	
		<form method="post" class="validateForm">  
			  <input type="hidden" name="class_id" value="0">
			  <div class="form-group col-md-3">
				<label class="control-label" for="curr_date"><?php echo __("Date");?></label>				
					<input id="curr_date" class="form-control validate[required] date" type="text" value="<?php echo (isset($_POST['curr_date'])) ? $this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($attendance_date))): $this->Gym->get_db_format(date($this->Gym->getSettings("date_format")))?>" name="curr_date">
			 </div>		
			<div class="form-group col-md-3 button-possition saff_button-possition">
				<label for="subject_id"></label>
				<input type="submit" value="<?php echo __("Take/View Attendance");?>" name="staff_attendence" class="btn btn-flat btn-success">
			</div>       
        </form>
		<div class="clearfix"> </div>
		<hr>
		<!-- ###################################################################### -->	
	<?php
	if($view_attendance)
	{
	?>
		<div class="clearfix"> </div>
		<div class="panel-body panel-staff">  
            <form method="post" class="form-horizontal">           
          <input type="hidden" name="attendance_date" value="<?php echo $attendance_date;?>">
         <div class="panel-heading" style="padding-bottom: 25px; ">
         	<h4 class="panel-title">		 
         	<?php echo __("Staff Attendance, Date");?> : <?php echo (isset($_POST['curr_date'])) ? $this->Gym->get_db_format(date($this->Gym->getSettings("date_format"),strtotime($attendance_date))):"";?></h4>
         </div>
		 <br><br>
			<div class="clearfix"> </div>
          <div class="form-group">
			  <label class="radio-inline saff_inline_radio">
			  <input type="radio" name="status" value="Present" checked="checked"><?php echo __("Present"); ?></label>
			  <label class="radio-inline">
			  <input type="radio" name="status" value="Absent"><?php echo __("Absent"); ?><br>
			  </label>
          </div>
        <div class="col-md-12">
        <table class="table">
			<thead>
           <tr>
				<th width="70px"><?php echo __("Status");?></th>
				<th><?php echo __("Photo");?></th>
				<th width="250px"><?php echo __("Member Name");?></th>				
				<th><?php echo __("Status");?></th>			
			</tr>
			</thead>
			<tbody>
			<?php foreach($data as $row)
			{ 
				?>
            <tr> 
                <td class="checkbox_field"><span><input type="checkbox" class="checkbox1" name="attendance[]" value="<?php echo $row["id"];?>"></span></td>
                <td><img src="<?php echo $this->request->base ."/webroot/upload/".$row['image']; ?>" class='membership-img img-circle'></td>
				<td><span><?php echo $row['first_name']." ".$row['last_name']; ?></span></td>
				<td><?php echo  $this->Gym->get_attendance_status_staff($row["id"],$_POST["curr_date"]);?></td>
			</tr>
	  <?php } ?>
			</tbody>
		</table>
          </div>
		<div class="col-sm-12"> 
					       	
        	<input type="submit" value="<?php echo __("Save Attendance");?>" name="save_staff_attendance" class="btn btn-flat btn-success">
        	        </div>
        </form>	
		</div>
	<?php
	} ?>	
		
		<!-- END -->
		</div>
		<div class='overlay gym-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>
